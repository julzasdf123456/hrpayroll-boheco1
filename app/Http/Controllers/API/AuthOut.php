<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IDGenerator;

class AuthOut extends Controller {
    public function login() {
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            // $success['token'] =  $user->createToken('assist')-> accessToken; 
            $success['username'] = request('username');
            $success['id'] = $user->id;
            return response()->json($user, 200); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
}
