<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVehiclesRequest;
use App\Http\Requests\UpdateVehiclesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\VehiclesRepository;
use Illuminate\Http\Request;
use Flash;
use App\Models\Vehicles;
use Illuminate\Support\Facades\DB;

class VehiclesController extends AppBaseController
{
    /** @var VehiclesRepository $vehiclesRepository*/
    private $vehiclesRepository;

    public function __construct(VehiclesRepository $vehiclesRepo)
    {
        $this->middleware('auth');
        $this->vehiclesRepository = $vehiclesRepo;
    }

    /**
     * Display a listing of the Vehicles.
     */
    public function index(Request $request)
    {
        $vehicles = Vehicles::orderBy('VehicleName')->paginate(30);

        return view('vehicles.index', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Show the form for creating a new Vehicles.
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

        return view('vehicles.create', [
            'drivers' => $drivers,
        ]);
    }

    /**
     * Store a newly created Vehicles in storage.
     */
    public function store(CreateVehiclesRequest $request)
    {
        $input = $request->all();

        $vehicles = $this->vehiclesRepository->create($input);

        Flash::success('Vehicles saved successfully.');

        return redirect(route('vehicles.index'));
    }

    /**
     * Display the specified Vehicles.
     */
    public function show($id)
    {
        $vehicles = $this->vehiclesRepository->find($id);

        if (empty($vehicles)) {
            Flash::error('Vehicles not found');

            return redirect(route('vehicles.index'));
        }

        return view('vehicles.show')->with('vehicles', $vehicles);
    }

    /**
     * Show the form for editing the specified Vehicles.
     */
    public function edit($id)
    {
        $vehicles = $this->vehiclesRepository->find($id);

        $drivers = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.*')
            ->whereRaw("Positions.Level='Driver' OR Employees.AuthorizedToDrive='Yes'")
            ->orderBy('Employees.LastName')
            ->get();

        if (empty($vehicles)) {
            Flash::error('Vehicles not found');

            return redirect(route('vehicles.index'));
        }

        return view('vehicles.edit', [
            'vehicles' => $vehicles,
            'drivers' => $drivers
        ]);
    }

    /**
     * Update the specified Vehicles in storage.
     */
    public function update($id, UpdateVehiclesRequest $request)
    {
        $vehicles = $this->vehiclesRepository->find($id);

        if (empty($vehicles)) {
            Flash::error('Vehicles not found');

            return redirect(route('vehicles.index'));
        }

        $vehicles = $this->vehiclesRepository->update($request->all(), $id);

        Flash::success('Vehicles updated successfully.');

        return redirect(route('vehicles.index'));
    }

    /**
     * Remove the specified Vehicles from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $vehicles = $this->vehiclesRepository->find($id);

        if (empty($vehicles)) {
            Flash::error('Vehicles not found');

            return redirect(route('vehicles.index'));
        }

        $this->vehiclesRepository->delete($id);

        Flash::success('Vehicles deleted successfully.');

        return redirect(route('vehicles.index'));
    }
}
