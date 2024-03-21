<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTravelOrderDaysRequest;
use App\Http\Requests\UpdateTravelOrderDaysRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TravelOrderDaysRepository;
use Illuminate\Http\Request;
use Flash;

class TravelOrderDaysController extends AppBaseController
{
    /** @var TravelOrderDaysRepository $travelOrderDaysRepository*/
    private $travelOrderDaysRepository;

    public function __construct(TravelOrderDaysRepository $travelOrderDaysRepo)
    {
        $this->middleware('auth');
        $this->travelOrderDaysRepository = $travelOrderDaysRepo;
    }

    /**
     * Display a listing of the TravelOrderDays.
     */
    public function index(Request $request)
    {
        $travelOrderDays = $this->travelOrderDaysRepository->paginate(10);

        return view('travel_order_days.index')
            ->with('travelOrderDays', $travelOrderDays);
    }

    /**
     * Show the form for creating a new TravelOrderDays.
     */
    public function create()
    {
        return view('travel_order_days.create');
    }

    /**
     * Store a newly created TravelOrderDays in storage.
     */
    public function store(CreateTravelOrderDaysRequest $request)
    {
        $input = $request->all();

        $travelOrderDays = $this->travelOrderDaysRepository->create($input);

        Flash::success('Travel Order Days saved successfully.');

        return redirect(route('travelOrderDays.index'));
    }

    /**
     * Display the specified TravelOrderDays.
     */
    public function show($id)
    {
        $travelOrderDays = $this->travelOrderDaysRepository->find($id);

        if (empty($travelOrderDays)) {
            Flash::error('Travel Order Days not found');

            return redirect(route('travelOrderDays.index'));
        }

        return view('travel_order_days.show')->with('travelOrderDays', $travelOrderDays);
    }

    /**
     * Show the form for editing the specified TravelOrderDays.
     */
    public function edit($id)
    {
        $travelOrderDays = $this->travelOrderDaysRepository->find($id);

        if (empty($travelOrderDays)) {
            Flash::error('Travel Order Days not found');

            return redirect(route('travelOrderDays.index'));
        }

        return view('travel_order_days.edit')->with('travelOrderDays', $travelOrderDays);
    }

    /**
     * Update the specified TravelOrderDays in storage.
     */
    public function update($id, UpdateTravelOrderDaysRequest $request)
    {
        $travelOrderDays = $this->travelOrderDaysRepository->find($id);

        if (empty($travelOrderDays)) {
            Flash::error('Travel Order Days not found');

            return redirect(route('travelOrderDays.index'));
        }

        $travelOrderDays = $this->travelOrderDaysRepository->update($request->all(), $id);

        Flash::success('Travel Order Days updated successfully.');

        return redirect(route('travelOrderDays.index'));
    }

    /**
     * Remove the specified TravelOrderDays from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $travelOrderDays = $this->travelOrderDaysRepository->find($id);

        if (empty($travelOrderDays)) {
            Flash::error('Travel Order Days not found');

            return redirect(route('travelOrderDays.index'));
        }

        $this->travelOrderDaysRepository->delete($id);

        Flash::success('Travel Order Days deleted successfully.');

        return redirect(route('travelOrderDays.index'));
    }
}
