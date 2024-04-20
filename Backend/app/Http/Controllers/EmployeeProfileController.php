<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
}
