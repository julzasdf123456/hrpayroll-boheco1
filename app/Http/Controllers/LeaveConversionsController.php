<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveConversionsRequest;
use App\Http\Requests\UpdateLeaveConversionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LeaveConversionsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Employees;
use App\Models\IDGenerator;
use App\Models\LeaveConversions;
use Flash;

class LeaveConversionsController extends AppBaseController
{
    /** @var LeaveConversionsRepository $leaveConversionsRepository*/
    private $leaveConversionsRepository;

    public function __construct(LeaveConversionsRepository $leaveConversionsRepo)
    {
        $this->middleware('auth');
        $this->leaveConversionsRepository = $leaveConversionsRepo;
    }

    /**
     * Display a listing of the LeaveConversions.
     */
    public function index(Request $request)
    {
        $leaveConversions = $this->leaveConversionsRepository->paginate(10);

        return view('leave_conversions.index')
            ->with('leaveConversions', $leaveConversions);
    }

    /**
     * Show the form for creating a new LeaveConversions.
     */
    public function create()
    {
        if (Auth::user()->hasPermissionTo('create leave conversion for others')) {
            $employees = Employees::orderBy('FirstName')->get();
        } else {
            $employees = Employees::where('id', Auth::user()->employee_id)->orderBy('FirstName')->get();
        }
        return view('leave_conversions.create', [
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created LeaveConversions in storage.
     */
    public function store(CreateLeaveConversionsRequest $request)
    {
        $input = $request->all();

        $leaveConversions = $this->leaveConversionsRepository->create($input);

        Flash::success('Leave Conversions saved successfully.');

        return redirect(route('leaveConversions.index'));
    }

    /**
     * Display the specified LeaveConversions.
     */
    public function show($id)
    {
        $leaveConversions = $this->leaveConversionsRepository->find($id);

        if (empty($leaveConversions)) {
            Flash::error('Leave Conversions not found');

            return redirect(route('leaveConversions.index'));
        }

        return view('leave_conversions.show')->with('leaveConversions', $leaveConversions);
    }

    /**
     * Show the form for editing the specified LeaveConversions.
     */
    public function edit($id)
    {
        $leaveConversions = $this->leaveConversionsRepository->find($id);

        if (empty($leaveConversions)) {
            Flash::error('Leave Conversions not found');

            return redirect(route('leaveConversions.index'));
        }

        return view('leave_conversions.edit')->with('leaveConversions', $leaveConversions);
    }

    /**
     * Update the specified LeaveConversions in storage.
     */
    public function update($id, UpdateLeaveConversionsRequest $request)
    {
        $leaveConversions = $this->leaveConversionsRepository->find($id);

        if (empty($leaveConversions)) {
            Flash::error('Leave Conversions not found');

            return redirect(route('leaveConversions.index'));
        }

        $leaveConversions = $this->leaveConversionsRepository->update($request->all(), $id);

        Flash::success('Leave Conversions updated successfully.');

        return redirect(route('leaveConversions.index'));
    }

    /**
     * Remove the specified LeaveConversions from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $leaveConversions = $this->leaveConversionsRepository->find($id);

        if (empty($leaveConversions)) {
            Flash::error('Leave Conversions not found');

            return redirect(route('leaveConversions.index'));
        }

        $this->leaveConversionsRepository->delete($id);

        Flash::success('Leave Conversions deleted successfully.');

        return redirect(route('leaveConversions.index'));
    }
}
