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
use App\Models\LeaveBalances;
use App\Models\LeaveBalanceDetails;
use App\Models\UserFootprints;
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
            $employees = DB::table('Employees')
                ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                ->select(
                    'Employees.*',
                    'Vacation',
                    'Sick'
                )
                ->orderBy('LastName')
                ->get();
        } else {
            $employees = DB::table('Employees')
                ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                ->whereRaw("Employees.id='" . Auth::user()->employee_id . "'")
                ->select(
                    'Employees.*',
                    'Vacation',
                    'Sick'
                )
                ->orderBy('LastName')
                ->get();
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

        UserFootprints::logSource('Deleted Conversion Log', 
                        Auth::user()->name . ' deleted a leave conversion log for employee ID ' . $leaveConversions->EmployeeId . ' with ' . $leaveConversions->VacationDays . ' Vacation days and ' . $leaveConversions->SickDays . ' Sick days.',
                        $id);

        $this->leaveConversionsRepository->delete($id);

        // Flash::success('Leave Conversions deleted successfully.');

        // return redirect(route('leaveConversions.index'));

        return response()->json('ok', 200);
    }

    public function requestMultiple(Request $request) {
        $data = $request['Requests'];
        $status = $request['Status'];

        foreach ($data as $item) {
            $employee = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select('Employees.FirstName',
                        'Employees.MiddleName',
                        'Employees.LastName',
                        'Employees.Suffix',
                        'Employees.id',
                        'Positions.BasicSalary AS SalaryAmount',
                        'Positions.Level',
                        'Positions.Position',
                        'EmployeesDesignations.Status'
                )
                ->whereRaw("Employees.id='" . $item['EmployeeId'] . "'")
                ->first();

            if ($employee != null) {
                $dailyRate = Employees::getDailyRate($employee->SalaryAmount);
                $vacation = $item['Vacation'] != null ? intval($item['Vacation']) : 0;
                $sick = $item['Sick'] != null ? intval($item['Sick']) : 0;
                $vacationAmount = round($dailyRate * $vacation, 2);
                $sickAmount = round($dailyRate * $sick);

                $id = IDGenerator::generateIDandRandString();
                $leaveConversion = new LeaveConversions;
                $leaveConversion->id = $id;
                $leaveConversion->EmployeeId = $employee->id;
                $leaveConversion->VacationDays = $vacation;
                $leaveConversion->SickDays = $sick;
                $leaveConversion->VacationAmount = $vacationAmount;
                $leaveConversion->SickAmount = $sickAmount;
                $leaveConversion->Year = date('Y');

                if (isset($status)) {
                    $leaveConversion->Status = $status;

                    if ($status === 'Approved') {
                        // UPDATE LeaveBalances
                        $leaveBalances = LeaveBalances::where('EmployeeId', $employee->id)->first();
                        if ($leaveBalances != null) {
                            $leaveBalances->Vacation = $leaveBalances->Vacation != null ? ($leaveBalances->Vacation - ($leaveConversion->VacationDays * 8 * 60)) : 0;
                            $leaveBalances->Sick = $leaveBalances->Sick != null ? ($leaveBalances->Sick - ($leaveConversion->SickDays * 8 * 60)) : 0;
                            $leaveBalances->save();

                            // VACATION
                            if ($leaveConversion->VacationDays > 0) {
                                LeaveBalanceDetails::leaveLog(
                                    $leaveBalances->EmployeeId,
                                    'DEDUCT',
                                    $leaveConversion->VacationDays,
                                    'Deducted ' . $leaveConversion->VacationDays . ' days from Vacation Leave for cash conversion',
                                    'VACATION'
                                );
                            }
                            
                            // SICK
                            if ($leaveConversion->SickDays > 0) {
                                LeaveBalanceDetails::leaveLog(
                                    $leaveBalances->EmployeeId,
                                    'DEDUCT',
                                    $leaveConversion->SickDays,
                                    'Deducted ' . $leaveConversion->SickDays . ' days from Sick Leave for cash conversion',
                                    'SICK'
                                );
                            }
                        }

                        UserFootprints::logSource('Leave Conversion Request Approved', 
                                'Leave credit to cash conversion approved and posted.',
                                $id);
                    }
                } else {
                    $leaveConversion->Status = 'Filed';

                    UserFootprints::logSource('Requested Leave Conversion', 
                        'Requested for leave to cash conversion for ' . Employees::getMergeNameFormal($employee) . ' with ' . $vacation . ' days for Vacation and ' . $sick . ' days for Sick Leave.',
                        $id);
                }
                
                $leaveConversion->UserId = Auth::id();
                $leaveConversion->DateFiled = $item['DateFiled'];
                $leaveConversion->save();
            }
        }

        return response()->json('ok', 200);
    }

    public function myApprovals(Request $request) {
        $checkEmployee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Employees.id='" . Auth::user()->employee_id . "'")
            ->select(
                'Positions.Position'
            )
            ->first();

        if ($checkEmployee != null) {
            if ($checkEmployee->Position == 'Head, Human Resource Development Section') {
                $data = DB::table('LeaveConversions')
                    ->leftJoin('Employees', 'LeaveConversions.EmployeeId', '=', 'Employees.id')
                    ->leftJoin('users', 'LeaveConversions.UserId', '=', 'users.id')
                    ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                    ->whereRaw("LeaveConversions.Status='Filed'")
                    ->select(
                        'FirstName',
                        'MiddleName',
                        'LastName',
                        'Suffix',
                        'LeaveConversions.*',
                        'users.name',
                        'Vacation',
                        'Sick'
                    )
                    ->get();

                return view('/leave_conversions/my_approvals', [
                    'data' => $data,
                ]);
            } elseif ($checkEmployee->Position == 'Manager, I S D') {
                $data = DB::table('LeaveConversions')
                    ->leftJoin('Employees', 'LeaveConversions.EmployeeId', '=', 'Employees.id')
                    ->leftJoin('users', 'LeaveConversions.UserId', '=', 'users.id')
                    ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                    ->whereRaw("LeaveConversions.Status='Approved by HR'")
                    ->select(
                        'FirstName',
                        'MiddleName',
                        'LastName',
                        'Suffix',
                        'LeaveConversions.*',
                        'users.name',
                        'Vacation',
                        'Sick'
                    )
                    ->get();

                return view('/leave_conversions/my_approvals', [
                    'data' => $data,
                ]);
            } else {
                return abort(403, 'Only HR and ISD Manager is Allowed to access this module.');
            }
        } else {
            return abort(409, 'Only HR and ISD Manager is Allowed to access this module.');
        }
    }

    public function approve(Request $request) {
        $id = $request['id'];

        $leaveConversion = LeaveConversions::find($id);

        if ($leaveConversion != null) {
            if ($leaveConversion->Status == 'Filed') {
                // APPROVED BY HR
                $leaveConversion->Status = 'Approved by HR';
                $leaveConversion->save();

                UserFootprints::logSource('Leave Conversion Request Approved by HR', 
                        'Leave credit to cash conversion approved by HR.',
                        $id);
            } else {
                // APPROVED BY ISD MANAGER
                $leaveConversion->Status = 'Approved';
                $leaveConversion->save();

                // UPDATE LeaveBalances
                $leaveBalances = LeaveBalances::where('EmployeeId', $leaveConversion->EmployeeId)->first();
                if ($leaveBalances != null) {
                    $leaveBalances->Vacation = $leaveBalances->Vacation != null ? ($leaveBalances->Vacation - ($leaveConversion->VacationDays * 8 * 60)) : 0;
                    $leaveBalances->Sick = $leaveBalances->Sick != null ? ($leaveBalances->Sick - ($leaveConversion->SickDays * 8 * 60)) : 0;
                    $leaveBalances->save();

                    // VACATION
                    if ($leaveConversion->VacationDays > 0) {
                        LeaveBalanceDetails::leaveLog(
                            $leaveBalances->EmployeeId,
                            'DEDUCT',
                            $leaveConversion->VacationDays,
                            'Deducted ' . $leaveConversion->VacationDays . ' days from Vacation Leave for cash conversion',
                            'VACATION'
                        );
                    }
                    
                    // SICK
                    if ($leaveConversion->SickDays > 0) {
                        LeaveBalanceDetails::leaveLog(
                            $leaveBalances->EmployeeId,
                            'DEDUCT',
                            $leaveConversion->SickDays,
                            'Deducted ' . $leaveConversion->SickDays . ' days from Sick Leave for cash conversion',
                            'SICK'
                        );
                    }
                }

                UserFootprints::logSource('Leave Conversion Request Approved', 
                        'Leave credit to cash conversion approved and posted.',
                        $id);
            }
        }

        return response()->json($leaveConversion, 200);
    }

    public function reject(Request $request) {
        $id = $request['id'];
        $notes = $request['Notes'];

        $leaveConversion = LeaveConversions::find($id);

        if ($leaveConversion != null) {
            $leaveConversion->Status = 'Rejected by HR';
            $leaveConversion->save();

            UserFootprints::logSource('Leave Conversion Request Rejected by HR', 
                    'Leave credit to cash conversion rejected by HR because of the following reasons: ' . $notes,
                    $id);
        }
    }

    public function approvedSLandVL(Request $request) {
        $data = DB::table('LeaveConversions')
                ->leftJoin('Employees', 'LeaveConversions.EmployeeId', '=', 'Employees.id')
                ->leftJoin('users', 'LeaveConversions.UserId', '=', 'users.id')
                ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                ->whereRaw("LeaveConversions.Status='Approved'")
                ->select(
                    'FirstName',
                    'MiddleName',
                    'LastName',
                    'Suffix',
                    'LeaveConversions.*',
                    'users.name',
                    'Vacation',
                    'Sick'
                )
                ->orderBy('LastName')
                ->orderBy('LeaveConversions.created_at')
                ->get();

        return view('/leave_conversions/approved_sl_vl', [
            'data' => $data,
        ]);
    }

    public function printAll() {
        $data = DB::table('LeaveConversions')
            ->leftJoin('Employees', 'LeaveConversions.EmployeeId', '=', 'Employees.id')
            ->leftJoin('users', 'LeaveConversions.UserId', '=', 'users.id')
            ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("LeaveConversions.Status='Approved'")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'LeaveConversions.*',
                'users.name',
                'Vacation',
                'Sick',
                'Positions.Position'
            )
            ->orderBy('LastName')
            ->orderBy('LeaveConversions.created_at')
            ->get();

        $payrollClerk = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Payroll Clerk' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $osdManager = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Manager, O S D' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $internalAuditor = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Internal Auditor' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $gm = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='General Manager' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        return view('/leave_conversions/print_all', [
            'data' => $data,
            'payrollClerk' => $payrollClerk,
            'osdManager' => $osdManager,
            'internalAuditor' => $internalAuditor,
            'gm' => $gm,
        ]);
    }

    public function printSingle($id) {
        $data = DB::table('LeaveConversions')
            ->leftJoin('Employees', 'LeaveConversions.EmployeeId', '=', 'Employees.id')
            ->leftJoin('users', 'LeaveConversions.UserId', '=', 'users.id')
            ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("LeaveConversions.id='" . $id . "'")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'LeaveConversions.*',
                'users.name',
                'Vacation',
                'Sick',
                'Positions.Position'
            )
            ->orderBy('LastName')
            ->orderBy('LeaveConversions.created_at')
            ->get();

        $payrollClerk = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Payroll Clerk' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $osdManager = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Manager, O S D' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $internalAuditor = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='Internal Auditor' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        $gm = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Positions.Position='General Manager' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN('Retired', 'Resigned'))")
            ->select(
                'FirstName',
                'MiddleName',
                'LastName',
                'Suffix',
                'Positions.Position'
            )
            ->first();

        return view('/leave_conversions/print_all', [
            'data' => $data,
            'payrollClerk' => $payrollClerk,
            'osdManager' => $osdManager,
            'internalAuditor' => $internalAuditor,
            'gm' => $gm,
        ]);
    }

    public function markAsDone(Request $request) {
        $id = $request['id'];

        $leaveConversion = LeaveConversions::find($id);

        if ($leaveConversion != null) {
            $leaveConversion->Status = 'Completed';
            $leaveConversion->save();

            UserFootprints::logSource('Leave Conversion Completed', 
                        'Leave credit to cash conversion process completed and has already been issued a check.',
                        $id);
        }

        return response()->json($leaveConversion, 200);
    }

    public function markAllAsDone(Request $request) {
        $leaveConversions = LeaveConversions::where('Status', 'Approved')->get();

        foreach($leaveConversions as $leaveConversion) {
            $leaveConversion->Status = 'Completed';
            $leaveConversion->save();

            UserFootprints::logSource('Leave Conversion Completed', 
                        'Leave credit to cash conversion process completed and has already been issued a check.',
                        $leaveConversion->id);
        }

        return response()->json('ok', 200);
    }

    public function getLeaveConversionsData(Request $request) {
        $employeeId = $request['EmployeeId'];

        return response()->json(LeaveConversions::where('EmployeeId', $employeeId)->orderByDesc('created_at')->get(), 200);
    }

    public function manualLeaveConversion(Request $request) {
        $employees = DB::table('Employees')
            ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
            ->select(
                'Employees.*',
                'Vacation',
                'Sick'
            )
            ->orderBy('LastName')
            ->get();

        return view('/leave_conversions/manual_leave_conversion', [
            'employees' => $employees,
        ]);
    }
}
