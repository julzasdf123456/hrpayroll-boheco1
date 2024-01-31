<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpecialDutyDaysRequest;
use App\Http\Requests\UpdateSpecialDutyDaysRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SpecialDutyDaysRepository;
use Illuminate\Http\Request;
use Flash;

class SpecialDutyDaysController extends AppBaseController
{
    /** @var SpecialDutyDaysRepository $specialDutyDaysRepository*/
    private $specialDutyDaysRepository;

    public function __construct(SpecialDutyDaysRepository $specialDutyDaysRepo)
    {
        $this->middleware('auth');
        $this->specialDutyDaysRepository = $specialDutyDaysRepo;
    }

    /**
     * Display a listing of the SpecialDutyDays.
     */
    public function index(Request $request)
    {
        $specialDutyDays = $this->specialDutyDaysRepository->paginate(10);

        return view('special_duty_days.index')
            ->with('specialDutyDays', $specialDutyDays);
    }

    /**
     * Show the form for creating a new SpecialDutyDays.
     */
    public function create()
    {
        return view('special_duty_days.create');
    }

    /**
     * Store a newly created SpecialDutyDays in storage.
     */
    public function store(CreateSpecialDutyDaysRequest $request)
    {
        $input = $request->all();

        $specialDutyDays = $this->specialDutyDaysRepository->create($input);

        Flash::success('Special Duty Days saved successfully.');

        return redirect(route('specialDutyDays.index'));
    }

    /**
     * Display the specified SpecialDutyDays.
     */
    public function show($id)
    {
        $specialDutyDays = $this->specialDutyDaysRepository->find($id);

        if (empty($specialDutyDays)) {
            Flash::error('Special Duty Days not found');

            return redirect(route('specialDutyDays.index'));
        }

        return view('special_duty_days.show')->with('specialDutyDays', $specialDutyDays);
    }

    /**
     * Show the form for editing the specified SpecialDutyDays.
     */
    public function edit($id)
    {
        $specialDutyDays = $this->specialDutyDaysRepository->find($id);

        if (empty($specialDutyDays)) {
            Flash::error('Special Duty Days not found');

            return redirect(route('specialDutyDays.index'));
        }

        return view('special_duty_days.edit')->with('specialDutyDays', $specialDutyDays);
    }

    /**
     * Update the specified SpecialDutyDays in storage.
     */
    public function update($id, UpdateSpecialDutyDaysRequest $request)
    {
        $specialDutyDays = $this->specialDutyDaysRepository->find($id);

        if (empty($specialDutyDays)) {
            Flash::error('Special Duty Days not found');

            return redirect(route('specialDutyDays.index'));
        }

        $specialDutyDays = $this->specialDutyDaysRepository->update($request->all(), $id);

        Flash::success('Special Duty Days updated successfully.');

        return redirect(route('specialDutyDays.index'));
    }

    /**
     * Remove the specified SpecialDutyDays from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $specialDutyDays = $this->specialDutyDaysRepository->find($id);

        if (empty($specialDutyDays)) {
            Flash::error('Special Duty Days not found');

            return redirect(route('specialDutyDays.index'));
        }

        $this->specialDutyDaysRepository->delete($id);

        Flash::success('Special Duty Days deleted successfully.');

        return redirect(route('specialDutyDays.index'));
    }
}
