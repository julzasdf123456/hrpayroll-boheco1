<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeesDesignationsRequest;
use App\Http\Requests\UpdateEmployeesDesignationsRequest;
use App\Repositories\EmployeesDesignationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\EmployeesDesignations;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class EmployeesDesignationsController extends AppBaseController
{
    /** @var  EmployeesDesignationsRepository */
    private $employeesDesignationsRepository;

    public function __construct(EmployeesDesignationsRepository $employeesDesignationsRepo)
    {
        $this->employeesDesignationsRepository = $employeesDesignationsRepo;
    }

    /**
     * Display a listing of the EmployeesDesignations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $employeesDesignations = $this->employeesDesignationsRepository->all();

        return view('employees_designations.index')
            ->with('employeesDesignations', $employeesDesignations);
    }

    /**
     * Show the form for creating a new EmployeesDesignations.
     *
     * @return Response
     */
    public function create()
    {
        $departments = DB::table('Positions')
            ->select('Department')
            ->orderBy('Department')
            ->groupBy('Department')
            ->get();

        return view('employees_designations.create', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created EmployeesDesignations in storage.
     *
     * @param CreateEmployeesDesignationsRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeesDesignationsRequest $request)
    {
        $input = $request->all();

        $prevDesignations = EmployeesDesignations::where('EmployeeId', $request['EmployeeId'])->update(['IsActive' => 'No']);

        $employeesDesignations = $this->employeesDesignationsRepository->create($input);

        Flash::success('Employees Designations saved successfully.');

        $employee = Employees::find($employeesDesignations->EmployeeId);

        if ($employee != null) {
            $employee->Designation = $employeesDesignations->id;
            $employee->PositionStatus = $request['Status'];
            $employee->save();
        }

        return redirect(route('employees.show', [$employeesDesignations->EmployeeId]));
    }

    /**
     * Display the specified EmployeesDesignations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $employeesDesignations = $this->employeesDesignationsRepository->find($id);

        if (empty($employeesDesignations)) {
            Flash::error('Employees Designations not found');

            return redirect(route('employeesDesignations.index'));
        }

        return view('employees_designations.show')->with('employeesDesignations', $employeesDesignations);
    }

    /**
     * Show the form for editing the specified EmployeesDesignations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (Permission::hasDirectPermission(['god permission', 'update employee designation'])) {
            $employeesDesignations = $this->employeesDesignationsRepository->find($id);

            if (empty($employeesDesignations)) {
                Flash::error('Employees Designations not found');

                return redirect(route('employeesDesignations.index'));
            }

            $departments = DB::table('Positions')
                ->select('Department')
                ->orderBy('Department')
                ->groupBy('Department')
                ->get();

            return view('employees_designations.edit', [
                'employeesDesignations' => $employeesDesignations,
                'departments' => $departments,
            ]);
        } else {
            return abort(403, 'You are not authorized to access this module.');
        }
    }

    /**
     * Update the specified EmployeesDesignations in storage.
     *
     * @param int $id
     * @param UpdateEmployeesDesignationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeesDesignationsRequest $request)
    {
        $employeesDesignations = $this->employeesDesignationsRepository->find($id);

        if (empty($employeesDesignations)) {
            Flash::error('Employees Designations not found');

            return redirect(route('employeesDesignations.index'));
        }

        $employeesDesignations = $this->employeesDesignationsRepository->update($request->all(), $id);

        $employee = Employees::find($employeesDesignations->EmployeeId);

        if ($employee != null) {
            $employee->Designation = $employeesDesignations->id;
            $employee->PositionStatus = $request['Status'];
            $employee->save();
        }

        Flash::success('Employees Designations updated successfully.');

        return redirect(route('employees.show', [$employeesDesignations->EmployeeId]));
    }

    /**
     * Remove the specified EmployeesDesignations from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $employeesDesignations = $this->employeesDesignationsRepository->find($id);

        if (empty($employeesDesignations)) {
            Flash::error('Employees Designations not found');

            return redirect(route('employeesDesignations.index'));
        }

        $this->employeesDesignationsRepository->delete($id);

        Flash::success('Employees Designations deleted successfully.');

        return redirect(route('employees.show', [$employeesDesignations->EmployeeId]));
    }
}
