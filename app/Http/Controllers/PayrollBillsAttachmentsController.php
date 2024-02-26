<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayrollBillsAttachmentsRequest;
use App\Http\Requests\UpdatePayrollBillsAttachmentsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PayrollBillsAttachmentsRepository;
use Illuminate\Http\Request;
use Flash;

class PayrollBillsAttachmentsController extends AppBaseController
{
    /** @var PayrollBillsAttachmentsRepository $payrollBillsAttachmentsRepository*/
    private $payrollBillsAttachmentsRepository;

    public function __construct(PayrollBillsAttachmentsRepository $payrollBillsAttachmentsRepo)
    {
        $this->middleware('auth');
        $this->payrollBillsAttachmentsRepository = $payrollBillsAttachmentsRepo;
    }

    /**
     * Display a listing of the PayrollBillsAttachments.
     */
    public function index(Request $request)
    {
        $payrollBillsAttachments = $this->payrollBillsAttachmentsRepository->paginate(10);

        return view('payroll_bills_attachments.index')
            ->with('payrollBillsAttachments', $payrollBillsAttachments);
    }

    /**
     * Show the form for creating a new PayrollBillsAttachments.
     */
    public function create()
    {
        return view('payroll_bills_attachments.create');
    }

    /**
     * Store a newly created PayrollBillsAttachments in storage.
     */
    public function store(CreatePayrollBillsAttachmentsRequest $request)
    {
        $input = $request->all();

        $payrollBillsAttachments = $this->payrollBillsAttachmentsRepository->create($input);

        Flash::success('Payroll Bills Attachments saved successfully.');

        return redirect(route('payrollBillsAttachments.index'));
    }

    /**
     * Display the specified PayrollBillsAttachments.
     */
    public function show($id)
    {
        $payrollBillsAttachments = $this->payrollBillsAttachmentsRepository->find($id);

        if (empty($payrollBillsAttachments)) {
            Flash::error('Payroll Bills Attachments not found');

            return redirect(route('payrollBillsAttachments.index'));
        }

        return view('payroll_bills_attachments.show')->with('payrollBillsAttachments', $payrollBillsAttachments);
    }

    /**
     * Show the form for editing the specified PayrollBillsAttachments.
     */
    public function edit($id)
    {
        $payrollBillsAttachments = $this->payrollBillsAttachmentsRepository->find($id);

        if (empty($payrollBillsAttachments)) {
            Flash::error('Payroll Bills Attachments not found');

            return redirect(route('payrollBillsAttachments.index'));
        }

        return view('payroll_bills_attachments.edit')->with('payrollBillsAttachments', $payrollBillsAttachments);
    }

    /**
     * Update the specified PayrollBillsAttachments in storage.
     */
    public function update($id, UpdatePayrollBillsAttachmentsRequest $request)
    {
        $payrollBillsAttachments = $this->payrollBillsAttachmentsRepository->find($id);

        if (empty($payrollBillsAttachments)) {
            Flash::error('Payroll Bills Attachments not found');

            return redirect(route('payrollBillsAttachments.index'));
        }

        $payrollBillsAttachments = $this->payrollBillsAttachmentsRepository->update($request->all(), $id);

        Flash::success('Payroll Bills Attachments updated successfully.');

        return redirect(route('payrollBillsAttachments.index'));
    }

    /**
     * Remove the specified PayrollBillsAttachments from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $payrollBillsAttachments = $this->payrollBillsAttachmentsRepository->find($id);

        if (empty($payrollBillsAttachments)) {
            Flash::error('Payroll Bills Attachments not found');

            return redirect(route('payrollBillsAttachments.index'));
        }

        $this->payrollBillsAttachmentsRepository->delete($id);

        Flash::success('Payroll Bills Attachments deleted successfully.');

        return redirect(route('payrollBillsAttachments.index'));
    }
}
