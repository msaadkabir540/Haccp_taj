<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employees;
use Laravel\Passport\HasApiTokens;
use DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $employeecode = $request->get('employeecode');
        $password = $request->get('password');

        $isEmployeeExist = Employees::where('employeecode', $employeecode)->where('quit', 0)->distinct()->count();
        

        if($isEmployeeExist == 0){
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => 'employee_not_exists' 
            ]);
        }
        

        $isAccountAlreadyExists = User::where('employeecode', $employeecode)->distinct()->count();

        if($isAccountAlreadyExists > 0){
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => 'user_already_exists'
            ]);
        }

        $request->validate([
            // 'name' => 'required|string',
            'employeecode' => 'required|employeecode|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            // 'name' => $request->name,
            'employeecode' => $employeecode,
            'password' => bcrypt($password),
        ]);

        // return response()->json(['user' => $user]);
        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'SignUp Successfully'
        ]);
    }

    

    public function login(Request $request)
    {
        // dd($request);
        $credentials = $request->validate([
            'employeecode' => 'required|employeecode',
            'password' => 'required|string',
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $employeeData = Employees::where('employeecode', $user->employeecode)->first();

            return $this->respondWithToken($token,$employeeData);

            // return response()->json([
            //     'status' => true,
            //     'data' => $employeeData,
            //     'token' => $token,
            //     'message' => 'Login Successfully'
            // ]);
        }

        return response()->json([
            'status' => false,
            'token' => null,
            'error' => 'Unauthorized'
        ], 401);
        
        // return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        // dd($request);
        $request->user()->currentAccessToken()->delete();


        return response()->json(['message' => 'Successfully logged out']);
    }


    protected function respondWithToken($token,$employeeData){

        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => $this->guard('api')->factory()->getTTL() * 36000,
            'employee' => $employeeData
        ]);
    }


    // public function logout(Request $request)
    // {
    //     $request->user()->tokens()->delete();

    //     return response()->json(['message' => 'Logged out']);
    // }
}
