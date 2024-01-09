<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveAttendanceDatesRequest;
use App\Http\Requests\UpdateLeaveAttendanceDatesRequest;
use App\Repositories\LeaveAttendanceDatesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeaveAttendanceDatesController extends AppBaseController
{
    /** @var  LeaveAttendanceDatesRepository */
    private $leaveAttendanceDatesRepository;

    public function __construct(LeaveAttendanceDatesRepository $leaveAttendanceDatesRepo)
    {
        $this->middleware('auth');
        $this->leaveAttendanceDatesRepository = $leaveAttendanceDatesRepo;
    }

    /**
     * Display a listing of the LeaveAttendanceDates.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveAttendanceDates = $this->leaveAttendanceDatesRepository->all();

        return view('leave_attendance_dates.index')
            ->with('leaveAttendanceDates', $leaveAttendanceDates);
    }

    /**
     * Show the form for creating a new LeaveAttendanceDates.
     *
     * @return Response
     */
    public function create()
    {
        return view('leave_attendance_dates.create');
    }

    /**
     * Store a newly created LeaveAttendanceDates in storage.
     *
     * @param CreateLeaveAttendanceDatesRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveAttendanceDatesRequest $request)
    {
        $input = $request->all();

        $leaveAttendanceDates = $this->leaveAttendanceDatesRepository->create($input);

        Flash::success('Leave Attendance Dates saved successfully.');

        return redirect(route('leaveAttendanceDates.index'));
    }

    /**
     * Display the specified LeaveAttendanceDates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveAttendanceDates = $this->leaveAttendanceDatesRepository->find($id);

        if (empty($leaveAttendanceDates)) {
            Flash::error('Leave Attendance Dates not found');

            return redirect(route('leaveAttendanceDates.index'));
        }

        return view('leave_attendance_dates.show')->with('leaveAttendanceDates', $leaveAttendanceDates);
    }

    /**
     * Show the form for editing the specified LeaveAttendanceDates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveAttendanceDates = $this->leaveAttendanceDatesRepository->find($id);

        if (empty($leaveAttendanceDates)) {
            Flash::error('Leave Attendance Dates not found');

            return redirect(route('leaveAttendanceDates.index'));
        }

        return view('leave_attendance_dates.edit')->with('leaveAttendanceDates', $leaveAttendanceDates);
    }

    /**
     * Update the specified LeaveAttendanceDates in storage.
     *
     * @param int $id
     * @param UpdateLeaveAttendanceDatesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveAttendanceDatesRequest $request)
    {
        $leaveAttendanceDates = $this->leaveAttendanceDatesRepository->find($id);

        if (empty($leaveAttendanceDates)) {
            Flash::error('Leave Attendance Dates not found');

            return redirect(route('leaveAttendanceDates.index'));
        }

        $leaveAttendanceDates = $this->leaveAttendanceDatesRepository->update($request->all(), $id);

        Flash::success('Leave Attendance Dates updated successfully.');

        return redirect(route('leaveAttendanceDates.index'));
    }

    /**
     * Remove the specified LeaveAttendanceDates from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveAttendanceDates = $this->leaveAttendanceDatesRepository->find($id);

        if (empty($leaveAttendanceDates)) {
            Flash::error('Leave Attendance Dates not found');

            return redirect(route('leaveAttendanceDates.index'));
        }

        $this->leaveAttendanceDatesRepository->delete($id);

        Flash::success('Leave Attendance Dates deleted successfully.');

        return redirect(route('leaveAttendanceDates.index'));
    }
}
