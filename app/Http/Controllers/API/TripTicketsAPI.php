<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
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

class TripTicketsAPI extends Controller {
    public function getTripTicketDependencies(Request $request) {
        $id = $request['id'];

        $vehicles = Vehicles::orderBy('VehicleName')->get();
        $drivers = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*')
            ->whereRaw("Positions.Level='Driver' OR Employees.AuthorizedToDrive='Yes'")
            ->orderBy('Employees.LastName')
            ->get();
        $employees = Employees::orderBy('LastName')->get();

        $signatories = Employees::getSupers($id, ['Chief', 'Manager', 'General Manager']);

        $otherSignatories = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
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
            ], 200
        );
    }

    public function postTripTicket(Request $request) {
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
            
            for($i=0; $i<count($destinations); $i++) {
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
        foreach($passengers as $item) {
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
                /**
                 * =========================================================================
                 * SEND SMS
                 * =========================================================================
                 */
                if ($employee != null && $employee->ContactNumbers != null) {
                    if ($requisitioner != null) {
                        SMSNotifications::sendSMS($employee->ContactNumbers, 
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
}