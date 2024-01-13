<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripTicketPassengersRequest;
use App\Http\Requests\UpdateTripTicketPassengersRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TripTicketPassengersRepository;
use Illuminate\Http\Request;
use App\Models\TripTicketPassengers;
use App\Models\IDGenerator;
use Flash;

class TripTicketPassengersController extends AppBaseController
{
    /** @var TripTicketPassengersRepository $tripTicketPassengersRepository*/
    private $tripTicketPassengersRepository;

    public function __construct(TripTicketPassengersRepository $tripTicketPassengersRepo)
    {
        $this->middleware('auth');
        $this->tripTicketPassengersRepository = $tripTicketPassengersRepo;
    }

    /**
     * Display a listing of the TripTicketPassengers.
     */
    public function index(Request $request)
    {
        $tripTicketPassengers = $this->tripTicketPassengersRepository->paginate(10);

        return view('trip_ticket_passengers.index')
            ->with('tripTicketPassengers', $tripTicketPassengers);
    }

    /**
     * Show the form for creating a new TripTicketPassengers.
     */
    public function create()
    {
        return view('trip_ticket_passengers.create');
    }

    /**
     * Store a newly created TripTicketPassengers in storage.
     */
    public function store(CreateTripTicketPassengersRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();

        $tripTicketPassengers = $this->tripTicketPassengersRepository->create($input);

        // Flash::success('Trip Ticket Passengers saved successfully.');

        // return redirect(route('tripTicketPassengers.index'));
        return response()->json('ok', 200);
    }

    /**
     * Display the specified TripTicketPassengers.
     */
    public function show($id)
    {
        $tripTicketPassengers = $this->tripTicketPassengersRepository->find($id);

        if (empty($tripTicketPassengers)) {
            Flash::error('Trip Ticket Passengers not found');

            return redirect(route('tripTicketPassengers.index'));
        }

        return view('trip_ticket_passengers.show')->with('tripTicketPassengers', $tripTicketPassengers);
    }

    /**
     * Show the form for editing the specified TripTicketPassengers.
     */
    public function edit($id)
    {
        $tripTicketPassengers = $this->tripTicketPassengersRepository->find($id);

        if (empty($tripTicketPassengers)) {
            Flash::error('Trip Ticket Passengers not found');

            return redirect(route('tripTicketPassengers.index'));
        }

        return view('trip_ticket_passengers.edit')->with('tripTicketPassengers', $tripTicketPassengers);
    }

    /**
     * Update the specified TripTicketPassengers in storage.
     */
    public function update($id, UpdateTripTicketPassengersRequest $request)
    {
        $tripTicketPassengers = $this->tripTicketPassengersRepository->find($id);

        if (empty($tripTicketPassengers)) {
            Flash::error('Trip Ticket Passengers not found');

            return redirect(route('tripTicketPassengers.index'));
        }

        $tripTicketPassengers = $this->tripTicketPassengersRepository->update($request->all(), $id);

        Flash::success('Trip Ticket Passengers updated successfully.');

        return redirect(route('tripTicketPassengers.index'));
    }

    /**
     * Remove the specified TripTicketPassengers from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tripTicketPassengers = $this->tripTicketPassengersRepository->find($id);

        if (empty($tripTicketPassengers)) {
            Flash::error('Trip Ticket Passengers not found');

            return redirect(route('tripTicketPassengers.index'));
        }

        $this->tripTicketPassengersRepository->delete($id);

        Flash::success('Trip Ticket Passengers deleted successfully.');

        return redirect(route('tripTicketPassengers.index'));
    }

    public function removePassengerAjax(Request $request) {
        $employeeId = $request['EmployeeId'];
        $tripTicketId = $request['TripTicketId'];

        TripTicketPassengers::where('TripTicketId', $tripTicketId)
            ->where('EmployeeId', $employeeId)
            ->delete();

        return response()->json('ok', 200);
    }
}
