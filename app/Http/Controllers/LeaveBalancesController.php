<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveBalancesRequest;
use App\Http\Requests\UpdateLeaveBalancesRequest;
use App\Repositories\LeaveBalancesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeaveBalancesController extends AppBaseController
{
    /** @var  LeaveBalancesRepository */
    private $leaveBalancesRepository;

    public function __construct(LeaveBalancesRepository $leaveBalancesRepo)
    {
        $this->middleware('auth');
        $this->leaveBalancesRepository = $leaveBalancesRepo;
    }

    /**
     * Display a listing of the LeaveBalances.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveBalances = $this->leaveBalancesRepository->all();

        return view('leave_balances.index')
            ->with('leaveBalances', $leaveBalances);
    }

    /**
     * Show the form for creating a new LeaveBalances.
     *
     * @return Response
     */
    public function create()
    {
        return view('leave_balances.create');
    }

    /**
     * Store a newly created LeaveBalances in storage.
     *
     * @param CreateLeaveBalancesRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveBalancesRequest $request)
    {
        $input = $request->all();

        $leaveBalances = $this->leaveBalancesRepository->create($input);

        Flash::success('Leave Balances saved successfully.');

        return redirect(route('leaveBalances.index'));
    }

    /**
     * Display the specified LeaveBalances.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        return view('leave_balances.show')->with('leaveBalances', $leaveBalances);
    }

    /**
     * Show the form for editing the specified LeaveBalances.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        return view('leave_balances.edit')->with('leaveBalances', $leaveBalances);
    }

    /**
     * Update the specified LeaveBalances in storage.
     *
     * @param int $id
     * @param UpdateLeaveBalancesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveBalancesRequest $request)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        $leaveBalances = $this->leaveBalancesRepository->update($request->all(), $id);

        Flash::success('Leave Balances updated successfully.');

        return redirect(route('leaveBalances.index'));
    }

    /**
     * Remove the specified LeaveBalances from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        $this->leaveBalancesRepository->delete($id);

        Flash::success('Leave Balances deleted successfully.');

        return redirect(route('leaveBalances.index'));
    }
}
