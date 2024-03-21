<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTravelOrderSignatoriesRequest;
use App\Http\Requests\UpdateTravelOrderSignatoriesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TravelOrderSignatoriesRepository;
use Illuminate\Http\Request;
use Flash;

class TravelOrderSignatoriesController extends AppBaseController
{
    /** @var TravelOrderSignatoriesRepository $travelOrderSignatoriesRepository*/
    private $travelOrderSignatoriesRepository;

    public function __construct(TravelOrderSignatoriesRepository $travelOrderSignatoriesRepo)
    {
        $this->middleware('auth');
        $this->travelOrderSignatoriesRepository = $travelOrderSignatoriesRepo;
    }

    /**
     * Display a listing of the TravelOrderSignatories.
     */
    public function index(Request $request)
    {
        $travelOrderSignatories = $this->travelOrderSignatoriesRepository->paginate(10);

        return view('travel_order_signatories.index')
            ->with('travelOrderSignatories', $travelOrderSignatories);
    }

    /**
     * Show the form for creating a new TravelOrderSignatories.
     */
    public function create()
    {
        return view('travel_order_signatories.create');
    }

    /**
     * Store a newly created TravelOrderSignatories in storage.
     */
    public function store(CreateTravelOrderSignatoriesRequest $request)
    {
        $input = $request->all();

        $travelOrderSignatories = $this->travelOrderSignatoriesRepository->create($input);

        Flash::success('Travel Order Signatories saved successfully.');

        return redirect(route('travelOrderSignatories.index'));
    }

    /**
     * Display the specified TravelOrderSignatories.
     */
    public function show($id)
    {
        $travelOrderSignatories = $this->travelOrderSignatoriesRepository->find($id);

        if (empty($travelOrderSignatories)) {
            Flash::error('Travel Order Signatories not found');

            return redirect(route('travelOrderSignatories.index'));
        }

        return view('travel_order_signatories.show')->with('travelOrderSignatories', $travelOrderSignatories);
    }

    /**
     * Show the form for editing the specified TravelOrderSignatories.
     */
    public function edit($id)
    {
        $travelOrderSignatories = $this->travelOrderSignatoriesRepository->find($id);

        if (empty($travelOrderSignatories)) {
            Flash::error('Travel Order Signatories not found');

            return redirect(route('travelOrderSignatories.index'));
        }

        return view('travel_order_signatories.edit')->with('travelOrderSignatories', $travelOrderSignatories);
    }

    /**
     * Update the specified TravelOrderSignatories in storage.
     */
    public function update($id, UpdateTravelOrderSignatoriesRequest $request)
    {
        $travelOrderSignatories = $this->travelOrderSignatoriesRepository->find($id);

        if (empty($travelOrderSignatories)) {
            Flash::error('Travel Order Signatories not found');

            return redirect(route('travelOrderSignatories.index'));
        }

        $travelOrderSignatories = $this->travelOrderSignatoriesRepository->update($request->all(), $id);

        Flash::success('Travel Order Signatories updated successfully.');

        return redirect(route('travelOrderSignatories.index'));
    }

    /**
     * Remove the specified TravelOrderSignatories from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $travelOrderSignatories = $this->travelOrderSignatoriesRepository->find($id);

        if (empty($travelOrderSignatories)) {
            Flash::error('Travel Order Signatories not found');

            return redirect(route('travelOrderSignatories.index'));
        }

        $this->travelOrderSignatoriesRepository->delete($id);

        Flash::success('Travel Order Signatories deleted successfully.');

        return redirect(route('travelOrderSignatories.index'));
    }
}
