<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripTicketsRequest;
use App\Http\Requests\UpdateTripTicketsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TripTicketsRepository;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Towns;
use App\Models\Vehicles;
use App\Models\TripTickets;
use App\Models\TripTicketDestinations;
use App\Models\TripTicketSignatories;
use App\Models\IDGenerator;
use App\Models\AttendanceData;
use App\Models\SMSNotifications;
use App\Models\Users;
use App\Models\TripTicketGRS;
use App\Models\TripTicketPassengers;
use App\Models\Notifications;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class TripTicketsController extends AppBaseController
{
    /** @var TripTicketsRepository $tripTicketsRepository*/
    private $tripTicketsRepository;

    public function __construct(TripTicketsRepository $tripTicketsRepo)
    {
        $this->middleware('auth');
        $this->tripTicketsRepository = $tripTicketsRepo;
    }

    /**
     * Display a listing of the TripTickets.
     */
    public function index()
    {
        // $tripTickets = $this->tripTicketsRepository->paginate(10);
        $tripTickets = TripTickets::where('EmployeeId', Auth::user()->employee_id)
            ->where("Status","!=","Trash")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('trip_tickets.index')
            ->with('tripTickets', $tripTickets);
    }

    /**
     * Show the form for creating a new TripTickets.
     */
    public function create()
    {
        $drivers = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*')
            ->whereRaw("Positions.Level='Driver' OR Employees.AuthorizedToDrive='Yes'")
            ->orderBy('Employees.LastName')
            ->get();

        $vehicles = Vehicles::orderBy('VehicleName')->get();

        return view('trip_tickets.create', [
            'employees' => Employees::orderBy('LastName')->get(),
            'drivers' => $drivers,
            'towns' => Towns::orderBy('Town')->get(),
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Store a newly created TripTickets in storage.
     */
    public function store(CreateTripTicketsRequest $request)
    {
        $input = $request->all();

        $tripTickets = $this->tripTicketsRepository->create($input);

        // SET MANUALLY TYPED DESTINATIONS
        if ($request['DestinationTyped'] != null) {
            $destinations = explode(";", $request['DestinationTyped']);
            
            for($i=0; $i<count($destinations); $i++) {
                if ($destinations[$i] != null) {
                    $destination = new TripTicketDestinations;
                    $destination->id = IDGenerator::generateIDandRandString();
                    $destination->TripTicketId = $input['id'];
                    $destination->DestinationAddress = trim($destinations[$i]);
                    $destination->save();
                }
            }
        }

        // SET SIGNATORY
        if ($request['Signatory']) {
            $signatory = new TripTicketSignatories;
            $signatory->id = IDGenerator::generateIDandRandString();
            $signatory->TripTicketId = $input['id'];
            $signatory->EmployeeId = $request['Signatory']; // user id
            $signatory->Rank = 1;
            $signatory->save();

            $user = Users::find($request['Signatory']);
            $employee = Employees::find($user->employee_id);
            $requisitioner = Employees::find($tripTickets->EmployeeId);
            if ($employee != null) {
                Notifications::create([
                    'UserId' => $user->id,
                    'Content' => ($requisitioner != null ? ($requisitioner->FirstName . " " . $requisitioner->LastName) : 'An employee ') . " has filed a trip ticket that needs your approval. ",
                    'Type' => 'TRIP_TICKET',
                    'Notes' => $input['id'],
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
                        SMSNotifications::sendSMS($employee->ContactNumbers, 
                            "HRS Trip Ticket Approval\n\nHello " . $employee->FirstName . ", " . $requisitioner->FirstName . " " . $requisitioner->LastName . " has filed a trip ticket that needs your approval.",
                            "HR-Trip Ticket",
                            $tripTickets->id
                        );
                    }
                }
            }
        }

        Flash::success('Trip Tickets saved successfully.');

        if ($request['Status'] == 'APPROVED') {
            return redirect(route('tripTickets.manual-entry'));
        } else {
            return redirect(route('tripTickets.my-trip-tickets', [$input['UserId']]));
        }
    }

    /**
     * Display the specified TripTickets.
     */
    public function show($id)
    {
        $tripTickets = $this->tripTicketsRepository->find($id);

        $drivers = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*')
            ->whereRaw("Positions.Level='Driver' OR Employees.AuthorizedToDrive='Yes'")
            ->orderBy('Employees.LastName')
            ->get();

        $vehicles = Vehicles::orderBy('VehicleName')->get();

        $passengers = DB::table('TripTicketPassengers')
            ->leftJoin('Employees', 'TripTicketPassengers.EmployeeId', '=', 'Employees.id')
            ->whereRaw("TripTicketPassengers.TripTicketId='" . $id . "'")
            ->select('Employees.*', 'TripTicketPassengers.id AS PassengerId')
            ->orderBy('Employees.FirstName')
            ->get();

        $destinations = DB::table('TripTicketDestinations')
            ->whereRaw("TripTicketId='" . $id . "'")
            ->orderBy('DestinationAddress')
            ->get();

        $signatory = DB::table('TripTicketSignatories')
            ->leftJoin('users', 'TripTicketSignatories.EmployeeId', '=', 'users.id')
            ->whereRaw("TripTicketSignatories.TripTicketId='" . $id . "'")
            ->select('users.*', 
                'TripTicketSignatories.id AS SignatoryId',
                'TripTicketSignatories.Status'
            )
            ->orderBy('users.name')
            ->first();

        if (empty($tripTickets)) {
            Flash::error('Trip Tickets not found');

            return redirect(route('tripTickets.index'));
        }

        return view('trip_tickets.show', [
            'tripTickets' => $tripTickets,
            'employees' => Employees::orderBy('LastName')->get(),
            'drivers' => $drivers,
            'towns' => Towns::orderBy('Town')->get(),
            'vehicles' => $vehicles,
            'passengers' => $passengers,
            'destinations' => $destinations,
            'signatory' => $signatory,
        ]);
    }

    /**
     * Show the form for editing the specified TripTickets.
     */
    public function edit($id)
    {
        $tripTickets = $this->tripTicketsRepository->find($id);

        $drivers = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*')
            ->whereRaw("Positions.Level='Driver' OR Employees.AuthorizedToDrive='Yes'")
            ->orderBy('Employees.LastName')
            ->get();

        $vehicles = Vehicles::orderBy('VehicleName')->get();

        $passengers = DB::table('TripTicketPassengers')
            ->leftJoin('Employees', 'TripTicketPassengers.EmployeeId', '=', 'Employees.id')
            ->whereRaw("TripTicketPassengers.TripTicketId='" . $id . "'")
            ->select('Employees.*', 'TripTicketPassengers.id AS PassengerId')
            ->orderBy('Employees.FirstName')
            ->get();

        $destinations = DB::table('TripTicketDestinations')
            ->whereRaw("TripTicketId='" . $id . "'")
            ->orderBy('DestinationAddress')
            ->get();

        $signatory = DB::table('TripTicketSignatories')
            ->leftJoin('users', 'TripTicketSignatories.EmployeeId', '=', 'users.id')
            ->whereRaw("TripTicketSignatories.TripTicketId='" . $id . "'")
            ->select('users.*', 
                'TripTicketSignatories.id AS SignatoryId',
                'TripTicketSignatories.Status'
            )
            ->orderBy('users.name')
            ->first();

        if (empty($tripTickets)) {
            Flash::error('Trip Tickets not found');

            return redirect(route('tripTickets.index'));
        }

        return view('trip_tickets.edit', [
            'tripTickets' => $tripTickets,
            'employees' => Employees::orderBy('LastName')->get(),
            'drivers' => $drivers,
            'towns' => Towns::orderBy('Town')->get(),
            'vehicles' => $vehicles,
            'passengers' => $passengers,
            'destinations' => $destinations,
            'signatory' => $signatory,
        ]);
    }

    /**
     * Update the specified TripTickets in storage.
     */
    public function update($id, UpdateTripTicketsRequest $request)
    {
        $tripTickets = $this->tripTicketsRepository->find($id);

        if (empty($tripTickets)) {
            Flash::error('Trip Tickets not found');

            return redirect(route('tripTickets.index'));
        }

        // SET MANUALLY TYPED DESTINATIONS
        //DELETE ALL MANUALLY TYPED DESTINATIONS
        TripTicketDestinations::whereNull('TownId')
            ->where('TripTicketId', $id)
            ->delete();

        if ($request['DestinationTyped'] != null) {
            $destinations = explode(";", $request['DestinationTyped']);
            
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

        // SET SIGNATORY
        // DELETE EXISTING SIGNATORY
        TripTicketSignatories::where('TripTicketId', $id)
            ->delete();
            
        if ($request['Signatory']) {
            $signatory = new TripTicketSignatories;
            $signatory->id = IDGenerator::generateIDandRandString();
            $signatory->TripTicketId = $id;
            $signatory->EmployeeId = $request['Signatory']; // user id
            $signatory->Rank = 1;
            $signatory->save();
        }

        $tripTickets = $this->tripTicketsRepository->update($request->all(), $id);

        Flash::success('Trip Tickets updated successfully.');

        return redirect(route('tripTickets.my-trip-tickets', [$tripTickets->UserId]));
    }

    /**
     * Remove the specified TripTickets from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        // if (Permission::hasDirectPermission(['god permission', 'delete trip ticket'])) {
            $tripTickets = $this->tripTicketsRepository->find($id);

            if (empty($tripTickets)) {
                Flash::error('Trip Tickets not found');

                return redirect(route('tripTickets.index'));
            }

            $tripTickets->Status = 'Trash';
            $tripTickets->save();

            return response()->json($tripTickets, 200);
        // } else {
        //     return abort(403, 'You are not authorized to access this module.');
        // }
    }

    public function getSignatories(Request $request) {
        $employeeId = $request['EmployeeId'];

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.LastName', 'Positions.Position', 'Positions.Department', 'Positions.ParentPositionId', 'Positions.Level')
            ->whereRaw("Employees.id='" . $employeeId . "'")
            ->orderByDesc('EmployeesDesignations.DateStarted')
            ->first();

        $otherSignatories = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('users.id', 'Employees.id AS EmployeeId', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
            ->whereRaw("Positions.Level IN ('Chief', 'Manager', 'General Manager')")
            ->get();

        $signatories = [];
        if ($employee != null) {
            $signatories = Employees::getSupers($employeeId, ['Chief', 'Manager', 'General Manager']);

            return response()->json(['Signatories' => $signatories, 'OtherSignatories' => $otherSignatories], 200);           
        } else {
            return response()->json('Employee not found!', 404);
        }
    }

    public function myTripTickets($userId) {
        $tripTickets = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->whereRaw("TripTickets.UserId='" . $userId . "' AND TripTickets.Status NOT IN ('Trash')")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->orderByDesc('DatetimeFiled')
            ->paginate(25);

        return view('/trip_tickets/my_trip_tickets', [
            'tripTickets' => $tripTickets,
        ]);
    }

    public function getTripTicketAjax(Request $request) {
        $id = $request['id'];

        $tripTicket = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->whereRaw("TripTickets.id='" . $id . "'")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->first();

        if ($tripTicket != null) {
            $passengers = DB::table('TripTicketPassengers')
                ->leftJoin('Employees', 'TripTicketPassengers.EmployeeId', '=', 'Employees.id')
                ->whereRaw("TripTicketPassengers.TripTicketId='" . $id . "'")
                ->select('Employees.*', 'TripTicketPassengers.id AS PassengerId')
                ->orderBy('Employees.FirstName')
                ->get();

            $destinations = DB::table('TripTicketDestinations')
                ->whereRaw("TripTicketId='" . $id . "'")
                ->orderBy('DestinationAddress')
                ->get();

            $signatory = DB::table('TripTicketSignatories')
                ->leftJoin('users', 'TripTicketSignatories.EmployeeId', '=', 'users.id')
                ->whereRaw("TripTicketSignatories.TripTicketId='" . $id . "'")
                ->select('users.*', 
                    'TripTicketSignatories.id AS SignatoryId',
                    'TripTicketSignatories.Status'
                )
                ->orderBy('users.name')
                ->first();

            $grs = TripTicketGRS::where('TripTicketId', $id)->first();
            
            $tripTicket->Passengers = $passengers;
            $tripTicket->Destinations = $destinations;
            $tripTicket->Signatory = $signatory;
            $tripTicket->GRS = $grs;
        } else {
            $tripTicket->Passengers = [];
            $tripTicket->Destinations = [];
            $tripTicket->Signatory = null;
            $tripTicket->GRS = [];
        }

        return response()->json($tripTicket, 200);
    }

    public function myApprovals(Request $request) {
        $tripTickets = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->leftJoin('TripTicketSignatories', 'TripTickets.id', '=', 'TripTicketSignatories.TripTicketId')
            ->whereRaw("TripTicketSignatories.EmployeeId='" . Auth::id() . "' AND TripTickets.Status NOT IN ('Trash', 'APPROVED', 'REJECTED', 'DEPARTED', 'ARRIVED')")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->orderByDesc('DatetimeFiled')
            ->paginate(25);

        return view('/trip_tickets/my_approvals', [
            'tripTickets' => $tripTickets,
        ]);
    }

    public function approveTripTicket(Request $request) {
        $id = $request['id'];

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
        $approver = Auth::user();

        if ($approver != null) {
            // send notification
            Notifications::create([
                'UserId' => $tripTicket->UserId,
                'Content' => ($approver->name) . " has approved your trip ticket.",
                'Type' => 'TRIP_TICKET_APPROVAL',
                'Notes' => $id,
                'Status' => 'UNREAD',
            ]);

            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HRS Trip Ticket Approval\n\nHello " . $employee->FirstName . ", " . Auth::user()->name . " has APPROVED your trip ticket with Ref. No. " . $id . ".",
                    "HR-Trip Ticket",
                    $id
                );
            }
        }

        return response()->json('ok', 200);
    }

    public function rejectTripTicket(Request $request) {
        $id = $request['id'];

        TripTickets::where('id', $id)
            ->update(['Status' => 'REJECTED']);

        TripTicketSignatories::where('TripTicketId', $id)
            ->update(['Status' => 'REJECTED']);

        /**
         * =========================================================================
         * SEND SMS
         * =========================================================================
         */
        $tripTicket = TripTickets::where('id', $id)->first();
        if ($tripTicket != null) {
            $employee = Employees::find(Users::find($tripTicket->UserId)->employee_id);
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HR System - Trip Ticket Approval:\n\n" . Auth::user()->name . " has DISAPPROVED your trip ticket with Ref. No. " . $id . ".",
                    "HR-Trip Ticket",
                    $id
                );
            }
        }

        return response()->json('ok', 200);
    }

    public function requestGRS(Request $request) {
        $id = $request['id'];

        TripTickets::where('id', $id)
            ->update(['RequestGRS' => 'Yes']);

        return response()->json('ok', 200);
    }

    public function logVehicleTrips(Request $request) {
        $tripTickets = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->whereRaw("TripTickets.Status IN ('APPROVED') AND TripTickets.DateOfTravel='" . date('Y-m-d') . "'")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->orderBy('TripTickets.created_at')
            ->get();            

        return view('/trip_tickets/log_vehicle_trips', [
            'tripTickets' => $tripTickets,
            'employees' => Employees::orderBy('LastName')->get(),
        ]);
    }

    public function logDeparture(Request $request) {
        $id = $request['id'];

        $tripTicket = TripTickets::where('id', $id)->first();

        if ($tripTicket != null) {
            $tripTicket->Status = 'DEPARTED';
            $tripTicket->DatetimeDeparted = date('Y-m-d H:i:s');
            $tripTicket->GuardLoggedDeparture = Auth::user()->name;
            $tripTicket->save();
        }

        // INSERT TO ATTENDANCES
        $passengers = TripTicketPassengers::where('TripTicketId', $id)->get();
        foreach ($passengers as $item) {
            $employee = Employees::find($item->EmployeeId);

            if ($employee != null) {
                $user = Users::where('employee_id', $employee->id)->first();
                // INSERT START MORNING IN
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($tripTicket->DateOfTravel)) . ' 07:31:00';
                $attendance->AbsentPermission = 'TRIP TICKET';
                $attendance->save();

                // INSERT START MORNING OUT
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($tripTicket->DateOfTravel)) . ' 12:05:00';
                $attendance->AbsentPermission = 'TRIP TICKET';
                $attendance->save();

                // INSERT START AFTERNOON IN
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($tripTicket->DateOfTravel)) . ' 12:45:00';
                $attendance->AbsentPermission = 'TRIP TICKET';
                $attendance->save();

                // INSERT START AFTERNOON OUT
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = date('Y-m-d', strtotime($tripTicket->DateOfTravel)) . ' 17:05:00';
                $attendance->AbsentPermission = 'TRIP TICKET';
                $attendance->save();
            }
        }

        return response()->json('ok', 200);
    }

    public function logVehicleArrivals(Request $request) {
        $tripTickets = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->whereRaw("TripTickets.Status IN ('DEPARTED') AND TripTickets.DateOfTravel>'" . date('Y-m-d', strtotime('today -4 days')) . "'")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->orderBy('TripTickets.created_at')
            ->get();            

        return view('/trip_tickets/log_vehicle_arrivals', [
            'tripTickets' => $tripTickets,
        ]);
    }

    public function logArrival(Request $request) {
        $id = $request['id'];

        $tripTicket = TripTickets::where('id', $id)->first();

        if ($tripTicket != null) {
            $tripTicket->Status = 'ARRIVED';
            $tripTicket->DatetimeArrived = date('Y-m-d H:i:s');
            $tripTicket->GuardLoggedArrival = Auth::user()->name;
            $tripTicket->save();
        }

        return response()->json('ok', 200);
    }

    public function manualEntry(Request $request) {
        $employees = Employees::orderBy('LastName')->get();

        $drivers = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*')
            ->whereRaw("Positions.Level='Driver' OR Employees.AuthorizedToDrive='Yes'")
            ->orderBy('Employees.LastName')
            ->get();

        $vehicles = Vehicles::orderBy('VehicleName')->get();

        return view('trip_tickets.manual_entry', [
            'employees' => $employees,
            'drivers' => $drivers,
            'towns' => Towns::orderBy('Town')->get(),
            'vehicles' => $vehicles,
        ]);
    }

    public function getTripTicketsByEmployee(Request $request) {
        $employeeId = $request['EmployeeId'];
        $startDate = $request['StartDate'];

        $data = DB::table("TripTicketPassengers")
            ->leftJoin('TripTickets', 'TripTicketPassengers.TripTicketId', '=', 'TripTickets.id')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->whereRaw("TripTicketPassengers.EmployeeId='" . $employeeId . "' AND TripTickets.DateOfTravel <= '" . $startDate . "' AND TripTickets.Status NOT IN ('Trash')")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                DB::raw("(SELECT STRING_AGG(DestinationAddress, ',') FROM TripTicketDestinations WHERE TripTicketDestinations.TripTicketId=TripTickets.id) AS Destinations")
            )
            ->orderByDesc('TripTicketPassengers.created_at')
            ->paginate(6);

        return response()->json($data, 200);
    }
}
