<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class EmployeeProfileController extends Controller
{

    public function validateEmployee($employeecode){
        $response = [
            'status' => false,
            'employee' => null
        ];

        // $$employeecode = trim($request->employee_code);

        if(!$employeecode){
            return response()->json($response);
        }

        $employee = Employees::where('employeecode', $employeecode)->get();
        
        if(count($employee) > 0){
            $response['status'] = true;
            $response['employee'] = $employee;
        }

        return response()->json($response);
    }

    public function createEmployee(Request $request){


        // $this->validate($request, [
        //     'employeecode' => 'required',
        // ]);

        try
        {
            DB::beginTransaction();
            
                $addEmployee = new Employees();
                $addEmployee->employeecode  = $request->employeecode;
                $addEmployee->name  = $request->name;
                $addEmployee->email  = $request->email;
                $addEmployee->dob  = $request->dob;
                $addEmployee->contact_no = $request->contact_no;
                $addEmployee->address = $request->address;
                $addEmployee->department = $request->department;
                $addEmployee->isadmin = $request->isadmin;
                $addEmployee->save();
                
            if(!$addEmployee) {
                DB::rollback();
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => 'success', 'data' => null]);
        }
        catch(RuntimeException $e)
        {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something Went Wrong', 'data' => null]);
        }
    







    }
}
