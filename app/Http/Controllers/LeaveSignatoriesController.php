<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveSignatoriesRequest;
use App\Http\Requests\UpdateLeaveSignatoriesRequest;
use App\Repositories\LeaveSignatoriesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeaveSignatoriesController extends AppBaseController
{
    /** @var  LeaveSignatoriesRepository */
    private $leaveSignatoriesRepository;

    public function __construct(LeaveSignatoriesRepository $leaveSignatoriesRepo)
    {
        $this->middleware('auth');
        $this->leaveSignatoriesRepository = $leaveSignatoriesRepo;
    }

    /**
     * Display a listing of the LeaveSignatories.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveSignatories = $this->leaveSignatoriesRepository->all();

        return view('leave_signatories.index')
            ->with('leaveSignatories', $leaveSignatories);
    }

    /**
     * Show the form for creating a new LeaveSignatories.
     *
     * @return Response
     */
    public function create()
    {
        return view('leave_signatories.create');
    }

    /**
     * Store a newly created LeaveSignatories in storage.
     *
     * @param CreateLeaveSignatoriesRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveSignatoriesRequest $request)
    {
        $input = $request->all();

        $leaveSignatories = $this->leaveSignatoriesRepository->create($input);

        Flash::success('Leave Signatories saved successfully.');

        return redirect(route('leaveSignatories.index'));
    }

    /**
     * Display the specified LeaveSignatories.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveSignatories = $this->leaveSignatoriesRepository->find($id);

        if (empty($leaveSignatories)) {
            Flash::error('Leave Signatories not found');

            return redirect(route('leaveSignatories.index'));
        }

        return view('leave_signatories.show')->with('leaveSignatories', $leaveSignatories);
    }

    /**
     * Show the form for editing the specified LeaveSignatories.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveSignatories = $this->leaveSignatoriesRepository->find($id);

        if (empty($leaveSignatories)) {
            Flash::error('Leave Signatories not found');

            return redirect(route('leaveSignatories.index'));
        }

        return view('leave_signatories.edit')->with('leaveSignatories', $leaveSignatories);
    }

    /**
     * Update the specified LeaveSignatories in storage.
     *
     * @param int $id
     * @param UpdateLeaveSignatoriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveSignatoriesRequest $request)
    {
        $leaveSignatories = $this->leaveSignatoriesRepository->find($id);

        if (empty($leaveSignatories)) {
            Flash::error('Leave Signatories not found');

            return redirect(route('leaveSignatories.index'));
        }

        $leaveSignatories = $this->leaveSignatoriesRepository->update($request->all(), $id);

        Flash::success('Leave Signatories updated successfully.');

        return redirect(route('leaveSignatories.index'));
    }

    /**
     * Remove the specified LeaveSignatories from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveSignatories = $this->leaveSignatoriesRepository->find($id);

        if (empty($leaveSignatories)) {
            Flash::error('Leave Signatories not found');

            return redirect(route('leaveSignatories.index'));
        }

        $this->leaveSignatoriesRepository->delete($id);

        // Flash::success('Leave Signatories deleted successfully.');

        return response()->json(['res' => 'ok'], 200);
    }
}
