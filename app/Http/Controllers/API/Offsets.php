<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\OffsetSignatories;
use App\Models\OffsetApplications;
use App\Models\AttendanceData;
use App\Models\Users;
use App\Models\Employees;
use App\Models\Notifications;
use App\Models\SMSNotifications;
use App\Models\PayrollSchedules;

class Offsets extends Controller {
    public function postOffset(Request $request) {
        $validated = $request->validate([
            'UserId' => 'required|string',
            'EmployeeId' => 'required|string',
            'DateOfDuty' => 'required|string',
            'Purpose' => 'required|string',
            'DateOfOffset' => 'required|string',
            'Reason' => 'required|string',
            'Signatories' => 'required|array',
            'Duration' => 'required|string',
        ]);

        $userId = $validated['UserId'];
        $employeeId = $validated['EmployeeId'];
        $dateOfDuty = $validated['DateOfDuty'];
        $purpose = $validated['Purpose'];
        $dateOfOffset = $validated['DateOfOffset'];
        $reason = $validated['Reason'];
        $duration = $validated['Duration'];
        $signatories = $validated['Signatories'];

        // INSERT INTO OFFSET APPLICATIONS
        $id = IDGenerator::generateID();
        $batchId = IDGenerator::generateID();

        $offset = new OffsetApplications;
        $offset->OffsetBatchId = $batchId;
        $offset->id = $id;
        $offset->PreparedBy = $userId;
        $offset->DatePrepared = date('Y-m-d');
        $offset->EmployeeId = $employeeId;
        $offset->DateOfDuty = $dateOfDuty;
        $offset->PurposeOfDuty = $purpose;
        $offset->DateOfOffset = $dateOfOffset;
        $offset->OffsetReason = $reason;
        $offset->Duration = $duration;
        $offset->Status = 'FILED';
        $offset->save();

        $employee = Employees::find($employeeId);

        // INSERT SIGNATORIES
        if (isset($signatories)) {
            foreach($signatories as $i => $item) {
                $offsetSig = new OffsetSignatories;
                $offsetSig->id = IDGenerator::generateID() . "" . $i;
                $offsetSig->OffsetBatchId = $batchId;
                $offsetSig->EmployeeId = $item['UserId'];
                $offsetSig->Rank = $item['Rank'];
                $offsetSig->Status = null;
                $offsetSig->save();

                // send notifications if first signatory
                if ($item['Rank'] == '1' | $item['Rank'] == 1) {
                    $u = Users::find($item['UserId']);
                    if ($u != null) {
                        $fRank = Employees::find($u->employee_id);

                        // send notification
                        Notifications::create([
                            'UserId' => $u->id,
                            'Content' => ($employee != null ? ($employee->FirstName . " " . $employee->LastName) : $u->name) . " has filed an offset application that needs your approval. ",
                            'Type' => 'OFFSET_APPROVAL',
                            'Notes' => $id,
                            'Status' => 'UNREAD',
                            'ForSignatory' => 'Yes',
                        ]);

                        // send sms
                        if ($fRank != null && $fRank->ContactNumbers != null) {
                            SMSNotifications::sendSMS($fRank->ContactNumbers, 
                                "HRS Offset Approval\n\nHello " . $fRank->FirstName . ", " . $employee->FirstName . " " . $employee->LastName . " has filed an offset application that needs your approval. " .
                                    "Kindly check your HR System approval module for more info.",
                                "HR-Offset",
                                $id
                            );
                        }
                    }                    
                }
            }
        }

        return response()->json(['res' => 'ok'], 200);
    }

    public function getOffset(Request $request) {
        $id = $request['id'];

        $data = OffsetApplications::find($id);

        if ($data != null) {
            $data->Employee = Employees::find($data->EmployeeId);

            $data->Signatories = DB::table('OffsetSignatories')
                ->leftJoin("users", "OffsetSignatories.EmployeeId", "=", 'users.id')
                ->select(
                    "OffsetSignatories.*",
                    "users.name"
                )
                ->where('OffsetSignatories.OffsetBatchId', $data->OffsetBatchId)
                ->orderBy("Rank")
                ->get();

            return response()->json($data, 200);
        } else {
            return response()->json('not found', 404);
        }
    }

