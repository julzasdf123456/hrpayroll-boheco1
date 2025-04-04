<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TripTicketLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Employees;
use App\Models\SMSNotifications;
use App\Models\Users;
use App\Models\Vehicles;
use App\Models\TripTicketDestinations;
use App\Models\TripTickets;
use App\Models\TripTicketSignatories;
use App\Models\TripTicketPassengers;
use App\Models\Notifications;
use Carbon\Carbon;

class TripTicketsAPI extends Controller
{
    public function getTripTicketDependencies(Request $request)
    {
        $id = $request['id'];

        $vehicles = Vehicles::orderBy('VehicleName')->get();
        $drivers = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.id', '=', 'Employees.Designation')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*')
            ->whereRaw("Positions.Level='Driver' OR Employees.AuthorizedToDrive='Yes'")
            ->orderBy('Employees.LastName')
            ->get();
        $employees = Employees::orderBy('LastName')->get();

        $signatories = Employees::getSupers($id, ['Chief', 'Manager', 'General Manager']);

        $otherSignatories = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.id', '=', 'Employees.Designation')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('users.id', 'Employees.id AS EmployeeId', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
            ->whereRaw("Positions.Level IN ('Chief', 'Manager', 'General Manager')")
            ->get();

        return response()->json(
            [
                'Vehicles' => $vehicles,
                'Drivers' => $drivers,
                'Employees' => $employees,
                'Signatories' => $signatories,
                'OtherSignatories' => $otherSignatories,
            ],
            200
        );
    }

    public function postTripTicket(Request $request)
    {
        $validated = $request->validate([
            'EmployeeId' => 'required|string',
            'PurposeOfTravel' => 'required|string',
            'Driver' => 'required|string',
            'Status' => 'required|string',
            'UserId' => 'required|string',
            'DateOfTravel' => 'required|string',
            'Vehicle' => 'required|string',
            'DestinationTyped' => 'required|string',
            'Passengers' => 'required|array',
            'Signatory' => 'required|string',
        ]);

        $id = IDGenerator::generateID();
        $tripTickets = TripTickets::create([
            'id' => $id,
            'DatetimeFiled' => date('Y-m-d H:i:s'),
            'EmployeeId' => $validated['EmployeeId'],
            'PurposeOfTravel' => $validated['PurposeOfTravel'],
            'Driver' => $validated['Driver'],
            'Status' => $validated['Status'],
            'UserId' => $validated['UserId'],
            'DateOfTravel' => $validated['DateOfTravel'],
            'Vehicle' => $validated['Vehicle'],
            'DestinationTyped' => $validated['DestinationTyped'],
        ]);

        // SET MANUALLY TYPED DESTINATIONS
        $destination = $validated['DestinationTyped'];
        if ($destination != null) {
            $destinations = explode(";", $destination);

            for ($i = 0; $i < count($destinations); $i++) {
                if ($destinations[$i] != null) {
                    $destination = new TripTicketDestinations;
                    $destination->id = IDGenerator::generateIDandRandString();
                    $destination->TripTicketId = $id;
                    $destination->DestinationAddress = trim($destinations[$i]);
                    $destination->save();
                }
            }
        }

        // add passengers
        $passengers = $validated['Passengers'];
        foreach ($passengers as $item) {
            TripTicketPassengers::create([
                'id' => IDGenerator::generateIDandRandString(),
                'TripTicketId' => $id,
                'EmployeeId' => $item['id'],
            ]);
        }

        // SET SIGNATORY
        if ($validated['Signatory']) {
            $signatory = new TripTicketSignatories;
            $signatory->id = IDGenerator::generateIDandRandString();
            $signatory->TripTicketId = $id;
            $signatory->EmployeeId = $validated['Signatory']; // user id
            $signatory->Rank = 1;
            $signatory->save();

            $user = Users::find($validated['Signatory']);
            $employee = Employees::find($user->employee_id);
            $requisitioner = Employees::find($tripTickets->EmployeeId);
            if ($employee != null) {
                // send notification
                Notifications::create([
                    'UserId' => $user->id,
                    'Content' => ($requisitioner != null ? ($requisitioner->FirstName . " " . $requisitioner->LastName) : 'An employee ') . " has filed a trip ticket that needs your approval. ",
                    'Type' => 'TRIP_TICKET',
                    'Notes' => $id,
                    'Status' => 'UNREAD',
                    'ForSignatory' => 'Yes',
                ]);

                /**
                 * =========================================================================
                 * SEND SMS
                 * =========================================================================
                 */
                if ($employee != null && $employee->ContactNumbers != null) {
                    if ($requisitioner != null) {
                        SMSNotifications::sendSMS(
                            $employee->ContactNumbers,
                            "HRS Trip Ticket Approval\n\nHello " . $employee->FirstName . ", " . $requisitioner->FirstName . " " . $requisitioner->LastName . " has filed a trip ticket that needs your approval.",
                            "HR-Trip Ticket",
                            $tripTickets->id
                        );
                    }
                }
            }
        }

        return response()->json('ok', 200);
    }

    public function getAllTripTickets(Request $request)
    {
        $employeeId = $request['EmployeeId'];
        // $type = $request['Type'];
        $search = $request['Search'];

        if ($search == null) {
            $data = DB::table("TripTicketPassengers")
                ->leftJoin('TripTickets', 'TripTicketPassengers.TripTicketId', '=', 'TripTickets.id')
                ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
                ->whereRaw("TripTicketPassengers.EmployeeId='" . $employeeId . "' AND TripTickets.Status NOT IN ('Trash')")
                ->select(
                    'TripTickets.*',
                    'Employees.FirstName AS DriverFirstName',
                    'Employees.MiddleName AS DriverMiddleName',
                    'Employees.LastName AS DriverLastName',
                    'Employees.Suffix AS DriverSuffix',
                    DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=TripTickets.id) AS Destinations")
                )
                ->orderByDesc('TripTicketPassengers.created_at')
                ->get();
        } else {
            $data = DB::table("TripTicketPassengers")
                ->leftJoin('TripTickets', 'TripTicketPassengers.TripTicketId', '=', 'TripTickets.id')
                ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
                ->whereRaw("TripTicketPassengers.EmployeeId='" . $employeeId . "' AND TripTickets.PurposeOfTravel LIKE '%" . $search . "%' AND TripTickets.Status NOT IN ('Trash')")
                ->select(
                    'TripTickets.*',
                    'Employees.FirstName AS DriverFirstName',
                    'Employees.MiddleName AS DriverMiddleName',
                    'Employees.LastName AS DriverLastName',
                    'Employees.Suffix AS DriverSuffix',
                    DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=TripTickets.id) AS Destinations")
                )
                ->orderByDesc('TripTicketPassengers.created_at')
                ->get();
        }

        return response()->json($data, 200);
    }

    public function deleteTripTicket(Request $request)
    {
        $id = $request['id'];

        $tripTicket = TripTickets::find($id);

        if ($tripTicket != null && $tripTicket->Status != 'APPROVED') {
            $tripTicket->Status = 'Trash';
            $tripTicket->save();

            Notifications::where('Notes', $id)->delete();

            return response()->json($tripTicket, 200);
        } else {
            return response()->json('Not allowed', 403);
        }
    }

    public function getTT(Request $request)
    {
        $id = $request['id'];

        // $data = DB::table("TripTicketPassengers")
        //     ->leftJoin('TripTickets', 'TripTicketPassengers.TripTicketId', '=', 'TripTickets.id')
        //     ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
        //     ->whereRaw("TripTickets.id='" . $id . "'")
        //     ->select(
        //         'TripTickets.*',
        //         'Employees.FirstName AS DriverFirstName',
        //         'Employees.MiddleName AS DriverMiddleName',
        //         'Employees.LastName AS DriverLastName',
        //         'Employees.Suffix AS DriverSuffix',
        //         DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=TripTickets.id) AS Destinations")
        //     )
        //     ->first();

        $data =  DB::table("TripTickets as a")
            ->leftJoin("TripTicketPassengers as b", "a.id", "=", "b.TripTicketId")
            ->leftJoin("Employees as c", "c.id", "=", "a.Driver")
            ->where("a.id", "=", $id)
            ->select(
                "a.*",
                "c.FirstName as DriverFirstName",
                "c.MiddleName as DriverMiddleName",
                "c.LastName as DriverLastName",
                "c.Suffix as DriverSuffix",
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=a.id) AS Destinations")
            )
            ->first();

        // \Log::info("getTT data ::::: ". collect($data));

        if ($data != null) {
            $data->Employee = Employees::find($data->EmployeeId);

            $data->Signatories = DB::table('TripTicketSignatories')
                ->leftJoin("users", "TripTicketSignatories.EmployeeId", "=", 'users.id')
                ->select(
                    "TripTicketSignatories.*",
                    "users.name"
                )
                ->where('TripTicketId', $data->id)
                ->orderBy("Rank")
                ->get();

            return response()->json($data, 200);
        } else {
            return response()->json('trip ticket not found', 404);
        }
    }

    public function approveTripTicket(Request $request)
    {
        $id = $request['id'];
        $userId = $request['UserId'];

        $tripTicket = TripTickets::where('id', $id)->first();

        if ($tripTicket != null) {
            $tripTicket->Status = 'APPROVED';
            $tripTicket->save();
        }

        TripTicketSignatories::where('TripTicketId', $id)
            ->update(['Status' => 'APPROVED']);

        /**
         * =========================================================================
         * SEND SMS
         * =========================================================================
         */
        $employee = Employees::find(Users::find($tripTicket->UserId)->employee_id);
        $approver = Users::find($userId);

        if ($approver != null) {
            // update notification
            Notifications::where('Notes', $id)
                ->where('UserId', $approver->id)
                ->update(['Status' => 'READ']);

            // send notification
            Notifications::create([
                'UserId' => $tripTicket->UserId,
                'Content' => ($approver->name) . " has approved your trip ticket.",
                'Type' => 'TRIP_TICKET_APPROVAL',
                'Notes' => $id,
                'Status' => 'UNREAD',
            ]);

            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS(
                    $employee->ContactNumbers,
                    "HRS Trip Ticket Approval\n\nHello " . $employee->FirstName . ", " . $approver->name . " has APPROVED your trip ticket with Ref. No. " . $id . ".",
                    "HR-Trip Ticket",
                    $id
                );
            }
        }

        return response()->json('ok', 200);
    }

    public function rejectTripTicket(Request $request)
    {
        $id = $request['id'];
        $notes = $request['Notes'];
        $userId = $request['UserId'];

        TripTickets::where('id', $id)
            ->update(['Status' => 'REJECTED']);

        TripTicketSignatories::where('TripTicketId', $id)
            ->update(['Status' => 'REJECTED', 'Notes' => $notes]);

        /**
         * =========================================================================
         * SEND SMS
         * =========================================================================
         */
        $tripTicket = TripTickets::where('id', $id)->first();
        $approver = Users::find($userId);
        if ($approver != null && $tripTicket != null) {
            // send notification
            Notifications::create([
                'UserId' => $tripTicket->UserId,
                'Content' => ($approver->name) . " has REJECTED your trip ticket.",
                'Type' => 'TRIP_TICKET_APPROVAL',
                'Notes' => $id,
                'Status' => 'UNREAD',
            ]);

            $employee = Employees::find(Users::find($tripTicket->UserId)->employee_id);
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS(
                    $employee->ContactNumbers,
                    "HR System - Trip Ticket Approval:\n\n" . $approver->name . " has DISAPPROVED your trip ticket with Ref. No. " . $id . ".",
                    "HR-Trip Ticket",
                    $id
                );
            }
        }

        return response()->json('ok', 200);
    }

    public function requestGRS(Request $request)
    {
        $id = $request['id'];

        TripTickets::where('id', $id)
            ->update(['RequestGRS' => 'Yes']);

        return response()->json('ok', 200);
    }

    public function getAllTodaysTickets()
    {
        $data =  DB::table("TripTickets as a")
            ->leftJoin("TripTicketPassengers as b", "a.id", "=", "b.TripTicketId")
            ->leftJoin("Employees as c", "c.id", "=", "a.Driver")
            ->whereRaw("a.DateOfTravel = cast(current_timestamp as date) and a.Status in ('Trash','REJECTED')")
            ->select(
                "a.*",
                "c.FirstName as DriverFirstName",
                "c.MiddleName as DriverMiddleName",
                "c.LastName as DriverLastName",
                "c.Suffix as DriverSuffix",
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=a.id) AS Destinations")
            )
            ->orderByDesc("a.created_at")
            ->get();
        return response()->json($data, 200);
    }
    public function getTripTickets(Request $req)
    {

        $search = $req->input("search");
        $status = $req->input("status");

        if ($search !== null && $status !== null) { // search tickets by travel date and status.
            $data =  DB::table("TripTickets as a")
            ->leftJoin("TripTicketPassengers as b", "a.id", "=", "b.TripTicketId")
            ->leftJoin("Employees as c", "c.id", "=", "a.Driver")
            ->leftJoin("Employees as d", "d.id", "=", "a.EmployeeId")
            ->where("a.Status","=",$status)
            ->where(function($query) use ($search) {
                $query->where("c.FirstName", "like", "%$search%")
                    ->orWhere("c.MiddleName", "like", "%$search%")
                    ->orWhere("c.LastName", "like", "%$search%")
                    ->orWhere("c.Suffix", "like", "%$search%")
                    ->orWhere("d.FirstName", "like", "%$search%")
                    ->orWhere("d.MiddleName", "like", "%$search%")
                    ->orWhere("d.LastName", "like", "%$search%")
                    ->orWhere("d.Suffix", "like", "%$search%")
                    ->orWhere("a.Vehicle", "like", "%$search%")
                    ->orWhereRaw("FORMAT(a.created_at, 'yyyy-MM-dd') LIKE ?", ["%$search%"])
                    ->orWhereRaw("FORMAT(a.created_at, 'MM/dd/yyyy') LIKE ?", ["%$search%"]);
            })
            ->select(
                DB::raw("distinct(a.id) as TripTicketId"),
                "a.*",
                "c.FirstName as DriverFirstName",
                "c.MiddleName as DriverMiddleName",
                "c.LastName as DriverLastName",
                "c.Suffix as DriverSuffix",
                "d.FirstName as EmployeeFirstName",
                "d.MiddleName as EmployeeMiddleName",
                "d.LastName as EmployeeLastName",
                "d.Suffix as EmployeeSuffix",
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=a.id) AS Destinations"),
            )
            ->orderByDesc("a.DateOfTravel")
            ->get();
        } else if ($search !== null && $status === null) { // search tickets by travel date.
            $data =  DB::table("TripTickets as a")
            ->leftJoin("TripTicketPassengers as b", "a.id", "=", "b.TripTicketId")
            ->leftJoin("Employees as c", "c.id", "=", "a.Driver")
            ->leftJoin("Employees as d", "d.id", "=", "a.EmployeeId")
            ->whereRaw("a.Status not in ('Trash','REJECTED')")
            ->where(function($query) use ($search) {
                $query->where("c.FirstName", "like", "%$search%")
                    ->orWhere("c.MiddleName", "like", "%$search%")
                    ->orWhere("c.LastName", "like", "%$search%")
                    ->orWhere("c.Suffix", "like", "%$search%")
                    ->orWhere("d.FirstName", "like", "%$search%")
                    ->orWhere("d.MiddleName", "like", "%$search%")
                    ->orWhere("d.LastName", "like", "%$search%")
                    ->orWhere("d.Suffix", "like", "%$search%")
                    ->orWhere("a.Vehicle", "like", "%$search%")
                    ->orWhereRaw("FORMAT(a.created_at, 'yyyy-MM-dd') LIKE ?", ["%$search%"]);
            })
            ->select(
                DB::raw("distinct(a.id) as TripTicketId"),
                "a.*",
                "c.FirstName as DriverFirstName",
                "c.MiddleName as DriverMiddleName",
                "c.LastName as DriverLastName",
                "c.Suffix as DriverSuffix",
                "d.FirstName as EmployeeFirstName",
                "d.MiddleName as EmployeeMiddleName",
                "d.LastName as EmployeeLastName",
                "d.Suffix as EmployeeSuffix",
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=a.id) AS Destinations")
            )
            ->orderByDesc("a.DateOfTravel")
            ->get();
        } else if ($search === null && $status !== null) { // search tickets by status.
            
            $data =  DB::table("TripTickets as a")
            ->leftJoin("TripTicketPassengers as b", "a.id", "=", "b.TripTicketId")
            ->leftJoin("Employees as c", "c.id", "=", "a.Driver")
            ->leftJoin("Employees as d", "d.id", "=", "a.EmployeeId")
            ->where("a.Status","=",$status)
            ->select(
                DB::raw("distinct(a.id) as TripTicketId"),
                "a.*",
                "c.FirstName as DriverFirstName",
                "c.MiddleName as DriverMiddleName",
                "c.LastName as DriverLastName",
                "c.Suffix as DriverSuffix",
                "d.FirstName as EmployeeFirstName",
                "d.MiddleName as EmployeeMiddleName",
                "d.LastName as EmployeeLastName",
                "d.Suffix as EmployeeSuffix",
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=a.id) AS Destinations")
            )
            ->orderByDesc("a.DateOfTravel")
            ->get();
        } else {
            $data =  DB::table("TripTickets as a")
            ->leftJoin("TripTicketPassengers as b", "a.id", "=", "b.TripTicketId")
            ->leftJoin("Employees as c", "c.id", "=", "a.Driver")
            ->leftJoin("Employees as d", "d.id", "=", "a.EmployeeId")
            ->whereRaw("a.Status not in ('Trash','REJECTED')")
            ->select(
                DB::raw("distinct(a.id) as TripTicketId"),
                "a.*",
                "c.FirstName as DriverFirstName",
                "c.MiddleName as DriverMiddleName",
                "c.LastName as DriverLastName",
                "c.Suffix as DriverSuffix",
                "d.FirstName as EmployeeFirstName",
                "d.MiddleName as EmployeeMiddleName",
                "d.LastName as EmployeeLastName",
                "d.Suffix as EmployeeSuffix",
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=a.id) AS Destinations")
            )
            ->orderByDesc("a.DateOfTravel")
            ->get();
        }

        $status = DB::table("TripTickets")
            ->whereRaw("Status is not null and status NOT IN ('Trash', 'REJECTED')")
            ->selectRaw("DISTINCT(Status)")
            ->get();
        
        return response()->json([
            "statusList" => $status,
            "data" => $data,
        ], 200);
    }

    public function getTripTicket(Request $req) {
        $id = $req->input("id");
        $data =  DB::table("TripTickets as a")
        ->leftJoin("TripTicketPassengers as b", "a.id", "=", "b.TripTicketId")
        ->leftJoin("Employees as c", "c.id", "=", "a.Driver")
        ->leftJoin("EmployeesDesignations as c1","c.id","=","c1.EmployeeId")
        ->leftJoin("Positions as c2","c2.id","=","c1.PositionId")
        ->leftJoin("Employees as d", "d.id", "=", "a.EmployeeId")
        ->leftJoin("EmployeesDesignations as d1","d.id","=","d1.EmployeeId")
        ->leftJoin("Positions as d2","d2.id","=","d1.PositionId")
        ->leftJoin("TripTicketSignatories as e", "a.id", "=", "e.TripTicketId")
        ->where("a.id","=",$id)
        ->select(
                "a.*",
                "c.FirstName as DriverFirstName",
                "c.MiddleName as DriverMiddleName",
                "c.LastName as DriverLastName",
                "c.Suffix as DriverSuffix",
                "c2.Position as DriverPosition",
                "d.FirstName as EmployeeFirstName",
                "d.MiddleName as EmployeeMiddleName",
                "d.LastName as EmployeeLastName",
                "d2.Position as EmployeePosition",
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=a.id) AS Destinations"),
        )
        ->orderByDesc("a.DateOfTravel")
        ->first();

        $destinations = DB::table("TripTicketDestinations")
            ->where("TripTicketId","=",$id)
            ->get();

        $signatories = DB::table("TripTicketSignatories as a")
            ->where("a.TripTicketId","=",$id)
            ->leftJoin("users as b","a.EmployeeId","=","b.id")
            ->leftJoin("Employees as c","b.employee_id","=","c.id")
            ->leftJoin("EmployeesDesignations as d","c.id","=","d.EmployeeId")
            ->leftJoin("Positions as e","e.id","=","d.PositionId")
            ->select(
                "a.*",
                "c.id as SignatoryId",
                "c.FirstName as SignatoryFirstName",
                "c.MiddleName as SignatoryMiddleName",
                "c.LastName as SignatoryLastName",
                "c.Suffix as SignatorySuffix",
                "e.Position as SignatoryPosition"
            )
            ->get();

        $passengers = DB::table("TripTicketPassengers as a")
            ->where("a.TripTicketId","=",$id)
            ->whereRaw("(a.EmployeeId != b.id OR a.EmployeeId != c.Driver)")
            ->leftJoin("Employees as b","a.EmployeeId","=","b.id")
            ->leftJoin("TripTickets as c","c.id","=","a.TripTicketId")
            ->select("a.*","b.FirstName","b.MiddleName","b.LastName","b.Suffix")
            ->get();

        if (!$data) {
            return response()->json(["message" => "Trip ticket not found."], 404);
        } else {
            return response()->json([
                "tripticket" => $data,
                "destinations" => $destinations,
                "signatories" => $signatories,
                "passengers" => $passengers
            ], 200);
        }
    }

    public function getTripLogs($id) {
        $data = DB::table("TripTicketLogs as a")
            ->where("a.TripTicketId","=",$id)
            ->leftJoin("Employees as b", "a.GuardId", "=", "b.id")
            ->select(
                "a.*",
                "b.FirstName",
                "b.MiddleName",
                "b.LastName",
                "b.Suffix",
            )
            ->orderBy("a.created_at")
            ->get();

        $dep = TripTicketLogs::where("TripTicketId","=",$id)->where("Status","=","DEPARTURAL")->first();
        $arr = TripTicketLogs::where("TripTicketId","=",$id)->where("Status","=","ARRIVAL")->first();
        
        return response()->json([
            "logs" => $data,
            "departural" => $dep,
            "arrival" => $arr,
        ], 200);
    }

    public function approveTripLog(Request $req) {
        
        $input = $req->validate(TripTicketLogs::$rules);

        $data = TripTicketLogs::create($input);

        return response()->json($data, 201);
        
    }
}