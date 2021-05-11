<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function companyTransaction() {
        try {
            $data = Transaction::where('type', 'company')->orderBy('id', 'DESC')->paginate(10);
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

    public function employeeTransaction() {
        try {
            $data = Transaction::with('employee')->where('type', 'employee')->orderBy('id', 'DESC')->paginate(10);
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

}
