<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDayOffSchedulesRequest;
use App\Http\Requests\UpdateDayOffSchedulesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DayOffSchedulesRepository;
use Illuminate\Http\Request;
use App\Models\DayOffSchedules;
use Flash;

class DayOffSchedulesController extends AppBaseController
{
    /** @var DayOffSchedulesRepository $dayOffSchedulesRepository*/
    private $dayOffSchedulesRepository;

    public function __construct(DayOffSchedulesRepository $dayOffSchedulesRepo)
    {
        $this->middleware('auth');
        $this->dayOffSchedulesRepository = $dayOffSchedulesRepo;
    }

    /**
     * Display a listing of the DayOffSchedules.
     */
    public function index(Request $request)
    {
        $dayOffSchedules = DayOffSchedules::orderBy('Days')->paginate(10);

        return view('day_off_schedules.index')
            ->with('dayOffSchedules', $dayOffSchedules);
    }

    /**
     * Show the form for creating a new DayOffSchedules.
     */
    public function create()
    {
        return view('day_off_schedules.create');
    }

    /**
     * Store a newly created DayOffSchedules in storage.
     */
    public function store(CreateDayOffSchedulesRequest $request)
    {
        $input = $request->all();

        $dayOffSchedules = $this->dayOffSchedulesRepository->create($input);

        Flash::success('Day Off Schedules saved successfully.');

        return redirect(route('dayOffSchedules.index'));
    }

    /**
     * Display the specified DayOffSchedules.
     */
    public function show($id)
    {
        $dayOffSchedules = $this->dayOffSchedulesRepository->find($id);

        if (empty($dayOffSchedules)) {
            Flash::error('Day Off Schedules not found');

            return redirect(route('dayOffSchedules.index'));
        }

        return view('day_off_schedules.show')->with('dayOffSchedules', $dayOffSchedules);
    }

    /**
     * Show the form for editing the specified DayOffSchedules.
     */
    public function edit($id)
    {
        $dayOffSchedules = $this->dayOffSchedulesRepository->find($id);

        if (empty($dayOffSchedules)) {
            Flash::error('Day Off Schedules not found');

            return redirect(route('dayOffSchedules.index'));
        }

        return view('day_off_schedules.edit')->with('dayOffSchedules', $dayOffSchedules);
    }

    /**
     * Update the specified DayOffSchedules in storage.
     */
    public function update($id, UpdateDayOffSchedulesRequest $request)
    {
        $dayOffSchedules = $this->dayOffSchedulesRepository->find($id);

        if (empty($dayOffSchedules)) {
            Flash::error('Day Off Schedules not found');

            return redirect(route('dayOffSchedules.index'));
        }

        $dayOffSchedules = $this->dayOffSchedulesRepository->update($request->all(), $id);

        Flash::success('Day Off Schedules updated successfully.');

        return redirect(route('dayOffSchedules.index'));
    }

    /**
     * Remove the specified DayOffSchedules from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $dayOffSchedules = $this->dayOffSchedulesRepository->find($id);

        if (empty($dayOffSchedules)) {
            Flash::error('Day Off Schedules not found');

            return redirect(route('dayOffSchedules.index'));
        }

        $this->dayOffSchedulesRepository->delete($id);

        Flash::success('Day Off Schedules deleted successfully.');

        return redirect(route('dayOffSchedules.index'));
    }
}
