<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayrollSchedulesRequest;
use App\Http\Requests\UpdatePayrollSchedulesRequest;
use App\Repositories\PayrollSchedulesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PayrollSchedulesController extends AppBaseController
{
    /** @var  PayrollSchedulesRepository */
    private $payrollSchedulesRepository;

    public function __construct(PayrollSchedulesRepository $payrollSchedulesRepo)
    {
        $this->middleware('auth');
        $this->payrollSchedulesRepository = $payrollSchedulesRepo;
    }

    /**
     * Display a listing of the PayrollSchedules.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $payrollSchedules = $this->payrollSchedulesRepository->all();

        return view('payroll_schedules.index')
            ->with('payrollSchedules', $payrollSchedules);
    }

    /**
     * Show the form for creating a new PayrollSchedules.
     *
     * @return Response
     */
    public function create()
    {
        return view('payroll_schedules.create');
    }

    /**
     * Store a newly created PayrollSchedules in storage.
     *
     * @param CreatePayrollSchedulesRequest $request
     *
     * @return Response
     */
    public function store(CreatePayrollSchedulesRequest $request)
    {
        $input = $request->all();

        $payrollSchedules = $this->payrollSchedulesRepository->create($input);

        Flash::success('Payroll Schedules saved successfully.');

        return redirect(route('payrollSchedules.index'));
    }

    /**
     * Display the specified PayrollSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $payrollSchedules = $this->payrollSchedulesRepository->find($id);

        if (empty($payrollSchedules)) {
            Flash::error('Payroll Schedules not found');

            return redirect(route('payrollSchedules.index'));
        }

        return view('payroll_schedules.show')->with('payrollSchedules', $payrollSchedules);
    }

    /**
     * Show the form for editing the specified PayrollSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $payrollSchedules = $this->payrollSchedulesRepository->find($id);

        if (empty($payrollSchedules)) {
            Flash::error('Payroll Schedules not found');

            return redirect(route('payrollSchedules.index'));
        }

        return view('payroll_schedules.edit')->with('payrollSchedules', $payrollSchedules);
    }

    /**
     * Update the specified PayrollSchedules in storage.
     *
     * @param int $id
     * @param UpdatePayrollSchedulesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePayrollSchedulesRequest $request)
    {
        $payrollSchedules = $this->payrollSchedulesRepository->find($id);

        if (empty($payrollSchedules)) {
            Flash::error('Payroll Schedules not found');

            return redirect(route('payrollSchedules.index'));
        }

        $payrollSchedules = $this->payrollSchedulesRepository->update($request->all(), $id);

        Flash::success('Payroll Schedules updated successfully.');

        return redirect(route('payrollSchedules.index'));
    }

    /**
     * Remove the specified PayrollSchedules from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $payrollSchedules = $this->payrollSchedulesRepository->find($id);

        if (empty($payrollSchedules)) {
            Flash::error('Payroll Schedules not found');

            return redirect(route('payrollSchedules.index'));
        }

        $this->payrollSchedulesRepository->delete($id);

        Flash::success('Payroll Schedules deleted successfully.');

        return redirect(route('payrollSchedules.index'));
    }
}
