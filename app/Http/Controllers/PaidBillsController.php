<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaidBillsRequest;
use App\Http\Requests\UpdatePaidBillsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PaidBillsRepository;
use Illuminate\Http\Request;
use Flash;

class PaidBillsController extends AppBaseController
{
    /** @var PaidBillsRepository $paidBillsRepository*/
    private $paidBillsRepository;

    public function __construct(PaidBillsRepository $paidBillsRepo)
    {
        $this->middleware('auth');
        $this->paidBillsRepository = $paidBillsRepo;
    }

    /**
     * Display a listing of the PaidBills.
     */
    public function index(Request $request)
    {
        $paidBills = $this->paidBillsRepository->paginate(10);

        return view('paid_bills.index')
            ->with('paidBills', $paidBills);
    }

    /**
     * Show the form for creating a new PaidBills.
     */
    public function create()
    {
        return view('paid_bills.create');
    }

    /**
     * Store a newly created PaidBills in storage.
     */
    public function store(CreatePaidBillsRequest $request)
    {
        $input = $request->all();

        $paidBills = $this->paidBillsRepository->create($input);

        Flash::success('Paid Bills saved successfully.');

        return redirect(route('paidBills.index'));
    }

    /**
     * Display the specified PaidBills.
     */
    public function show($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        return view('paid_bills.show')->with('paidBills', $paidBills);
    }

    /**
     * Show the form for editing the specified PaidBills.
     */
    public function edit($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        return view('paid_bills.edit')->with('paidBills', $paidBills);
    }

    /**
     * Update the specified PaidBills in storage.
     */
    public function update($id, UpdatePaidBillsRequest $request)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        $paidBills = $this->paidBillsRepository->update($request->all(), $id);

        Flash::success('Paid Bills updated successfully.');

        return redirect(route('paidBills.index'));
    }

    /**
     * Remove the specified PaidBills from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        $this->paidBillsRepository->delete($id);

        Flash::success('Paid Bills deleted successfully.');

        return redirect(route('paidBills.index'));
    }
}
