<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Repositories\UsersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Employees;
use App\Models\Towns;
use App\Models\EmployeesDesignations;
use App\Models\Rankings;
use App\Models\RankingRepository;
use App\Models\EducationalAttainment;
use App\Models\LeaveApplications;
use App\Models\LeaveDays;
use App\Models\Users;
use App\Models\ProfessionalIDs;
use App\Models\LeaveAttendanceDates;
use App\Models\EmployeeImages;
use App\Models\IDGenerator;
use App\Models\AttendanceData;
use App\Models\EmployeePayrollSchedules;
use App\Models\PayrollSchedules;
use App\Models\LeaveBalances;
use App\Models\LeaveBalanceDetails;
use App\Models\OffsetApplications;
use App\Models\Overtimes;
use App\Models\LeaveImageAttachments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class UsersController extends AppBaseController
{
    /** @var  UsersRepository */
    private $usersRepository;

    public function __construct(UsersRepository $usersRepo)
    {
        $this->usersRepository = $usersRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the Users.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->usersRepository->all();

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new Users.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created Users in storage.
     *
     * @param CreateUsersRequest $request
     *
     * @return Response
     */
    public function store(CreateUsersRequest $request)
    {
        $input = $request->all();

        $users = $this->usersRepository->create($input);

        Flash::success('Users saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified Users.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $users = $this->usersRepository->find($id);

        $user = User::find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $permissions = $user->getAllPermissions();

        return view('users.show', ['users' => $users, 'permissions' => $permissions]);
    }

    /**
     * Show the form for editing the specified Users.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('users', $users);
    }

    /**
     * Update the specified Users in storage.
     *
     * @param int $id
     * @param UpdateUsersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUsersRequest $request)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $users = $this->usersRepository->update($request->all(), $id);

        Flash::success('Users updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified Users from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $this->usersRepository->delete($id);

        Flash::success('Users deleted successfully.');

        return redirect(route('users.index'));
    }

    public function addRoles($id) {
        $users = User::find($id);

        $roles = Role::all();

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('/users/add_roles', ['users' => $users, 'roles' => $roles]);
    }

    public function createRoles(Request $request) {
        $user = User::find($request->userId);

        $user->syncPermissions($request->input('permissions', []));

        return redirect('users/' . $request->userId);
    }

    public function switchColorModes(Request $request) {
        $id = $request['id'];
        $color = $request['Color'];

        $user = User::find($id);

        if ($user != null) {
            $user->ColorProfile = $color;
            $user->save();
        }

        return response()->json($user, 200);
    }

    public function myAccountIndex($employeeId) {
        $employee = DB::table('PayrollExpandedDetails')
                ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->whereRaw("Employees.id='" . $employeeId . "'")
                ->select(
                    'Employees.*',
                    'Positions.Position'
                )
                ->first();

        if ($employee != null) {
            $leaveBalances = LeaveBalances::where('EmployeeId', $employeeId)->first();

            return view('/my_account/index', [
                'employee' => $employee,
                'leaveBalances' => $leaveBalances,
            ]);
        } else {
            return abort(404, 'Employee not found!');
        }
    }

    public function leaveCredits($employeeId) {
        $leaveBalances = LeaveBalances::where('EmployeeId', $employeeId)->first();

        $employee = DB::table('PayrollExpandedDetails')
                ->leftJoin('Employees', 'PayrollExpandedDetails.EmployeeId', '=', 'Employees.id')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->whereRaw("Employees.id='" . $employeeId . "'")
                ->select(
                    'Employees.*',
                    'Positions.Position'
                )
                ->first();

        $leaveBalanceDetails = LeaveBalanceDetails::where('EmployeeId', $employeeId)->orderByDesc('created_at')->get();

        return view('/my_account/leave_credits', [
            'employees' => $employee,
            'leaveBalances' => $leaveBalances,
            'leaveBalanceDetails' => $leaveBalanceDetails,
        ]);
    }

    public function viewLeave($id) {
        $leaveApplications = LeaveApplications::find($id);
        $leaveSignatories = DB::table('LeaveSignatories')
            ->leftJoin('users', 'LeaveSignatories.EmployeeId', '=', 'users.id')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
            ->select(
                'LeaveSignatories.id', 
                'LeaveSignatories.EmployeeId', 
                'LeaveSignatories.Status', 
                'Employees.FirstName', 
                'Employees.LastName', 
                'Employees.MiddleName', 
                'Employees.Suffix', 
                'Positions.Position', 
                'LeaveSignatories.updated_at', 
                'LeaveSignatories.Notes')
            ->where('LeaveSignatories.LeaveId', $id)
            ->whereRaw("(LeaveSignatories.Status IS NULL OR LeaveSignatories.Status NOT IN('REMOVED'))")
            ->orderBy('LeaveSignatories.Rank')
            ->get();

        if (empty($leaveApplications)) {
            Flash::error('Leave Applications not found');

            return redirect(route('leaveApplications.index'));
        }

        $leaveDays = LeaveDays::where('LeaveId', $id)->orderBy('LeaveDate')->get();
        $leaveImgs = LeaveImageAttachments::where('LeaveId', $id)->get();

        return view('/my_account/view_leave', [
            'leaveApplication' => $leaveApplications,
            'leaveSignatories' => $leaveSignatories,
            'leaveDays' => $leaveDays,
            'leaveImgs' => $leaveImgs,
        ]);
    }

    public function payrollDashboard() {
        $employeeId = Auth::user()->employee_id;

        return view('/my_account/payroll_dashboard', [

        ]);
    }

    public function payrollDetailedView() {
        return view('/my_account/payroll_detailed_view', [

        ]);
    }

    public function attachBohecoAccount() {
        return view('/my_account/payroll_attach_boheco_account', [

        ]);
    }

    public function searchBohecoAccounts(Request $request) {
        $search = $request['Search'];

        if ($search != null) {
            $data = DB::connection('sqlsrv_billing')
                ->table('AccountMaster')
                ->whereRaw("AccountNumber LIKE '%" . $search . "%' OR ConsumerName LIKE '%" . $search . "%' OR MeterNumber LIKE '%" . $search . "%'")
                ->orderBy('AccountNumber')
                ->paginate(10);
        } else {
            $data = [];
        }

        return response()->json($data, 200);
    }
}
