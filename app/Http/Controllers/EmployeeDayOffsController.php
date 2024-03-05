<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeDayOffsRequest;
use App\Http\Requests\UpdateEmployeeDayOffsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EmployeeDayOffsRepository;
use Illuminate\Http\Request;
use App\Models\EmployeeDayOffs;
use App\Models\IDGenerator;
use Flash;

class EmployeeDayOffsController extends AppBaseController
{
    /** @var EmployeeDayOffsRepository $employeeDayOffsRepository*/
    private $employeeDayOffsRepository;

    public function __construct(EmployeeDayOffsRepository $employeeDayOffsRepo)
    {
        $this->middleware('auth');
        $this->employeeDayOffsRepository = $employeeDayOffsRepo;
    }

    /**
     * Display a listing of the EmployeeDayOffs.
     */
    public function index(Request $request)
    {
        $employeeDayOffs = $this->employeeDayOffsRepository->paginate(10);

        return view('employee_day_offs.index')
            ->with('employeeDayOffs', $employeeDayOffs);
    }

    /**
     * Show the form for creating a new EmployeeDayOffs.
     */
    public function create()
    {
        return view('employee_day_offs.create');
    }

    /**
     * Store a newly created EmployeeDayOffs in storage.
     */
    public function store(CreateEmployeeDayOffsRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();

        $employeeDayOffs = $this->employeeDayOffsRepository->create($input);

        // Flash::success('Employee Day Offs saved successfully.');

        // return redirect(route('employeeDayOffs.index'));
        return response()->json($employeeDayOffs, 200);
    }

    /**
     * Display the specified EmployeeDayOffs.
     */
    public function show($id)
    {
        $employeeDayOffs = $this->employeeDayOffsRepository->find($id);

        if (empty($employeeDayOffs)) {
            Flash::error('Employee Day Offs not found');

            return redirect(route('employeeDayOffs.index'));
        }

        return view('employee_day_offs.show')->with('employeeDayOffs', $employeeDayOffs);
    }

    /**
     * Show the form for editing the specified EmployeeDayOffs.
     */
    public function edit($id)
    {
        $employeeDayOffs = $this->employeeDayOffsRepository->find($id);

        if (empty($employeeDayOffs)) {
            Flash::error('Employee Day Offs not found');

            return redirect(route('employeeDayOffs.index'));
        }

        return view('employee_day_offs.edit')->with('employeeDayOffs', $employeeDayOffs);
    }

    /**
     * Update the specified EmployeeDayOffs in storage.
     */
    public function update($id, UpdateEmployeeDayOffsRequest $request)
    {
        $employeeDayOffs = $this->employeeDayOffsRepository->find($id);

        if (empty($employeeDayOffs)) {
            Flash::error('Employee Day Offs not found');

            return redirect(route('employeeDayOffs.index'));
        }

        $employeeDayOffs = $this->employeeDayOffsRepository->update($request->all(), $id);

        Flash::success('Employee Day Offs updated successfully.');

        return redirect(route('employeeDayOffs.index'));
    }

    /**
     * Remove the specified EmployeeDayOffs from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $employeeDayOffs = $this->employeeDayOffsRepository->find($id);

        if (empty($employeeDayOffs)) {
            Flash::error('Employee Day Offs not found');

            return redirect(route('employeeDayOffs.index'));
        }

        $this->employeeDayOffsRepository->delete($id);

        Flash::success('Employee Day Offs deleted successfully.');

        return redirect(route('employeeDayOffs.index'));
    }

    public function getByEmployee(Request $request) {
        $employeeId = $request['EmployeeId'];

        return response()->json(EmployeeDayOffs::where('EmployeeId', $employeeId)->orderBy('DayOff')->get(), 200);
    }

    public function remove(Request $request) {
        $employeeId = $request['EmployeeId'];
        $day = $request['Day'];

        EmployeeDayOffs::where('DayOff', $day)
            ->where('EmployeeId', $employeeId)
            ->delete();

        return response()->json('ok', 200);
    }
}
