<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOvertimeSignatoriesRequest;
use App\Http\Requests\UpdateOvertimeSignatoriesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\OvertimeSignatoriesRepository;
use Illuminate\Http\Request;
use Flash;

class OvertimeSignatoriesController extends AppBaseController
{
    /** @var OvertimeSignatoriesRepository $overtimeSignatoriesRepository*/
    private $overtimeSignatoriesRepository;

    public function __construct(OvertimeSignatoriesRepository $overtimeSignatoriesRepo)
    {
        $this->middleware('auth');
        $this->overtimeSignatoriesRepository = $overtimeSignatoriesRepo;
    }

    /**
     * Display a listing of the OvertimeSignatories.
     */
    public function index(Request $request)
    {
        $overtimeSignatories = $this->overtimeSignatoriesRepository->paginate(10);

        return view('overtime_signatories.index')
            ->with('overtimeSignatories', $overtimeSignatories);
    }

    /**
     * Show the form for creating a new OvertimeSignatories.
     */
    public function create()
    {
        return view('overtime_signatories.create');
    }

    /**
     * Store a newly created OvertimeSignatories in storage.
     */
    public function store(CreateOvertimeSignatoriesRequest $request)
    {
        $input = $request->all();

        $overtimeSignatories = $this->overtimeSignatoriesRepository->create($input);

        Flash::success('Overtime Signatories saved successfully.');

        return redirect(route('overtimeSignatories.index'));
    }

    /**
     * Display the specified OvertimeSignatories.
     */
    public function show($id)
    {
        $overtimeSignatories = $this->overtimeSignatoriesRepository->find($id);

        if (empty($overtimeSignatories)) {
            Flash::error('Overtime Signatories not found');

            return redirect(route('overtimeSignatories.index'));
        }

        return view('overtime_signatories.show')->with('overtimeSignatories', $overtimeSignatories);
    }

    /**
     * Show the form for editing the specified OvertimeSignatories.
     */
    public function edit($id)
    {
        $overtimeSignatories = $this->overtimeSignatoriesRepository->find($id);

        if (empty($overtimeSignatories)) {
            Flash::error('Overtime Signatories not found');

            return redirect(route('overtimeSignatories.index'));
        }

        return view('overtime_signatories.edit')->with('overtimeSignatories', $overtimeSignatories);
    }

    /**
     * Update the specified OvertimeSignatories in storage.
     */
    public function update($id, UpdateOvertimeSignatoriesRequest $request)
    {
        $overtimeSignatories = $this->overtimeSignatoriesRepository->find($id);

        if (empty($overtimeSignatories)) {
            Flash::error('Overtime Signatories not found');

            return redirect(route('overtimeSignatories.index'));
        }

        $overtimeSignatories = $this->overtimeSignatoriesRepository->update($request->all(), $id);

        Flash::success('Overtime Signatories updated successfully.');

        return redirect(route('overtimeSignatories.index'));
    }

    /**
     * Remove the specified OvertimeSignatories from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $overtimeSignatories = $this->overtimeSignatoriesRepository->find($id);

        if (empty($overtimeSignatories)) {
            Flash::error('Overtime Signatories not found');

            return redirect(route('overtimeSignatories.index'));
        }

        $this->overtimeSignatoriesRepository->delete($id);

        Flash::success('Overtime Signatories deleted successfully.');

        return redirect(route('overtimeSignatories.index'));
    }
}
