<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IDGenerator;
use Rats\Zkteco\Lib\ZKTeco;

class Biometrics extends Controller {
    public function getUsers(Request $request) {
        $ip = $request['Ip'];
        
        $zk = new ZKTeco($ip);
        $zk->connect();
        $users = $zk->getUser();

        $arr = [];
        foreach ($users as $key => $value) {
            array_push($arr, [
                "uid" => $value['uid'],
                "userid"=> $value['userid'],
                "name"=> $value['name'],
                "role"=> $value['role'],
                "password"=> $value['password'],
                "cardno"=> $value['cardno']
            ]);
        }

        $zk->disconnect();
        return response()->json($arr, 200);
    }

    public function getAttendance(Request $request) {
        $ip = $request['Ip'];
        
        $zk = new ZKTeco($ip);
        $zk->connect();
        $attendance = $zk->getAttendance();

        $arr = [];
        foreach ($attendance as $key => $value) {
            array_push($arr, [
                "uid" => $value['uid'],
                "id"=> $value['id'], // USER ID
                "state"=> $value['state'],
                "timestamp"=> $value['timestamp'],
                "type"=> $value['type'],
            ]);
        }

        $zk->disconnect();
        return response()->json($arr, 200);
    }

    public function getVersion(Request $request) {
        $ip = $request['Ip'];
        
        $zk = new ZKTeco($ip);
        $zk->connect();
        
        $version = $zk->getAttendance();

        $zk->disconnect();
        return response()->json($version, 200);
    }
}