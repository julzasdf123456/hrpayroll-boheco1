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
use Illuminate\Support\Facades\DB;
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
    public function index(Request $request)
    {
        $tripTickets = $this->tripTicketsRepository->paginate(10);

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
        }

        Flash::success('Trip Tickets saved successfully.');

        return redirect(route('tripTickets.show', [$input['id']]));
    }

    /**
     * Display the specified TripTickets.
     */
    public function show($id)
    {
        $tripTickets = $this->tripTicketsRepository->find($id);

        if (empty($tripTickets)) {
            Flash::error('Trip Tickets not found');

            return redirect(route('tripTickets.index'));
        }

        return view('trip_tickets.show')->with('tripTickets', $tripTickets);
    }

    /**
     * Show the form for editing the specified TripTickets.
     */
    public function edit($id)
    {
        $tripTickets = $this->tripTicketsRepository->find($id);

        if (empty($tripTickets)) {
            Flash::error('Trip Tickets not found');

            return redirect(route('tripTickets.index'));
        }

        return view('trip_tickets.edit')->with('tripTickets', $tripTickets);
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

        $tripTickets = $this->tripTicketsRepository->update($request->all(), $id);

        Flash::success('Trip Tickets updated successfully.');

        return redirect(route('tripTickets.index'));
    }

    /**
     * Remove the specified TripTickets from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tripTickets = $this->tripTicketsRepository->find($id);

        if (empty($tripTickets)) {
            Flash::error('Trip Tickets not found');

            return redirect(route('tripTickets.index'));
        }

        $this->tripTicketsRepository->delete($id);

        Flash::success('Trip Tickets deleted successfully.');

        return redirect(route('tripTickets.index'));
    }

    public function getSignatories(Request $request) {
        $employeeId = $request['EmployeeId'];

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.LastName', 'Positions.Position', 'Positions.Department', 'Positions.ParentPositionId')
            ->whereRaw("Employees.id='" . $employeeId . "'")
            ->first();

        if ($employee != null) {
            if ($employee->ParentPositionId != null) {
                // LOOP SIGNATORIES AND FETCH UPPER LEVEL POSITIONS
                $signatories = [];
                $parentPosId = $employee->ParentPositionId;
                $dept = $employee->Department;
                $sign = true;
                $i = 0;
                while ($sign) {
                    $signatoryParents = DB::table('users')
                        ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
                        ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
                        ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                        ->select('users.id', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
                        ->whereRaw("Positions.Department='" . $dept . "' AND Positions.id='" . $parentPosId . "'")
                        ->first();

                    if ($signatoryParents->id != null) {
                        array_push($signatories, [
                            'id' => $signatoryParents->id,
                            'FirstName' => $signatoryParents->FirstName,
                            'LastName' => $signatoryParents->LastName,
                            'MiddleName' => $signatoryParents->MiddleName,
                            'Suffix' => $signatoryParents->Suffix,
                            'Position' => $signatoryParents->Position,
                            'Level' => $signatoryParents->Level,
                        ]);
                    }

                    if ($signatoryParents->ParentPositionId != null) {
                        $parentPosId = $signatoryParents->ParentPositionId;
                        $sign = true;
                        $i++;
                    } else {
                        $sign = false;
                        break;
                    }
                }

                return response()->json($signatories, 200);
            } else {
                return response()->json('This employee has no immediate supervisor, there cannot be assigned a signatory. Please assign supervisor first, or contact IT or HR for more info', 403);
            }            
        } else {
            return response()->json('Employee not found!', 404);
        }
    }
}
