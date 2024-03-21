<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTravelOrderEmployeesRequest;
use App\Http\Requests\UpdateTravelOrderEmployeesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TravelOrderEmployeesRepository;
use Illuminate\Http\Request;
use Flash;

class TravelOrderEmployeesController extends AppBaseController
{
    /** @var TravelOrderEmployeesRepository $travelOrderEmployeesRepository*/
    private $travelOrderEmployeesRepository;

    public function __construct(TravelOrderEmployeesRepository $travelOrderEmployeesRepo)
    {
        $this->middleware('auth');
        $this->travelOrderEmployeesRepository = $travelOrderEmployeesRepo;
    }

    /**
     * Display a listing of the TravelOrderEmployees.
     */
    public function index(Request $request)
    {
        $travelOrderEmployees = $this->travelOrderEmployeesRepository->paginate(10);

        return view('travel_order_employees.index')
            ->with('travelOrderEmployees', $travelOrderEmployees);
    }

    /**
     * Show the form for creating a new TravelOrderEmployees.
     */
    public function create()
    {
        return view('travel_order_employees.create');
    }

    /**
     * Store a newly created TravelOrderEmployees in storage.
     */
    public function store(CreateTravelOrderEmployeesRequest $request)
    {
        $input = $request->all();

        $travelOrderEmployees = $this->travelOrderEmployeesRepository->create($input);

        Flash::success('Travel Order Employees saved successfully.');

        return redirect(route('travelOrderEmployees.index'));
    }

    /**
     * Display the specified TravelOrderEmployees.
     */
    public function show($id)
    {
        $travelOrderEmployees = $this->travelOrderEmployeesRepository->find($id);

        if (empty($travelOrderEmployees)) {
            Flash::error('Travel Order Employees not found');

            return redirect(route('travelOrderEmployees.index'));
        }

        return view('travel_order_employees.show')->with('travelOrderEmployees', $travelOrderEmployees);
    }

    /**
     * Show the form for editing the specified TravelOrderEmployees.
     */
    public function edit($id)
    {
        $travelOrderEmployees = $this->travelOrderEmployeesRepository->find($id);

        if (empty($travelOrderEmployees)) {
            Flash::error('Travel Order Employees not found');

            return redirect(route('travelOrderEmployees.index'));
        }

        return view('travel_order_employees.edit')->with('travelOrderEmployees', $travelOrderEmployees);
    }

    /**
     * Update the specified TravelOrderEmployees in storage.
     */
    public function update($id, UpdateTravelOrderEmployeesRequest $request)
    {
        $travelOrderEmployees = $this->travelOrderEmployeesRepository->find($id);

        if (empty($travelOrderEmployees)) {
            Flash::error('Travel Order Employees not found');

            return redirect(route('travelOrderEmployees.index'));
        }

        $travelOrderEmployees = $this->travelOrderEmployeesRepository->update($request->all(), $id);

        Flash::success('Travel Order Employees updated successfully.');

        return redirect(route('travelOrderEmployees.index'));
    }

    /**
     * Remove the specified TravelOrderEmployees from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $travelOrderEmployees = $this->travelOrderEmployeesRepository->find($id);

        if (empty($travelOrderEmployees)) {
            Flash::error('Travel Order Employees not found');

            return redirect(route('travelOrderEmployees.index'));
        }

        $this->travelOrderEmployeesRepository->delete($id);

        Flash::success('Travel Order Employees deleted successfully.');

        return redirect(route('travelOrderEmployees.index'));
    }
}
