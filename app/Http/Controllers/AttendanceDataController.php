<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAttendanceDataRequest;
use App\Http\Requests\UpdateAttendanceDataRequest;
use App\Repositories\AttendanceDataRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AttendanceDataController extends AppBaseController
{
    /** @var  AttendanceDataRepository */
    private $attendanceDataRepository;

    public function __construct(AttendanceDataRepository $attendanceDataRepo)
    {
        $this->middleware('auth');
        $this->attendanceDataRepository = $attendanceDataRepo;
    }

    /**
     * Display a listing of the AttendanceData.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $attendanceDatas = $this->attendanceDataRepository->all();

        return view('attendance_datas.index')
            ->with('attendanceDatas', $attendanceDatas);
    }

    /**
     * Show the form for creating a new AttendanceData.
     *
     * @return Response
     */
    public function create()
    {
        return view('attendance_datas.create');
    }

    /**
     * Store a newly created AttendanceData in storage.
     *
     * @param CreateAttendanceDataRequest $request
     *
     * @return Response
     */
    public function store(CreateAttendanceDataRequest $request)
    {
        $input = $request->all();

        $attendanceData = $this->attendanceDataRepository->create($input);

        Flash::success('Attendance Data saved successfully.');

        return redirect(route('attendanceDatas.index'));
    }

    /**
     * Display the specified AttendanceData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $attendanceData = $this->attendanceDataRepository->find($id);

        if (empty($attendanceData)) {
            Flash::error('Attendance Data not found');

            return redirect(route('attendanceDatas.index'));
        }

        return view('attendance_datas.show')->with('attendanceData', $attendanceData);
    }

    /**
     * Show the form for editing the specified AttendanceData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $attendanceData = $this->attendanceDataRepository->find($id);

        if (empty($attendanceData)) {
            Flash::error('Attendance Data not found');

            return redirect(route('attendanceDatas.index'));
        }

        return view('attendance_datas.edit')->with('attendanceData', $attendanceData);
    }

    /**
     * Update the specified AttendanceData in storage.
     *
     * @param int $id
     * @param UpdateAttendanceDataRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAttendanceDataRequest $request)
    {
        $attendanceData = $this->attendanceDataRepository->find($id);

        if (empty($attendanceData)) {
            Flash::error('Attendance Data not found');

            return redirect(route('attendanceDatas.index'));
        }

        $attendanceData = $this->attendanceDataRepository->update($request->all(), $id);

        Flash::success('Attendance Data updated successfully.');

        return redirect(route('attendanceDatas.index'));
    }

    /**
     * Remove the specified AttendanceData from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $attendanceData = $this->attendanceDataRepository->find($id);

        if (empty($attendanceData)) {
            Flash::error('Attendance Data not found');

            return redirect(route('attendanceDatas.index'));
        }

        $this->attendanceDataRepository->delete($id);

        Flash::success('Attendance Data deleted successfully.');

        return redirect(route('attendanceDatas.index'));
    }
}
