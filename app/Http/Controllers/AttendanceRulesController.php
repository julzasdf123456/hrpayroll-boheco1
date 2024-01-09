<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAttendanceRulesRequest;
use App\Http\Requests\UpdateAttendanceRulesRequest;
use App\Repositories\AttendanceRulesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AttendanceRulesController extends AppBaseController
{
    /** @var  AttendanceRulesRepository */
    private $attendanceRulesRepository;

    public function __construct(AttendanceRulesRepository $attendanceRulesRepo)
    {
        $this->middleware('auth');
        $this->attendanceRulesRepository = $attendanceRulesRepo;
    }

    /**
     * Display a listing of the AttendanceRules.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $attendanceRules = $this->attendanceRulesRepository->all();

        return view('attendance_rules.index')
            ->with('attendanceRules', $attendanceRules);
    }

    /**
     * Show the form for creating a new AttendanceRules.
     *
     * @return Response
     */
    public function create()
    {
        return view('attendance_rules.create');
    }

    /**
     * Store a newly created AttendanceRules in storage.
     *
     * @param CreateAttendanceRulesRequest $request
     *
     * @return Response
     */
    public function store(CreateAttendanceRulesRequest $request)
    {
        $input = $request->all();

        $attendanceRules = $this->attendanceRulesRepository->create($input);

        Flash::success('Attendance Rules saved successfully.');

        return redirect(route('attendanceRules.index'));
    }

    /**
     * Display the specified AttendanceRules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $attendanceRules = $this->attendanceRulesRepository->find($id);

        if (empty($attendanceRules)) {
            Flash::error('Attendance Rules not found');

            return redirect(route('attendanceRules.index'));
        }

        return view('attendance_rules.show')->with('attendanceRules', $attendanceRules);
    }

    /**
     * Show the form for editing the specified AttendanceRules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $attendanceRules = $this->attendanceRulesRepository->find($id);

        if (empty($attendanceRules)) {
            Flash::error('Attendance Rules not found');

            return redirect(route('attendanceRules.index'));
        }

        return view('attendance_rules.edit')->with('attendanceRules', $attendanceRules);
    }

    /**
     * Update the specified AttendanceRules in storage.
     *
     * @param int $id
     * @param UpdateAttendanceRulesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAttendanceRulesRequest $request)
    {
        $attendanceRules = $this->attendanceRulesRepository->find($id);

        if (empty($attendanceRules)) {
            Flash::error('Attendance Rules not found');

            return redirect(route('attendanceRules.index'));
        }

        $attendanceRules = $this->attendanceRulesRepository->update($request->all(), $id);

        Flash::success('Attendance Rules updated successfully.');

        return redirect(route('attendanceRules.index'));
    }

    /**
     * Remove the specified AttendanceRules from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $attendanceRules = $this->attendanceRulesRepository->find($id);

        if (empty($attendanceRules)) {
            Flash::error('Attendance Rules not found');

            return redirect(route('attendanceRules.index'));
        }

        $this->attendanceRulesRepository->delete($id);

        Flash::success('Attendance Rules deleted successfully.');

        return redirect(route('attendanceRules.index'));
    }
}