    public function approveOffset(Request $request) {
        $id = $request['id'];
        $signatoryId = $request['SignatoryId'];

        $offset = OffsetApplications::find($id);
        $offsetSignatory = OffsetSignatories::find($signatoryId);

        // UPDATE SIGNATORIES
        $offsetSignatory->Status = 'APPROVED';
        $offsetSignatory->save();

        // GET USER
        $user = Users::where('employee_id', $offset->EmployeeId)->first();
        $employee = Employees::find($offset->EmployeeId);
        $payrollSchedule = PayrollSchedules::find($employee->PayrollScheduleId);

        // ADD NOTIFICATION FOR THE REQUISITIONER
        $notifications = new Notifications;
        $notifications->UserId = $user != null ? $user->id : '';
        $notifications->Type = 'OFFSET_APPROVAL';
        $notifications->Content = Users::find($offsetSignatory->EmployeeId)->name . ' has approved your offset application.';
        $notifications->Notes = $id;
        $notifications->Status = "UNREAD";
        $notifications->save();

        // ADD NOTIFICATIONS FOR NEXT SIGNATORY
        $nextSignatory = DB::table('OffsetSignatories')
            ->whereRaw("OffsetBatchId='" . $offset->OffsetBatchId . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $offsetSignatory->Rank)
            ->orderBy('Rank')
            ->first();

        if ($nextSignatory != null) {
            // IF LEAVE IS STILL NOTE COMPLETED SIGNING
            $notifications = new Notifications;
            $notifications->UserId = $nextSignatory->EmployeeId;
            $notifications->Type = 'OFFSET_APPROVAL';
            $notifications->Content = Employees::getMergeName(Employees::find($offset->EmployeeId)) . " has filed an offset application. Check it out to approve.";
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
                        "HRS Offset Approval\n\nHello " . $fRank->FirstName . ", " . $employee->FirstName . " " . $employee->LastName . " has filed an offset application that needs your approval. " .
                            "Kindly check your HR System approval module for more info.",
                        "HR-Offset",
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
                    "HRS Offset Approval\n\nHello " . $employee->FirstName . ", " . Users::find($offsetSignatory->EmployeeId)->name . " has APPROVED your offset application. It is now forwarded to the next signatory.",
                    "HR-Offset",
                    $id
                );
            }

            // UPDATE LEAVE STATUS
            $offset->Status = 'Partially Approved';
            $offset->save();
            
        } else {
            // UPDATE OFFSET STATUS
            // THIS PORTION IS WHEN THE LEAVE HAS FULLY SIGNED BY ALL SIGNATORIES    

            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS Offset Approval\n\nHello " . $employee->FirstName . ", " . Users::find($offsetSignatory->EmployeeId)->name . " has APPROVED your offset application.",
                    "HR-Offset",
                    $id
                );
            }
            
            $offset->Status = 'APPROVED';
            $offset->save();

            if ($offset->Duration != null && $offset->Duration === 'WHOLE') {
                // INSERT START MORNING IN
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 07:31:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();

                // INSERT START MORNING OUT
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 12:05:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();

                // INSERT START AFTERNOON IN
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 12:45:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();

                // INSERT START AFTERNOON OUT
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 17:05:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();
            } elseif ($offset->Duration != null && $offset->Duration === 'AM') {
                // INSERT START MORNING IN
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 07:31:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();

                // INSERT START MORNING OUT
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 12:05:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();
            } elseif ($offset->Duration != null && $offset->Duration === 'PM') {
                // INSERT START AFTERNOON IN
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 12:45:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();

                // INSERT START AFTERNOON OUT
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 17:05:00';
                $attendance->AbsentPermission = 'OFFSET';
                $attendance->save();
            }
        }
        
        return response()->json('ok', 200);
    }

    public function rejectOffset(Request $request) {
        $id = $request['id'];
        $signatoryId = $request['SignatoryId'];
        $notes = $request['Notes'];

        $offset = OffsetApplications::find($id);
        $offsetSignatory = OffsetSignatories::find($signatoryId);
        $employee = Employees::find($offset->EmployeeId);

        if ($offset != null) {
            $offset->Status = 'REJECTED';
            $offset->save();
        }

        if ($offsetSignatory != null) {
            $offsetSignatory->Status = 'REJECTED';
            $offsetSignatory->Notes = $notes;
            $offsetSignatory->save();

            $nextSignatories = DB::table('OffsetSignatories')
                ->whereRaw("OffsetBatchId='" . $offset->OffsetBatchId . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $offsetSignatory->Rank)
                ->orderBy('Rank')
                ->get();
            foreach ($nextSignatories as $item) {
                $leaveSig = OffsetSignatories::find($item->id);
                if ($leaveSig != null) {
                    $leaveSig->Status = 'REJECTED';
                    $leaveSig->Notes = 'Auto rejected due to rejection of previous signatory.';
                    $leaveSig->save();
                }
            }

            // INSERT NOTIF
            $user = Users::where('employee_id', $offset->EmployeeId)->first();
            $notifications = new Notifications;
            $notifications->UserId = $user != null ? $user->id : '';
            $notifications->Type = 'OFFSET_INFO';
            $notifications->Content = Users::find($offsetSignatory->EmployeeId)->name . ' REJECTED your offset application.';
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();
            
            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS Offset Approval:\n\n" . Users::find($offsetSignatory->EmployeeId)->name . " has DISAPPROVED your offset application due to the following reasons:\n\n" . $notes,
                    "HR-Offset",
                    $id
                );
            }
        }

        return response()->json($offset, 200);
    }

    public function getAllOffsets(Request $request) {
        $employeeId = $request['EmployeeId'];

        $data = OffsetApplications::where('EmployeeId', $employeeId)
            ->orderByDesc('created_at')
            ->simplePaginate(5);

        return response()->json($data->items(), 200);
    }
}