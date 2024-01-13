<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripTicketDestinationsRequest;
use App\Http\Requests\UpdateTripTicketDestinationsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TripTicketDestinationsRepository;
use Illuminate\Http\Request;
use Flash;
use App\Models\IDGenerator;
use App\Models\TripTicketDestinations;

class TripTicketDestinationsController extends AppBaseController
{
    /** @var TripTicketDestinationsRepository $tripTicketDestinationsRepository*/
    private $tripTicketDestinationsRepository;

    public function __construct(TripTicketDestinationsRepository $tripTicketDestinationsRepo)
    {
        $this->middleware('auth');
        $this->tripTicketDestinationsRepository = $tripTicketDestinationsRepo;
    }

    /**
     * Display a listing of the TripTicketDestinations.
     */
    public function index(Request $request)
    {
        $tripTicketDestinations = $this->tripTicketDestinationsRepository->paginate(10);

        return view('trip_ticket_destinations.index')
            ->with('tripTicketDestinations', $tripTicketDestinations);
    }

    /**
     * Show the form for creating a new TripTicketDestinations.
     */
    public function create()
    {
        return view('trip_ticket_destinations.create');
    }

    /**
     * Store a newly created TripTicketDestinations in storage.
     */
    public function store(CreateTripTicketDestinationsRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();

        $tripTicketDestinations = $this->tripTicketDestinationsRepository->create($input);

        // Flash::success('Trip Ticket Destinations saved successfully.');

        // return redirect(route('tripTicketDestinations.index'));
        return response()->json($tripTicketDestinations, 200);
    }

    /**
     * Display the specified TripTicketDestinations.
     */
    public function show($id)
    {
        $tripTicketDestinations = $this->tripTicketDestinationsRepository->find($id);

        if (empty($tripTicketDestinations)) {
            Flash::error('Trip Ticket Destinations not found');

            return redirect(route('tripTicketDestinations.index'));
        }

        return view('trip_ticket_destinations.show')->with('tripTicketDestinations', $tripTicketDestinations);
    }

    /**
     * Show the form for editing the specified TripTicketDestinations.
     */
    public function edit($id)
    {
        $tripTicketDestinations = $this->tripTicketDestinationsRepository->find($id);

        if (empty($tripTicketDestinations)) {
            Flash::error('Trip Ticket Destinations not found');

            return redirect(route('tripTicketDestinations.index'));
        }

        return view('trip_ticket_destinations.edit')->with('tripTicketDestinations', $tripTicketDestinations);
    }

    /**
     * Update the specified TripTicketDestinations in storage.
     */
    public function update($id, UpdateTripTicketDestinationsRequest $request)
    {
        $tripTicketDestinations = $this->tripTicketDestinationsRepository->find($id);

        if (empty($tripTicketDestinations)) {
            Flash::error('Trip Ticket Destinations not found');

            return redirect(route('tripTicketDestinations.index'));
        }

        $tripTicketDestinations = $this->tripTicketDestinationsRepository->update($request->all(), $id);

        Flash::success('Trip Ticket Destinations updated successfully.');

        return redirect(route('tripTicketDestinations.index'));
    }

    /**
     * Remove the specified TripTicketDestinations from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tripTicketDestinations = $this->tripTicketDestinationsRepository->find($id);

        if (empty($tripTicketDestinations)) {
            Flash::error('Trip Ticket Destinations not found');

            return redirect(route('tripTicketDestinations.index'));
        }

        $this->tripTicketDestinationsRepository->delete($id);

        Flash::success('Trip Ticket Destinations deleted successfully.');

        return redirect(route('tripTicketDestinations.index'));
    }

    public function removeDestination(Request $request) {
        $tripTicketId = $request['TripTicketId'];
        $destinationAddress = $request['DestinationAddress'];

        TripTicketDestinations::where('TripTicketId', $tripTicketId)
            ->where('DestinationAddress', $destinationAddress)
            ->delete();

        return response()->json('ok', 200);
    }
}
