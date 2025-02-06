<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\AttendaneConfirmations;
use App\Models\AttendaneConfirmationSignatories;
use App\Models\AttendanceData;
use App\Models\Users;
use App\Models\Employees;
use App\Models\Notifications;
use App\Models\SMSNotifications;

class AttendanceConfirmationAPI extends Controller {
    public function postAttendanceConfirmation(Request $request) {
        $validated = $request->validate([
            'UserId' => 'required|string',
            'EmployeeId' => 'required|string',
            'Reason' => 'required|string',
            'TimeSlot' => 'required|string',
            'Date' => 'required|string',
            'Time' => 'required|string',
            'Signatories' => 'required|array',
        ]);

        $userId = $validated['UserId'];
        $employeeId = $validated['EmployeeId'];
        $reason = $validated['Reason'];
        $timeSlot = $validated['TimeSlot'];
        $date = $validated['Date'];
        $time = $validated['Time'];
        $signatories = $validated['Signatories'];

        $id = IDGenerator::generateID();
        $attConf = AttendaneConfirmations::create([
            'id' => $id,
            'EmployeeId' => $employeeId,
            'UserId' => $userId,
            'Status' => 'FILED',
            'Reason' => $reason,
            'AMIn' => AttendaneConfirmations::validateTimeSlot(date('Y-m-d H:i:s', strtotime($date . ' ' . $time)), $timeSlot, 'Morning In'),
            'AMOut' => AttendaneConfirmations::validateTimeSlot(date('Y-m-d H:i:s', strtotime($date . ' ' . $time)), $timeSlot, 'Morning Out'),
            'PMIn' => AttendaneConfirmations::validateTimeSlot(date('Y-m-d H:i:s', strtotime($date . ' ' . $time)), $timeSlot, 'Afternoon In'),
            'PMOut' => AttendaneConfirmations::validateTimeSlot(date('Y-m-d H:i:s', strtotime($date . ' ' . $time)), $timeSlot, 'Afternoon Out'),
            'OTIn' => AttendaneConfirmations::validateTimeSlot(date('Y-m-d H:i:s', strtotime($date . ' ' . $time)), $timeSlot, 'Overtime In'),
            'OTOut' => AttendaneConfirmations::validateTimeSlot(date('Y-m-d H:i:s', strtotime($date . ' ' . $time)), $timeSlot, 'Overtime Out'),
        ]);

        $employee = Employees::find($employeeId);

        // INSERT SIGNATORIES
        if (isset($signatories)) {
            foreach($signatories as $i => $item) {
                $attSigs = new AttendaneConfirmationSignatories;
                $attSigs->id = IDGenerator::generateID() . "" . $i;
                $attSigs->AttendanceConfirmationId = $id;
                $attSigs->EmployeeId = $item['UserId'];
                $attSigs->Rank = $item['Rank'];
                $attSigs->Status = null;
                $attSigs->save();

                // send notifications if first signatory
                if ($item['Rank'] == '1' | $item['Rank'] == 1) {
                    $u = Users::find($item['UserId']);
                    if ($u != null) {
                        $fRank = Employees::find($u->employee_id);

                        // send notification
                        Notifications::create([
                            'UserId' => $u->id,
                            'Content' => ($employee != null ? ($employee->FirstName . " " . $employee->LastName) : $u->name) . " has filed an attendance confirmation request that needs your approval. ",
                            'Type' => 'ATT_CONF_APPROVAL',
                            'Notes' => $id,
                            'Status' => 'UNREAD',
                            'ForSignatory' => 'Yes',
                        ]);

                        // send sms
                        if ($fRank != null && $fRank->ContactNumbers != null) {
                            SMSNotifications::sendSMS($fRank->ContactNumbers, 
                                "HRS Attendance Confirmation Approval\n\nHello " . $fRank->FirstName . ", " . $employee->FirstName . " " . $employee->LastName . " has filed an attendance confirmation request that needs your approval. " .
                                    "Kindly check your HR System approval module for more info.",
                                "HR-Attendance Confirmation",
                                $id
                            );
                        }
                    }                    
                }
            }
        }

        return response()->json(['res' => 'ok'], 200);
    }

    public function getAttendanceConfirmation(Request $request) {
        $id = $request['id'];

        $data = AttendaneConfirmations::find($id);

        if ($data != null) {
            $data->Employee = Employees::find($data->EmployeeId);

            $data->Signatories = DB::table('AttendanceConfirmationSignatories')
                ->leftJoin("users", "AttendanceConfirmationSignatories.EmployeeId", "=", 'users.id')
                ->select(
                    "AttendanceConfirmationSignatories.*",
                    "users.name"
                )
                ->where('AttendanceConfirmationSignatories.AttendanceConfirmationId', $data->id)
                ->orderBy("Rank")
                ->get();

            return response()->json($data, 200);
        } else {
            return response()->json('not found', 404);
        }
    }

