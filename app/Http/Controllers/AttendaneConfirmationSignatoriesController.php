<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAttendaneConfirmationSignatoriesRequest;
use App\Http\Requests\UpdateAttendaneConfirmationSignatoriesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AttendaneConfirmationSignatoriesRepository;
use Illuminate\Http\Request;
use Flash;

class AttendaneConfirmationSignatoriesController extends AppBaseController
{
    /** @var AttendaneConfirmationSignatoriesRepository $attendaneConfirmationSignatoriesRepository*/
    private $attendaneConfirmationSignatoriesRepository;

    public function __construct(AttendaneConfirmationSignatoriesRepository $attendaneConfirmationSignatoriesRepo)
    {
        $this->middleware('auth');
        $this->attendaneConfirmationSignatoriesRepository = $attendaneConfirmationSignatoriesRepo;
    }

    /**
     * Display a listing of the AttendaneConfirmationSignatories.
     */
    public function index(Request $request)
    {
        $attendaneConfirmationSignatories = $this->attendaneConfirmationSignatoriesRepository->paginate(10);

        return view('attendane_confirmation_signatories.index')
            ->with('attendaneConfirmationSignatories', $attendaneConfirmationSignatories);
    }

    /**
     * Show the form for creating a new AttendaneConfirmationSignatories.
     */
    public function create()
    {
        return view('attendane_confirmation_signatories.create');
    }

    /**
     * Store a newly created AttendaneConfirmationSignatories in storage.
     */
    public function store(CreateAttendaneConfirmationSignatoriesRequest $request)
    {
        $input = $request->all();

        $attendaneConfirmationSignatories = $this->attendaneConfirmationSignatoriesRepository->create($input);

        Flash::success('Attendane Confirmation Signatories saved successfully.');

        return redirect(route('attendaneConfirmationSignatories.index'));
    }

    /**
     * Display the specified AttendaneConfirmationSignatories.
     */
    public function show($id)
    {
        $attendaneConfirmationSignatories = $this->attendaneConfirmationSignatoriesRepository->find($id);

        if (empty($attendaneConfirmationSignatories)) {
            Flash::error('Attendane Confirmation Signatories not found');

            return redirect(route('attendaneConfirmationSignatories.index'));
        }

        return view('attendane_confirmation_signatories.show')->with('attendaneConfirmationSignatories', $attendaneConfirmationSignatories);
    }

    /**
     * Show the form for editing the specified AttendaneConfirmationSignatories.
     */
    public function edit($id)
    {
        $attendaneConfirmationSignatories = $this->attendaneConfirmationSignatoriesRepository->find($id);

        if (empty($attendaneConfirmationSignatories)) {
            Flash::error('Attendane Confirmation Signatories not found');

            return redirect(route('attendaneConfirmationSignatories.index'));
        }

        return view('attendane_confirmation_signatories.edit')->with('attendaneConfirmationSignatories', $attendaneConfirmationSignatories);
    }

    /**
     * Update the specified AttendaneConfirmationSignatories in storage.
     */
    public function update($id, UpdateAttendaneConfirmationSignatoriesRequest $request)
    {
        $attendaneConfirmationSignatories = $this->attendaneConfirmationSignatoriesRepository->find($id);

        if (empty($attendaneConfirmationSignatories)) {
            Flash::error('Attendane Confirmation Signatories not found');

            return redirect(route('attendaneConfirmationSignatories.index'));
        }

        $attendaneConfirmationSignatories = $this->attendaneConfirmationSignatoriesRepository->update($request->all(), $id);

        Flash::success('Attendane Confirmation Signatories updated successfully.');

        return redirect(route('attendaneConfirmationSignatories.index'));
    }

    /**
     * Remove the specified AttendaneConfirmationSignatories from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $attendaneConfirmationSignatories = $this->attendaneConfirmationSignatoriesRepository->find($id);

        if (empty($attendaneConfirmationSignatories)) {
            Flash::error('Attendane Confirmation Signatories not found');

            return redirect(route('attendaneConfirmationSignatories.index'));
        }

        $this->attendaneConfirmationSignatoriesRepository->delete($id);

        Flash::success('Attendane Confirmation Signatories deleted successfully.');

        return redirect(route('attendaneConfirmationSignatories.index'));
    }
}
