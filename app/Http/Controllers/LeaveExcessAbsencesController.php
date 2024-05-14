<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveExcessAbsencesRequest;
use App\Http\Requests\UpdateLeaveExcessAbsencesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LeaveExcessAbsencesRepository;
use Illuminate\Http\Request;
use Flash;

class LeaveExcessAbsencesController extends AppBaseController
{
    /** @var LeaveExcessAbsencesRepository $leaveExcessAbsencesRepository*/
    private $leaveExcessAbsencesRepository;

    public function __construct(LeaveExcessAbsencesRepository $leaveExcessAbsencesRepo)
    {
        $this->middleware('auth');
        $this->leaveExcessAbsencesRepository = $leaveExcessAbsencesRepo;
    }

    /**
     * Display a listing of the LeaveExcessAbsences.
     */
    public function index(Request $request)
    {
        $leaveExcessAbsences = $this->leaveExcessAbsencesRepository->paginate(10);

        return view('leave_excess_absences.index')
            ->with('leaveExcessAbsences', $leaveExcessAbsences);
    }

    /**
     * Show the form for creating a new LeaveExcessAbsences.
     */
    public function create()
    {
        return view('leave_excess_absences.create');
    }

    /**
     * Store a newly created LeaveExcessAbsences in storage.
     */
    public function store(CreateLeaveExcessAbsencesRequest $request)
    {
        $input = $request->all();

        $leaveExcessAbsences = $this->leaveExcessAbsencesRepository->create($input);

        Flash::success('Leave Excess Absences saved successfully.');

        return redirect(route('leaveExcessAbsences.index'));
    }

    /**
     * Display the specified LeaveExcessAbsences.
     */
    public function show($id)
    {
        $leaveExcessAbsences = $this->leaveExcessAbsencesRepository->find($id);

        if (empty($leaveExcessAbsences)) {
            Flash::error('Leave Excess Absences not found');

            return redirect(route('leaveExcessAbsences.index'));
        }

        return view('leave_excess_absences.show')->with('leaveExcessAbsences', $leaveExcessAbsences);
    }

    /**
     * Show the form for editing the specified LeaveExcessAbsences.
     */
    public function edit($id)
    {
        $leaveExcessAbsences = $this->leaveExcessAbsencesRepository->find($id);

        if (empty($leaveExcessAbsences)) {
            Flash::error('Leave Excess Absences not found');

            return redirect(route('leaveExcessAbsences.index'));
        }

        return view('leave_excess_absences.edit')->with('leaveExcessAbsences', $leaveExcessAbsences);
    }

    /**
     * Update the specified LeaveExcessAbsences in storage.
     */
    public function update($id, UpdateLeaveExcessAbsencesRequest $request)
    {
        $leaveExcessAbsences = $this->leaveExcessAbsencesRepository->find($id);

        if (empty($leaveExcessAbsences)) {
            Flash::error('Leave Excess Absences not found');

            return redirect(route('leaveExcessAbsences.index'));
        }

        $leaveExcessAbsences = $this->leaveExcessAbsencesRepository->update($request->all(), $id);

        Flash::success('Leave Excess Absences updated successfully.');

        return redirect(route('leaveExcessAbsences.index'));
    }

    /**
     * Remove the specified LeaveExcessAbsences from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $leaveExcessAbsences = $this->leaveExcessAbsencesRepository->find($id);

        if (empty($leaveExcessAbsences)) {
            Flash::error('Leave Excess Absences not found');

            return redirect(route('leaveExcessAbsences.index'));
        }

        $this->leaveExcessAbsencesRepository->delete($id);

        Flash::success('Leave Excess Absences deleted successfully.');

        return redirect(route('leaveExcessAbsences.index'));
    }
}
