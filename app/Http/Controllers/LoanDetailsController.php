<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoanDetailsRequest;
use App\Http\Requests\UpdateLoanDetailsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LoanDetailsRepository;
use Illuminate\Http\Request;
use Flash;

class LoanDetailsController extends AppBaseController
{
    /** @var LoanDetailsRepository $loanDetailsRepository*/
    private $loanDetailsRepository;

    public function __construct(LoanDetailsRepository $loanDetailsRepo)
    {
        $this->middleware('auth');
        $this->loanDetailsRepository = $loanDetailsRepo;
    }

    /**
     * Display a listing of the LoanDetails.
     */
    public function index(Request $request)
    {
        $loanDetails = $this->loanDetailsRepository->paginate(10);

        return view('loan_details.index')
            ->with('loanDetails', $loanDetails);
    }

    /**
     * Show the form for creating a new LoanDetails.
     */
    public function create()
    {
        return view('loan_details.create');
    }

    /**
     * Store a newly created LoanDetails in storage.
     */
    public function store(CreateLoanDetailsRequest $request)
    {
        $input = $request->all();

        $loanDetails = $this->loanDetailsRepository->create($input);

        Flash::success('Loan Details saved successfully.');

        return redirect(route('loanDetails.index'));
    }

    /**
     * Display the specified LoanDetails.
     */
    public function show($id)
    {
        $loanDetails = $this->loanDetailsRepository->find($id);

        if (empty($loanDetails)) {
            Flash::error('Loan Details not found');

            return redirect(route('loanDetails.index'));
        }

        return view('loan_details.show')->with('loanDetails', $loanDetails);
    }

    /**
     * Show the form for editing the specified LoanDetails.
     */
    public function edit($id)
    {
        $loanDetails = $this->loanDetailsRepository->find($id);

        if (empty($loanDetails)) {
            Flash::error('Loan Details not found');

            return redirect(route('loanDetails.index'));
        }

        return view('loan_details.edit')->with('loanDetails', $loanDetails);
    }

    /**
     * Update the specified LoanDetails in storage.
     */
    public function update($id, UpdateLoanDetailsRequest $request)
    {
        $loanDetails = $this->loanDetailsRepository->find($id);

        if (empty($loanDetails)) {
            Flash::error('Loan Details not found');

            return redirect(route('loanDetails.index'));
        }

        $loanDetails = $this->loanDetailsRepository->update($request->all(), $id);

        Flash::success('Loan Details updated successfully.');

        return redirect(route('loanDetails.index'));
    }

    /**
     * Remove the specified LoanDetails from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $loanDetails = $this->loanDetailsRepository->find($id);

        if (empty($loanDetails)) {
            Flash::error('Loan Details not found');

            return redirect(route('loanDetails.index'));
        }

        $this->loanDetailsRepository->delete($id);

        Flash::success('Loan Details deleted successfully.');

        return redirect(route('loanDetails.index'));
    }
}
