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
}
