<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveUsersForOthersRequest;
use App\Http\Requests\UpdateLeaveUsersForOthersRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LeaveUsersForOthersRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LeaveUsersForOthers;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class LeaveUsersForOthersController extends AppBaseController
{
    /** @var LeaveUsersForOthersRepository $leaveUsersForOthersRepository*/
    private $leaveUsersForOthersRepository;

    public function __construct(LeaveUsersForOthersRepository $leaveUsersForOthersRepo)
    {
        $this->middleware('auth');
        $this->leaveUsersForOthersRepository = $leaveUsersForOthersRepo;
    }

    /**
     * Display a listing of the LeaveUsersForOthers.
     */
    public function index(Request $request)
    {
        $leaveUsersForOthers = $this->leaveUsersForOthersRepository->paginate(10);

        return view('leave_users_for_others.index')
            ->with('leaveUsersForOthers', $leaveUsersForOthers);
    }

    /**
     * Show the form for creating a new LeaveUsersForOthers.
     */
    public function create()
    {
        return view('leave_users_for_others.create');
    }

    /**
     * Store a newly created LeaveUsersForOthers in storage.
     */
    public function store(CreateLeaveUsersForOthersRequest $request)
    {
        $input = $request->all();

        $leaveUsersForOthers = $this->leaveUsersForOthersRepository->create($input);

        Flash::success('Leave Users For Others saved successfully.');

        return redirect(route('leaveUsersForOthers.index'));
    }

    /**
     * Display the specified LeaveUsersForOthers.
     */
    public function show($id)
    {
        $leaveUsersForOthers = $this->leaveUsersForOthersRepository->find($id);

        if (empty($leaveUsersForOthers)) {
            Flash::error('Leave Users For Others not found');

            return redirect(route('leaveUsersForOthers.index'));
        }

        return view('leave_users_for_others.show')->with('leaveUsersForOthers', $leaveUsersForOthers);
    }

    /**
     * Show the form for editing the specified LeaveUsersForOthers.
     */
    public function edit($id)
    {
        $leaveUsersForOthers = $this->leaveUsersForOthersRepository->find($id);

        if (empty($leaveUsersForOthers)) {
            Flash::error('Leave Users For Others not found');

            return redirect(route('leaveUsersForOthers.index'));
        }

        return view('leave_users_for_others.edit')->with('leaveUsersForOthers', $leaveUsersForOthers);
    }

    /**
     * Update the specified LeaveUsersForOthers in storage.
     */
    public function update($id, UpdateLeaveUsersForOthersRequest $request)
    {
        $leaveUsersForOthers = $this->leaveUsersForOthersRepository->find($id);

        if (empty($leaveUsersForOthers)) {
            Flash::error('Leave Users For Others not found');

            return redirect(route('leaveUsersForOthers.index'));
        }

        $leaveUsersForOthers = $this->leaveUsersForOthersRepository->update($request->all(), $id);

        Flash::success('Leave Users For Others updated successfully.');

        return redirect(route('leaveUsersForOthers.index'));
    }

    /**
     * Remove the specified LeaveUsersForOthers from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $leaveUsersForOthers = $this->leaveUsersForOthersRepository->find($id);

        if (empty($leaveUsersForOthers)) {
            Flash::error('Leave Users For Others not found');

            return redirect(route('leaveUsersForOthers.index'));
        }

        $this->leaveUsersForOthersRepository->delete($id);

        Flash::success('Leave Users For Others deleted successfully.');

        return redirect(route('leaveUsersForOthers.index'));
    }

    public function configure($userid) {
        $user = User::find($userid);

        if ($user != null) {
            $permissions = $user->permissions;
            $allowedEmployees = LeaveUsersForOthers::where('LeaveCreator', $user->employee_id)
                ->pluck('EmployeeId')
                ->toArray();
        } else {
            $permissions = null;
            $allowedEmployees = null;
        }

        $isd = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->whereRaw("Positions.Department='ISD' AND (Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Resigned', 'Retired'))")
            ->select('Employees.*')
            ->orderBy('LastName')
            ->get();

        $esd = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->whereRaw("Positions.Department='ESD' AND (Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Resigned', 'Retired'))")
            ->select('Employees.*')
            ->orderBy('LastName')
            ->get();

        $ogm = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->whereRaw("Positions.Department='OGM' AND (Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Resigned', 'Retired'))")
            ->select('Employees.*')
            ->orderBy('LastName')
            ->get();

        $osd = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->whereRaw("Positions.Department='OSD' AND (Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Resigned', 'Retired'))")
            ->select('Employees.*')
            ->orderBy('LastName')
            ->get();

        $pgd = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->whereRaw("Positions.Department='PGD' AND (Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Resigned', 'Retired'))")
            ->select('Employees.*')
            ->orderBy('LastName')
            ->get();

        $seead = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->whereRaw("Positions.Department='SEEAD' AND (Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Resigned', 'Retired'))")
            ->select('Employees.*')
            ->orderBy('LastName')
            ->get();

        return view('/leave_users_for_others/configure', [
            'user' => $user,
            'permissions' => $permissions,
            'allowedEmployees' => $allowedEmployees,
            'isd' => $isd,
            'esd' => $esd,
            'ogm' => $ogm,
            'osd' => $osd,
            'pgd' => $pgd,
            'seead' => $seead,
        ]);
    }

    public function save(Request $request) {
        $employeeIds = $request['EmployeeIds'];
        $creatorEmployeeId = $request['CreatorId'];

        // delete first existing data sets
        LeaveUsersForOthers::where('LeaveCreator', $creatorEmployeeId)
            ->delete();

        // insert
        if ($employeeIds != null) {
            foreach($employeeIds as $item) {
                $lufo = new LeaveUsersForOthers;
                $lufo->id = IDGenerator::generateIDandRandString();
                $lufo->LeaveCreator = $creatorEmployeeId;
                $lufo->EmployeeId = $item;
                $lufo->save();
            }
        }
        
        return response()->json($employeeIds, 200);
    }
}
