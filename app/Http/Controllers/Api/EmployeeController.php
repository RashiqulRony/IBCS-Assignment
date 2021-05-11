<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Employee::with('grade', 'account')->orderBy('id', 'DESC')->get();
            return response()->json([
                'status' => true,
                'data'   => $data,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'grade'          => 'required|exists:grades,id',
            'name'           => 'required|max:191',
            'mobile_number'  => 'required|size:11|regex:/(01)[0-9]{9}/',
            'address'        => 'required|max:300',
            'account_type'   => 'required|in:savings,current',
            'account_name'   => 'required|max:191',
            'account_number' => 'required|numeric|digits_between:13,16',
            'bank_name'      => 'required|max:191',
            'branch_name'    => 'required|max:191',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json(['status' => false, 'errors'=> $validator->messages()]);
        }

        try {
            $employee = Employee::create([
                'employee_id'   => rand (1000, 9999),
                'grade_id'      => $request->grade,
                'name'          => $request->name,
                'mobile_number' => $request->mobile_number,
                'address'       => $request->address,
            ]);

            if (!empty($employee)) {
                EmployeeAccount::create([
                    'employee_id'     => $employee->id,
                    'account_type'    => $request->account_type,
                    'account_name'    => $request->account_name,
                    'account_number'  => $request->account_number,
                    'bank_name'       => $request->bank_name,
                    'branch_name'     => $request->branch_name,
                    'current_balance' => 0.00,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee save successfully'
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $data = Employee::with('grade', 'account')->find($id);
            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'grade'          => 'required|exists:grades,id',
            'name'           => 'required|max:191',
            'mobile_number'  => 'required|size:11|regex:/(01)[0-9]{9}/',
            'address'        => 'required|max:300',
            'account_type'   => 'required|in:savings,current',
            'account_name'   => 'required|max:191',
            'account_number' => 'required|numeric|digits_between:13,16',
            'bank_name'      => 'required|max:191',
            'branch_name'    => 'required|max:191',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json(['status' => false, 'errors'=> $validator->messages()]);
        }

        try {
            Employee::find($id)->update([
                'grade_id'      => $request->grade,
                'name'          => $request->name,
                'mobile_number' => $request->mobile_number,
                'address'       => $request->address,
            ]);

            $account = EmployeeAccount::where('employee_id', $id)->find($request->account_id);
            if (!empty($account)) {
                $account->update([
                    'account_type'    => $request->account_type,
                    'account_name'    => $request->account_name,
                    'account_number'  => $request->account_number,
                    'bank_name'       => $request->bank_name,
                    'branch_name'     => $request->branch_name,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Employee save successfully.'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Employee::find($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Employee delete successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
