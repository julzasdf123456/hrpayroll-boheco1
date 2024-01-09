<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveBalanceDetailsRequest;
use App\Http\Requests\UpdateLeaveBalanceDetailsRequest;
use App\Repositories\LeaveBalanceDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeaveBalanceDetailsController extends AppBaseController
{
    /** @var  LeaveBalanceDetailsRepository */
    private $leaveBalanceDetailsRepository;

    public function __construct(LeaveBalanceDetailsRepository $leaveBalanceDetailsRepo)
    {
        $this->middleware('auth');
        $this->leaveBalanceDetailsRepository = $leaveBalanceDetailsRepo;
    }

    /**
     * Display a listing of the LeaveBalanceDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveBalanceDetails = $this->leaveBalanceDetailsRepository->all();

        return view('leave_balance_details.index')
            ->with('leaveBalanceDetails', $leaveBalanceDetails);
    }

    /**
     * Show the form for creating a new LeaveBalanceDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('leave_balance_details.create');
    }

    /**
     * Store a newly created LeaveBalanceDetails in storage.
     *
     * @param CreateLeaveBalanceDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveBalanceDetailsRequest $request)
    {
        $input = $request->all();

        $leaveBalanceDetails = $this->leaveBalanceDetailsRepository->create($input);

        Flash::success('Leave Balance Details saved successfully.');

        return redirect(route('leaveBalanceDetails.index'));
    }

    /**
     * Display the specified LeaveBalanceDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveBalanceDetails = $this->leaveBalanceDetailsRepository->find($id);

        if (empty($leaveBalanceDetails)) {
            Flash::error('Leave Balance Details not found');

            return redirect(route('leaveBalanceDetails.index'));
        }

        return view('leave_balance_details.show')->with('leaveBalanceDetails', $leaveBalanceDetails);
    }

    /**
     * Show the form for editing the specified LeaveBalanceDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveBalanceDetails = $this->leaveBalanceDetailsRepository->find($id);

        if (empty($leaveBalanceDetails)) {
            Flash::error('Leave Balance Details not found');

            return redirect(route('leaveBalanceDetails.index'));
        }

        return view('leave_balance_details.edit')->with('leaveBalanceDetails', $leaveBalanceDetails);
    }

    /**
     * Update the specified LeaveBalanceDetails in storage.
     *
     * @param int $id
     * @param UpdateLeaveBalanceDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveBalanceDetailsRequest $request)
    {
        $leaveBalanceDetails = $this->leaveBalanceDetailsRepository->find($id);

        if (empty($leaveBalanceDetails)) {
            Flash::error('Leave Balance Details not found');

            return redirect(route('leaveBalanceDetails.index'));
        }

        $leaveBalanceDetails = $this->leaveBalanceDetailsRepository->update($request->all(), $id);

        Flash::success('Leave Balance Details updated successfully.');

        return redirect(route('leaveBalanceDetails.index'));
    }

    /**
     * Remove the specified LeaveBalanceDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveBalanceDetails = $this->leaveBalanceDetailsRepository->find($id);

        if (empty($leaveBalanceDetails)) {
            Flash::error('Leave Balance Details not found');

            return redirect(route('leaveBalanceDetails.index'));
        }

        $this->leaveBalanceDetailsRepository->delete($id);

        Flash::success('Leave Balance Details deleted successfully.');

        return redirect(route('leaveBalanceDetails.index'));
    }
}
