<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayrollDetailsRequest;
use App\Http\Requests\UpdatePayrollDetailsRequest;
use App\Repositories\PayrollDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PayrollDetailsController extends AppBaseController
{
    /** @var  PayrollDetailsRepository */
    private $payrollDetailsRepository;

    public function __construct(PayrollDetailsRepository $payrollDetailsRepo)
    {
        $this->middleware('auth');
        $this->payrollDetailsRepository = $payrollDetailsRepo;
    }

    /**
     * Display a listing of the PayrollDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $payrollDetails = $this->payrollDetailsRepository->all();

        return view('payroll_details.index')
            ->with('payrollDetails', $payrollDetails);
    }

    /**
     * Show the form for creating a new PayrollDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('payroll_details.create');
    }

    /**
     * Store a newly created PayrollDetails in storage.
     *
     * @param CreatePayrollDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreatePayrollDetailsRequest $request)
    {
        $input = $request->all();

        $payrollDetails = $this->payrollDetailsRepository->create($input);

        Flash::success('Payroll Details saved successfully.');

        return redirect(route('payrollDetails.index'));
    }

    /**
     * Display the specified PayrollDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $payrollDetails = $this->payrollDetailsRepository->find($id);

        if (empty($payrollDetails)) {
            Flash::error('Payroll Details not found');

            return redirect(route('payrollDetails.index'));
        }

        return view('payroll_details.show')->with('payrollDetails', $payrollDetails);
    }

    /**
     * Show the form for editing the specified PayrollDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $payrollDetails = $this->payrollDetailsRepository->find($id);

        if (empty($payrollDetails)) {
            Flash::error('Payroll Details not found');

            return redirect(route('payrollDetails.index'));
        }

        return view('payroll_details.edit')->with('payrollDetails', $payrollDetails);
    }

    /**
     * Update the specified PayrollDetails in storage.
     *
     * @param int $id
     * @param UpdatePayrollDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePayrollDetailsRequest $request)
    {
        $payrollDetails = $this->payrollDetailsRepository->find($id);

        if (empty($payrollDetails)) {
            Flash::error('Payroll Details not found');

            return redirect(route('payrollDetails.index'));
        }

        $payrollDetails = $this->payrollDetailsRepository->update($request->all(), $id);

        Flash::success('Payroll Details updated successfully.');

        return redirect(route('payrollDetails.index'));
    }

    /**
     * Remove the specified PayrollDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $payrollDetails = $this->payrollDetailsRepository->find($id);

        if (empty($payrollDetails)) {
            Flash::error('Payroll Details not found');

            return redirect(route('payrollDetails.index'));
        }

        $this->payrollDetailsRepository->delete($id);

        Flash::success('Payroll Details deleted successfully.');

        return redirect(route('payrollDetails.index'));
    }
}
