<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SMSNotifications;
use App\Models\Employees;
use App\Models\AttendanceData;
use Validator;

class SMSNotificationsAPI extends Controller {

    public $successStatus = 200;

    public function getRandomNotification(Request $request) {
        $sms = DB::table('SMSNotifications')
            ->select('*')
            ->where('Status', 'PENDING')
            ->whereRaw("TRY_CAST(created_at AS DATE) >= GETDATE()")
            ->orderBy('created_at')
            ->first();

        if ($sms != null) {
            return response()->json($sms, $this->successStatus);
        } else {
            return response()->json(['res' => 'No notifications found'], 404);
        }
    }

    public function updateSMSNotification(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        SMSNotifications::where('id', $id)
            ->update(['Status' => $status]);

        return response()->json('ok', 200);
    }

    public function insertSMSNotifForBiometricAttendance(Request $request) {
        $employees = DB::table('Employees')
            ->whereRaw("ContactNumbers IS NOT NULL AND LEN(ContactNumbers) > 9")
            ->get();

        $now = date('Y-m-d');

        $aInT = date('Y-m-d H:i:s', strtotime($now . ' 10:00'));
        $aOutT = date('Y-m-d H:i:s', strtotime($now . ' 12:01'));
        $pInStartT = date('Y-m-d H:i:s', strtotime($now . ' 12:31'));
        $pInEndT = date('Y-m-d H:i:s', strtotime($now . ' 13:05')); // 1:05pm
        $pOutT = date('Y-m-d H:i:s', strtotime($now . ' 17:00')); // 5pm

        foreach($employees as $item) {
            if ($item->BiometricsUserId != null) {
                $attendanceData = AttendanceData::where('BiometricUserId', $item->BiometricsUserId)
                    ->where(DB::raw("TRY_CAST(Timestamp AS DATE)"), $now)
                    ->get();

                if (count($attendanceData) > 0) {
                    $attLog = "";

                    foreach($attendanceData as $att) {
                        if ($att->Timestamp != null) {
                            $tm = date('Y-m-d H:i:s', strtotime($att->Timestamp));

                            if ($tm < $aInT) {
                                // time in
                                $attLog .= "Morning In: " . date('h:i A', strtotime($tm)). "\n";
                            } elseif ($tm >= $aInT && $tm < $aOutT) {
                                // morning undertime
                                $attLog .= "Morning LT/UT: " . date('h:i A', strtotime($tm)). "\n";
                            } elseif ($tm >= $aOutT && $tm < $pInStartT) {
                                // morning out
                                $attLog .= "Morning Out: " . date('h:i A', strtotime($tm)). "\n";
                            } elseif ($tm >= $pInStartT && $tm < $pInEndT) {
                                // afternoon in
                                $attLog .= "Afternoon In: " . date('h:i A', strtotime($tm)). "\n";
                            } elseif ($tm >= $pInEndT && $tm < $pOutT) {
                                // afternoon undertime
                                $attLog .= "Afternoon LT/UT: " . date('h:i A', strtotime($tm)). "\n";
                            } elseif ($tm >= $pOutT) {
                                // afternoon out
                                $attLog .= "Afternoon Out: " . date('h:i A', strtotime($tm)). "\n";
                            } else {
                                // ambiguos
                                $attLog .= "Ambiguous Logs: " . date('h:i A', strtotime($tm)). "\n";
                            }
                        }
                    }

                    $compo = env('APP_COMPANY_ABRV') . " HRS Notifs\n\n" . 
                        "Hello " . $item->FirstName . ", here's your daily biometric attendace log for " . date('M d, Y') . ":\n\n" . $attLog . "\n" . 
                        "Should you have any concerns regarding these logs, you may tender it to the HR office. \nHave a great day!";

                    SMSNotifications::sendSMS($item->ContactNumbers, $compo, null, null);
                }
            }
        }

        return response()->json($now, 200);
    }
}