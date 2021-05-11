<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Grade::orderBy('id', 'DESC')->get();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $previousSalary = Grade::orderBy('id','DESC')->first();
        if (!empty($previousSalary)) {
            $rules = [
                'title' => 'required|max:191',
            ];
            $salary = $previousSalary->salary + 5000;
        } else {
            $rules = [
                'title' => 'required|max:191',
                'salary' => "required|numeric|between:5000,100000.00",
            ];
            $salary = $request->salary;
        }


        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json(['status' => false, 'errors'=> $validator->messages()]);
        }

        try {
            Grade::create([
                'title'  => $request->title,
                'salary' => $salary,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Grade save successfully.',
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
            $data = Grade::find($id);
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
            'title' => 'required|max:191',
            'salary' => "required|numeric|between:5000,100000.00",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json(['status' => false, 'errors'=> $validator->messages()]);
        }

        try {
            Grade::find($id)->update([
                'title'  => $request->title,
                'salary' => $request->salary,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Grade update successfully.',
            ]);
        }catch (\Exception $exception) {
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
            Grade::find($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Grade delete successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function previousGrade ()
    {
        try {
            $previousSalary = Grade::orderBy('id','DESC')->first();
            if (!empty($previousSalary)) {
                return response()->json([
                    'status' => true,
                    'data' => $previousSalary
                ]);
            } else {
                return response()->json([
                    'status' => false,
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
