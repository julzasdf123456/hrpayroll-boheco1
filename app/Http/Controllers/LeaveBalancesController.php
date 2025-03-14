<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveBalancesRequest;
use App\Http\Requests\UpdateLeaveBalancesRequest;
use App\Repositories\LeaveBalancesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveBalances;
use App\Models\LeaveBalanceDetails;
use App\Models\IDGenerator;
use Flash;
use Response;
use Carbon\Carbon;

class LeaveBalancesController extends AppBaseController
{
    /** @var  LeaveBalancesRepository */
    private $leaveBalancesRepository;

    public function __construct(LeaveBalancesRepository $leaveBalancesRepo)
    {
        $this->middleware('auth');
        $this->leaveBalancesRepository = $leaveBalancesRepo;
    }

    /**
     * Display a listing of the LeaveBalances.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveBalances = $this->leaveBalancesRepository->all();

        return view('leave_balances.index')
            ->with('leaveBalances', $leaveBalances);
    }

    /**
     * Show the form for creating a new LeaveBalances.
     *
     * @return Response
     */
    public function create()
    {
        return view('leave_balances.create');
    }

    /**
     * Store a newly created LeaveBalances in storage.
     *
     * @param CreateLeaveBalancesRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveBalancesRequest $request)
    {
        $input = $request->all();

        $leaveBalances = $this->leaveBalancesRepository->create($input);

        Flash::success('Leave Balances saved successfully.');

        return redirect(route('leaveBalances.index'));
    }

    /**
     * Display the specified LeaveBalances.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        return view('leave_balances.show')->with('leaveBalances', $leaveBalances);
    }

    /**
     * Show the form for editing the specified LeaveBalances.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        return view('leave_balances.edit')->with('leaveBalances', $leaveBalances);
    }

    /**
     * Update the specified LeaveBalances in storage.
     *
     * @param int $id
     * @param UpdateLeaveBalancesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveBalancesRequest $request)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        $leaveBalances = $this->leaveBalancesRepository->update($request->all(), $id);

        // Flash::success('Leave Balances updated successfully.');

        // return redirect(route('leaveBalances.index'));
        return response()->json($leaveBalances, 200);
    }

    /**
     * Remove the specified LeaveBalances from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveBalances = $this->leaveBalancesRepository->find($id);

        if (empty($leaveBalances)) {
            Flash::error('Leave Balances not found');

            return redirect(route('leaveBalances.index'));
        }

        $this->leaveBalancesRepository->delete($id);

        Flash::success('Leave Balances deleted successfully.');

        return redirect(route('leaveBalances.index'));
    }

    public function getLeaveData(Request $request) {
        $employeeId = $request['EmployeeId'];
        $leaveType = $request['LeaveType'];

        if ($leaveType == 'All') {
            $data = DB::table('LeaveApplications')
                ->whereRaw("LeaveApplications.EmployeeId='" . $employeeId . "'")
                ->select(
                    'LeaveApplications.*',
                    DB::raw("(SELECT SUM(Longevity) FROM LeaveDays WHERE LeaveId=LeaveApplications.id) AS TotalDays")
                )
                ->orderByDesc('LeaveApplications.created_at')
                ->paginate(10);
        } else {
            $data = DB::table('LeaveApplications')
                ->whereRaw("LeaveApplications.EmployeeId='" . $employeeId . "' AND LeaveApplications.LeaveType='" . $leaveType . "'")
                ->select(
                    'LeaveApplications.*',
                    DB::raw("(SELECT SUM(Longevity) FROM LeaveDays WHERE LeaveId=LeaveApplications.id) AS TotalDays")
                )
                ->orderByDesc('LeaveApplications.created_at')
                ->paginate(10);
        }

        return response()->json($data, 200);
    }

    public function batchEdit(Request $request) {
        return view('/leave_balances/batch_edit', [

        ]);
    }

    public function getMergeData(Request $request) {
        $department = $request['Department'] ?? 'All';
        $month = $request['Month'] ?? Carbon::now()->format('M');
        $year = $request['Year'] ?? Carbon::now()->format('Y');

        

        if ($department == 'All') {
            $data = DB::table('Employees')
                ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
                ->whereRaw("(EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->where("LeaveBalances.Month","=",$month)
                ->where("LeaveBalances.Year","=",$year)
                ->select(
                    'LeaveBalances.*',
                    'FirstName',
                    'LastName',
                    'MiddleName',
                    'Suffix',
                    'Gender'
                )
                ->orderBy('LastName')
                ->get();
        } else {
            $data = DB::table('Employees')
                ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
                ->whereRaw("Positions.Department='" . $department . "' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->where("LeaveBalances.Month","=",$month)
                ->where("LeaveBalances.Year","=",$year)
                ->select(
                    'LeaveBalances.*',
                    'FirstName',
                    'LastName',
                    'MiddleName',
                    'Suffix',
                    'Gender'
                )
                ->orderBy('LastName')
                ->get();
        }

        return response()->json($data, 200);
    }

    public function updateValue(UpdateLeaveBalancesRequest $request)
    {
        $id = $request['id'];
        $type = $request['Type'];
        $value = $request['Value'];
        $added = $request['AddedValue'];

        $leaveBalances = LeaveBalances::find($id);

        $displayData = null;

        if ($leaveBalances != null) {
            if ($type == 'Vacation') {
                $leaveBalances->Vacation = $value;

                if (isset($added) && $added != null) {
                    $displayData = $added;
                }
            } else if ($type == 'Sick') {
                $leaveBalances->Sick = $value;

                if (isset($added) && $added != null) {
                    $displayData = $added;
                }
            } else if ($type == 'Special') {
                $leaveBalances->Special = $value;
            } else if ($type == 'Maternity') {
                $leaveBalances->Maternity = $value;
            } else if ($type == 'MaternityForSoloMother') {
                $leaveBalances->MaternityForSoloMother = $value;
            } else if ($type == 'Paternity') {
                $leaveBalances->Paternity = $value;
            } else if ($type == 'SoloParent') {
                $leaveBalances->SoloParent = $value;
            }
            $leaveBalances->save();

            
            if ($displayData != null) {
                LeaveBalanceDetails::create([
                    'id' => IDGenerator::generateIDandRandString(),
                    'EmployeeId' => $leaveBalances->EmployeeId,
                    'Method' => 'ADD-MANUAL',
                    'Days' => $added > 0 ? ($added / 8) : 0,
                    'Details' => 'Added ' . $displayData . ' hours of ' . $type . ' leave by ' . Auth::user()->name . '. Current ' . $type . ' leave total as of ' . date('M d, Y') . ': ' . $value,
                    'Month' => date('Y-m-01'),
                    'LeaveType' => strtoupper($type),
                ]);
            }
        }

        return response()->json($leaveBalances, 200);
    }

    public function balances(Request $request) {

        return view('leave_balances/balances');
    }

    public function printBalances($department, $month, $year) {


        $department = $department ?? 'All';
        $month = $month ?? Carbon::now()->format('M');
        $year = $year ?? Carbon::now()->format('Y');

        if ($department == 'All') {
            $data = DB::table('Employees')
                ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
                ->whereRaw("(EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->where("LeaveBalances.Month","=",$month)
                ->where("LeaveBalances.Year","=",$year)
                ->select(
                    'LeaveBalances.*',
                    'FirstName',
                    'LastName',
                    'MiddleName',
                    'Suffix',
                    'Gender'
                )
                ->orderBy('LastName')
                ->get();
        } else {
            $data = DB::table('Employees')
                ->leftJoin('LeaveBalances', 'Employees.id', '=', 'LeaveBalances.EmployeeId')
                ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                ->leftJoin('Positions', 'EmployeesDesignations.PositionId', '=', 'Positions.id')
                ->whereRaw("Positions.Department='" . $department . "' AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                ->where("LeaveBalances.Month","=",$month)
                ->where("LeaveBalances.Year","=",$year)
                ->select(
                    'LeaveBalances.*',
                    'FirstName',
                    'LastName',
                    'MiddleName',
                    'Suffix',
                    'Gender'
                )
                ->orderBy('LastName')
                ->get();
        }

        return view('leave_balances.print_balances', [
            'data' => $data,
            'month' => $month,
            'year' => $year
        ]);

        // return response()->json([
        //     'data' => $data,
        //     'month' => $month,
        //     'year' => $year
        // ]);
    }
}
