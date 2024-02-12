<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeIncntvsProjectionTaxMarkRequest;
use App\Http\Requests\UpdateEmployeeIncntvsProjectionTaxMarkRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EmployeeIncntvsProjectionTaxMarkRepository;
use Illuminate\Http\Request;
use Flash;

class EmployeeIncntvsProjectionTaxMarkController extends AppBaseController
{
    /** @var EmployeeIncntvsProjectionTaxMarkRepository $employeeIncntvsProjectionTaxMarkRepository*/
    private $employeeIncntvsProjectionTaxMarkRepository;

    public function __construct(EmployeeIncntvsProjectionTaxMarkRepository $employeeIncntvsProjectionTaxMarkRepo)
    {
        $this->middleware('auth');
        $this->employeeIncntvsProjectionTaxMarkRepository = $employeeIncntvsProjectionTaxMarkRepo;
    }

    /**
     * Display a listing of the EmployeeIncntvsProjectionTaxMark.
     */
    public function index(Request $request)
    {
        $employeeIncntvsProjectionTaxMarks = $this->employeeIncntvsProjectionTaxMarkRepository->paginate(10);

        return view('employee_incntvs_projection_tax_marks.index')
            ->with('employeeIncntvsProjectionTaxMarks', $employeeIncntvsProjectionTaxMarks);
    }

    /**
     * Show the form for creating a new EmployeeIncntvsProjectionTaxMark.
     */
    public function create()
    {
        return view('employee_incntvs_projection_tax_marks.create');
    }

    /**
     * Store a newly created EmployeeIncntvsProjectionTaxMark in storage.
     */
    public function store(CreateEmployeeIncntvsProjectionTaxMarkRequest $request)
    {
        $input = $request->all();

        $employeeIncntvsProjectionTaxMark = $this->employeeIncntvsProjectionTaxMarkRepository->create($input);

        Flash::success('Employee Incntvs Projection Tax Mark saved successfully.');

        return redirect(route('employeeIncntvsProjectionTaxMarks.index'));
    }

    /**
     * Display the specified EmployeeIncntvsProjectionTaxMark.
     */
    public function show($id)
    {
        $employeeIncntvsProjectionTaxMark = $this->employeeIncntvsProjectionTaxMarkRepository->find($id);

        if (empty($employeeIncntvsProjectionTaxMark)) {
            Flash::error('Employee Incntvs Projection Tax Mark not found');

            return redirect(route('employeeIncntvsProjectionTaxMarks.index'));
        }

        return view('employee_incntvs_projection_tax_marks.show')->with('employeeIncntvsProjectionTaxMark', $employeeIncntvsProjectionTaxMark);
    }

    /**
     * Show the form for editing the specified EmployeeIncntvsProjectionTaxMark.
     */
    public function edit($id)
    {
        $employeeIncntvsProjectionTaxMark = $this->employeeIncntvsProjectionTaxMarkRepository->find($id);

        if (empty($employeeIncntvsProjectionTaxMark)) {
            Flash::error('Employee Incntvs Projection Tax Mark not found');

            return redirect(route('employeeIncntvsProjectionTaxMarks.index'));
        }

        return view('employee_incntvs_projection_tax_marks.edit')->with('employeeIncntvsProjectionTaxMark', $employeeIncntvsProjectionTaxMark);
    }

    /**
     * Update the specified EmployeeIncntvsProjectionTaxMark in storage.
     */
    public function update($id, UpdateEmployeeIncntvsProjectionTaxMarkRequest $request)
    {
        $employeeIncntvsProjectionTaxMark = $this->employeeIncntvsProjectionTaxMarkRepository->find($id);

        if (empty($employeeIncntvsProjectionTaxMark)) {
            Flash::error('Employee Incntvs Projection Tax Mark not found');

            return redirect(route('employeeIncntvsProjectionTaxMarks.index'));
        }

        $employeeIncntvsProjectionTaxMark = $this->employeeIncntvsProjectionTaxMarkRepository->update($request->all(), $id);

        Flash::success('Employee Incntvs Projection Tax Mark updated successfully.');

        return redirect(route('employeeIncntvsProjectionTaxMarks.index'));
    }

    /**
     * Remove the specified EmployeeIncntvsProjectionTaxMark from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $employeeIncntvsProjectionTaxMark = $this->employeeIncntvsProjectionTaxMarkRepository->find($id);

        if (empty($employeeIncntvsProjectionTaxMark)) {
            Flash::error('Employee Incntvs Projection Tax Mark not found');

            return redirect(route('employeeIncntvsProjectionTaxMarks.index'));
        }

        $this->employeeIncntvsProjectionTaxMarkRepository->delete($id);

        Flash::success('Employee Incntvs Projection Tax Mark deleted successfully.');

        return redirect(route('employeeIncntvsProjectionTaxMarks.index'));
    }
}
