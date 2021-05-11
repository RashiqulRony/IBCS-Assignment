<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyAccount;
use App\Models\Employee;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Validator;

class SalaryController extends Controller
{

    public function salarySummary () {
        try {
            $employees = Employee::with('grade')->get();
            $companyAccount = CompanyAccount::first();

            $totalBasic = 0;
            $totalHouseRent = 0;
            $totalMedical = 0;
            foreach ($employees as $employee) {
                $totalBasic     += $employee->grade->salary;
                $totalHouseRent += $this->getHouseRentAndMedical($employee->grade->salary, 20);
                $totalMedical   += $this->getHouseRentAndMedical($employee->grade->salary, 15);
            }
            $totalSalary = $totalMedical + $totalHouseRent + $totalBasic;

            return response()->json([
                'status'            => true,
                'totalBasic'        => $totalBasic,
                'totalHouseRent'    => $totalHouseRent,
                'totalMedical'      => $totalMedical,
                'totalSalary'       => $totalSalary,
                'companyBalance'    => !empty($companyAccount) ? $companyAccount->balance : "0.00",
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status'  => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function paymentSalary(Request $request) {
        try {
            if (isset($request->is_add_more)) {
                $rules = [
                    'amount' => "required|numeric|between:1,9999999999.00",
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()) {
                    return response()->json(['status' => false, 'errors'=> $validator->messages()]);
                }
            }

            $employees = Employee::with('grade', 'account')->get();
            $companyAccount = CompanyAccount::first();
            if (!empty($companyAccount)) {
                $companyAccount->update(['balance' => $companyAccount->balance + (isset($request->amount) ? $request->amount : 0)]);
            } else {
                $companyAccount =  CompanyAccount::create([
                    'title' => 'Main Account',
                    'balance' => (isset($request->amount) ? $request->amount : 0)
                ]);
            }
            if (isset($request->amount) && $request->amount > 0) {
                $note = 'Add amount for employee salary purpose';
                $this->transactions('company','deposit', $request->amount, $note);
            }


            $totalBasic = 0;
            $totalHouseRent = 0;
            $totalMedical = 0;
            $addBalance = isset($request->amount) ? $request->amount : 0;

            foreach ($employees as $employee) {
                $totalBasic     += $employee->grade->salary;
                $totalHouseRent += $this->getHouseRentAndMedical($employee->grade->salary, 20);
                $totalMedical   += $this->getHouseRentAndMedical($employee->grade->salary, 15);
            }
            $totalSalary = $totalMedical + $totalHouseRent + $totalBasic;

            if (($companyAccount->balance + $addBalance) >= $totalSalary) {
                foreach ($employees as $employee) {
                    $empTotalSalary = $employee->grade->salary + ($this->getHouseRentAndMedical($employee->grade->salary, 20)) + ($this->getHouseRentAndMedical($employee->grade->salary, 15));
                    $employee->account->current_balance += $empTotalSalary;
                    $employee->account->save();
                    $note = 'Transfer For employee salary';
                    $this->transactions('employee','deposit', $empTotalSalary, $note, $employee->id);
                }
                $companyAccount->update(['balance' => $companyAccount->balance - $totalSalary]);
                if ($companyAccount) {
                    $note = 'Transfer company main account to employee account';
                    $this->transactions('company','withdraw', $totalSalary, $note);
                }

                return response()->json([
                    'status'  => true,
                    'message' => "Salary payment successfully"
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Company account don't have enough balance. Please select checkbox and Add more money!"
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    protected function getHouseRentAndMedical($amount, $percentage) {
       return ($amount / 100) * $percentage;
    }

    protected function transactions($type, $transfer_type, $amount, $note, $id = null) {
        Transaction::create([
            'employee_id' => $id,
            'type' => $type,
            'transfer_type' => $transfer_type,
            'amount' => $amount,
            'note' => $note,
        ]);
    }
}