    public function approveAttendanceConfirmation(Request $request) {
        $id = $request['id'];
        $signatoryId = $request['SignatoryId'];

        $attConf = AttendaneConfirmations::find($id);
        $signatory = AttendaneConfirmationSignatories::find($signatoryId);

        // UPDATE SIGNATORIES
        $signatory->Status = 'APPROVED';
        $signatory->save();

        // update notification
        Notifications::where('Notes', $id)
            ->where('UserId', $signatory->EmployeeId)
            ->update(['Status' => 'READ']);

        // GET USER
        $user = Users::where('employee_id', $attConf->EmployeeId)->first();
        $employee = Employees::find($attConf->EmployeeId);

        // ADD NOTIFICATION FOR THE REQUISITIONER
        $notifications = new Notifications;
        $notifications->UserId = $user != null ? $user->id : '';
        $notifications->Type = 'ATT_CONF_APPROVAL';
        $notifications->Content = Users::find($signatory->EmployeeId)->name . ' has approved your offset application.';
        $notifications->Notes = $id;
        $notifications->Status = "UNREAD";
        $notifications->save();

        // ADD NOTIFICATIONS FOR NEXT SIGNATORY
        $nextSignatory = DB::table('AttendanceConfirmationSignatories')
            ->whereRaw("AttendanceConfirmationId='" . $attConf->id . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $signatory->Rank)
            ->orderBy('Rank')
            ->first();

        if ($nextSignatory != null) {
            // IF LEAVE IS STILL NOTE COMPLETED SIGNING
            $notifications = new Notifications;
            $notifications->UserId = $nextSignatory->EmployeeId;
            $notifications->Type = 'ATT_CONF_APPROVAL';
            $notifications->Content = Employees::getMergeName(Employees::find($attConf->EmployeeId)) . " has filed an attendance confirmation request. Check it out to approve.";
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();

            /**
             * =========================================================================
             * SEND SMS TO NEXT SIGNATORY
             * =========================================================================
             */
            $u = Users::find($nextSignatory->EmployeeId);
            if ($u != null) {
                $fRank = Employees::find($u->employee_id);

                if ($fRank != null && $fRank->ContactNumbers != null) {
                    SMSNotifications::sendSMS($fRank->ContactNumbers, 
                        "HRS Attendance Confirmation Approval\n\nHello " . $fRank->FirstName . ", " . $employee->FirstName . " " . $employee->LastName . " has filed an Attendance Confirmation request that needs your approval. " .
                            "Kindly check your HR System approval module for more info.",
                        "HR-Attendance Confirmation",
                        $id
                    );
                }
            }

            /**
             * =========================================================================
             * SEND SMS TO EMPLOYEE
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS Attendance Confirmation Approval\n\nHello " . $employee->FirstName . ", " . Users::find($signatory->EmployeeId)->name . " has APPROVED your Attendance Confirmation request. It is now forwarded to the next signatory.",
                    "HR-Attendance Confirmation",
                    $id
                );
            }

            // UPDATE ATTENDANCE CONFIRMATION STATUS
            $attConf->Status = 'Partially Approved';
            $attConf->save();
            
        } else {
            // UPDATE ATTENDANCE CONFIRMATION STATUS
            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS Attendance Confirmation Approval\n\nHello " . $employee->FirstName . ", " . Users::find($signatory->EmployeeId)->name . " has APPROVED your Attendance Confirmation request.",
                    "HR-Attendance Confirmation",
                    $id
                );
            }
            
            $attConf->Status = 'APPROVED';
            $attConf->save();

            // save attednace
            // INSERT START MORNING IN
            if ($attConf->AMIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $attConf->AMIn;
                $attendance->save();
            }            

            // INSERT START MORNING OUT
            if ($attConf->AMOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $attConf->AMOut;
                $attendance->save();
            }            

            // INSERT START AFTERNOON IN
            if ($attConf->PMIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $attConf->PMIn;
                $attendance->save();
            }
            
            // INSERT START AFTERNOON OUT
            if ($attConf->PMOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $attConf->PMOut;
                $attendance->save();
            }

            // INSERT START OT IN
            if ($attConf->OTIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $attConf->OTIn;
                $attendance->save();
            }
            
            // INSERT START OT OUT
            if ($attConf->OTOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $attConf->OTOut;
                $attendance->save();
            }
        }
        
        return response()->json('ok', 200); 
    }

    public function getAllAttendanceConfirmations(Request $request) {
        $employeeId = $request['EmployeeId'];

        $data = AttendaneConfirmations::where('EmployeeId', $employeeId)
            ->select(
                '*',
                DB::raw("NULL AS Signatories"),
                DB::raw("NULL AS Employee"),
            )
            ->orderByDesc('created_at')
            ->simplePaginate(15);

        return response()->json($data->items(), 200);
    }

    public function deleteAttendanceConfirmation(Request $request) {
        $id = $request['id'];

        $attConf = AttendaneConfirmations::find($id);

        if ($attConf != null) {
            AttendaneConfirmationSignatories::where('AttendanceConfirmationId', $id)->delete();

            Notifications::where('Notes', $id)->delete();

            $attConf->delete();
        } 

        return response()->json($attConf, 200);
    }
}