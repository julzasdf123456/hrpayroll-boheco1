<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Users;
use App\Models\Employees;
use App\Models\SMSNotifications;
use function PHPUnit\Framework\returnValue;

class AuthOut extends Controller {
    public function login() {
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $user->id = "" . $user->id . "";
            // $success['token'] =  $user->createToken('assist')-> accessToken; 
            $success['username'] = request('username');
            $success['id'] = $user->id;

            // $token = $user->createToken('assist')-> accessToken; 
            return response()->json($user, 200); 

            // return response()->json([
            //     'user' => $user,
            //     'token' => $token
            // ], 200); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function checkEmployeeId(Request $request) {
        $employeeId = $request['EmployeeId'];

        $user = Users::where('employee_id', $employeeId)->first();

        if ($user != null) {
            $employee = Employees::find($user->employee_id);

            if ($employee != null && $employee->ContactNumbers != null) {                
                $user->OTP = IDGenerator::generateOTP();
                $user->save();

                // send otp to mobile number
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS OTP\n\nHello " . $employee->FirstName . ", You have requested to reset your password from the HRS app. Here's your OTP: \n\n" . $user->OTP .
                        "\n\nIf this wasn't you, make a complain to the HR office to secure your account.",
                    "HR-OTP",
                    $user->id
                );

                return response()->json($user, 200);
            } else {
                return response()->json(['msg' => 'contact number not found'], 403);
            }
        } else {
            return response()->json(['msg' => 'user not found'], 404);
        }
    }

    public function verifyOTP(Request $request) {
        $userId = $request['UserId'];
        $otp = $request['OTP'];

        $user = Users::where('id', $userId)
            ->where('OTP', $otp)
            ->first();

        if ($user != null) {
            return response()->json($user, 200);
        } else {
            return response()->json(['msg' => 'user or otp not found'], 404);
        }
    }

    public function resendOTP(Request $request) {
        $userId = $request['UserId'];

        $user = Users::where('id', $userId)->first();

        if ($user != null) {
            $employee = Employees::find($user->employee_id);

            if ($employee != null && $employee->ContactNumbers != null) {                
                $user->OTP = IDGenerator::generateOTP();
                $user->save();

                // send otp to mobile number
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS OTP\n\nHello " . $employee->FirstName . ", You have requested to reset your password from the HRS app. Here's your OTP: \n\n" . $user->OTP .
                        "\n\nIf this wasn't you, make a complain to the HR office to secure your account.",
                    "HR-OTP",
                    $user->id
                );

                return response()->json($user, 200);
            } else {
                return response()->json(['msg' => 'contact number not found'], 403);
            }
        } else {
            return response()->json(['msg' => 'user not found'], 404);
        }
    }

    public function resetPassword(Request $request) {
        $id = $request['UserId'];
        $password = $request['Password'];

        // Assuming you have a user authenticated
        $user = Users::find($id);

        // Update the user's password
        $user->password = Hash::make($password);
        $user->save();

        return response()->json($user, 200);
    }

    

    public function loginUser(Request $req) { // created by Domz.

        if ( $req['username']== null ||  $req['password'] == null ) {
            return response()->json(['message'=> 'Please fill the form.'], 400);
        }

        if (Auth::attempt(['username'=> $req['username'],'password'=> $req['password']])){
            $user = Users::where('username', $req['username'])->first();
            $user->makeHidden('password');
            $user->makeHidden('remember_token');

            $token = $user->createToken('user_auth_token')->plainTextToken;

            return response()->json([
                'token'=> $token,
                'user'=> $user,
            ], 201);
        } else {
            return response()->json(['message'=> 'Invalid Credentials.'], 401);
        }
    }

    public function verifyUser(Request $req) {

        $user = Users::where('id', $req->user()->id)->first();
        $user->makeHidden('password');
        $user->makeHidden('remember_token');
        return $user; 
    }
}
