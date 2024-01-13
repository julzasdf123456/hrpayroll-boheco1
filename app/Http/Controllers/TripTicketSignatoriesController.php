<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripTicketSignatoriesRequest;
use App\Http\Requests\UpdateTripTicketSignatoriesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TripTicketSignatoriesRepository;
use Illuminate\Http\Request;
use Flash;

class TripTicketSignatoriesController extends AppBaseController
{
    /** @var TripTicketSignatoriesRepository $tripTicketSignatoriesRepository*/
    private $tripTicketSignatoriesRepository;

    public function __construct(TripTicketSignatoriesRepository $tripTicketSignatoriesRepo)
    {
        $this->middleware('auth');
        $this->tripTicketSignatoriesRepository = $tripTicketSignatoriesRepo;
    }

    /**
     * Display a listing of the TripTicketSignatories.
     */
    public function index(Request $request)
    {
        $tripTicketSignatories = $this->tripTicketSignatoriesRepository->paginate(10);

        return view('trip_ticket_signatories.index')
            ->with('tripTicketSignatories', $tripTicketSignatories);
    }

    /**
     * Show the form for creating a new TripTicketSignatories.
     */
    public function create()
    {
        return view('trip_ticket_signatories.create');
    }

    /**
     * Store a newly created TripTicketSignatories in storage.
     */
    public function store(CreateTripTicketSignatoriesRequest $request)
    {
        $input = $request->all();

        $tripTicketSignatories = $this->tripTicketSignatoriesRepository->create($input);

        Flash::success('Trip Ticket Signatories saved successfully.');

        return redirect(route('tripTicketSignatories.index'));
    }

    /**
     * Display the specified TripTicketSignatories.
     */
    public function show($id)
    {
        $tripTicketSignatories = $this->tripTicketSignatoriesRepository->find($id);

        if (empty($tripTicketSignatories)) {
            Flash::error('Trip Ticket Signatories not found');

            return redirect(route('tripTicketSignatories.index'));
        }

        return view('trip_ticket_signatories.show')->with('tripTicketSignatories', $tripTicketSignatories);
    }

    /**
     * Show the form for editing the specified TripTicketSignatories.
     */
    public function edit($id)
    {
        $tripTicketSignatories = $this->tripTicketSignatoriesRepository->find($id);

        if (empty($tripTicketSignatories)) {
            Flash::error('Trip Ticket Signatories not found');

            return redirect(route('tripTicketSignatories.index'));
        }

        return view('trip_ticket_signatories.edit')->with('tripTicketSignatories', $tripTicketSignatories);
    }

    /**
     * Update the specified TripTicketSignatories in storage.
     */
    public function update($id, UpdateTripTicketSignatoriesRequest $request)
    {
        $tripTicketSignatories = $this->tripTicketSignatoriesRepository->find($id);

        if (empty($tripTicketSignatories)) {
            Flash::error('Trip Ticket Signatories not found');

            return redirect(route('tripTicketSignatories.index'));
        }

        $tripTicketSignatories = $this->tripTicketSignatoriesRepository->update($request->all(), $id);

        Flash::success('Trip Ticket Signatories updated successfully.');

        return redirect(route('tripTicketSignatories.index'));
    }

    /**
     * Remove the specified TripTicketSignatories from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tripTicketSignatories = $this->tripTicketSignatoriesRepository->find($id);

        if (empty($tripTicketSignatories)) {
            Flash::error('Trip Ticket Signatories not found');

            return redirect(route('tripTicketSignatories.index'));
        }

        $this->tripTicketSignatoriesRepository->delete($id);

        Flash::success('Trip Ticket Signatories deleted successfully.');

        return redirect(route('tripTicketSignatories.index'));
    }
}
