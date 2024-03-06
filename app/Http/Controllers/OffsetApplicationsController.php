<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOffsetApplicationsRequest;
use App\Http\Requests\UpdateOffsetApplicationsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\OffsetApplicationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Employees;
use App\Models\OffsetApplications;
use App\Models\OffsetSignatories;
use App\Models\IDGenerator;
use App\Models\AttendanceData;
use App\Models\Users;
use Flash;

class OffsetApplicationsController extends AppBaseController
{
    /** @var OffsetApplicationsRepository $offsetApplicationsRepository*/
    private $offsetApplicationsRepository;

    public function __construct(OffsetApplicationsRepository $offsetApplicationsRepo)
    {
        $this->middleware('auth');
        $this->offsetApplicationsRepository = $offsetApplicationsRepo;
    }

    /**
     * Display a listing of the OffsetApplications.
     */
    public function index(Request $request)
    {
        $offsetApplications = $this->offsetApplicationsRepository->paginate(10);

        return view('offset_applications.index')
            ->with('offsetApplications', $offsetApplications);
    }

    /**
     * Show the form for creating a new OffsetApplications.
     */
    public function create()
    {
        return view('offset_applications.create', [
            'employees' => Employees::orderBy('LastName')->get(),
        ]);
    }

    /**
     * Store a newly created OffsetApplications in storage.
     */
    public function store(CreateOffsetApplicationsRequest $request)
    {
        $input = $request->all();

        $offsetApplications = $this->offsetApplicationsRepository->create($input);

        Flash::success('Offset Applications saved successfully.');

        return redirect(route('offsetApplications.index'));
    }

    /**
     * Display the specified OffsetApplications.
     */
    public function show($id)
    {
        $offsetApplications = $this->offsetApplicationsRepository->find($id);

        if (empty($offsetApplications)) {
            Flash::error('Offset Applications not found');

            return redirect(route('offsetApplications.index'));
        }

        return view('offset_applications.show')->with('offsetApplications', $offsetApplications);
    }

    /**
     * Show the form for editing the specified OffsetApplications.
     */
    public function edit($id)
    {
        $offsetApplications = $this->offsetApplicationsRepository->find($id);

        if (empty($offsetApplications)) {
            Flash::error('Offset Applications not found');

            return redirect(route('offsetApplications.index'));
        }

        return view('offset_applications.edit')->with('offsetApplications', $offsetApplications);
    }

    /**
     * Update the specified OffsetApplications in storage.
     */
    public function update($id, UpdateOffsetApplicationsRequest $request)
    {
        $offsetApplications = $this->offsetApplicationsRepository->find($id);

        if (empty($offsetApplications)) {
            Flash::error('Offset Applications not found');

            return redirect(route('offsetApplications.index'));
        }

        $offsetApplications = $this->offsetApplicationsRepository->update($request->all(), $id);

        Flash::success('Offset Applications updated successfully.');

        return redirect(route('offsetApplications.index'));
    }

    /**
     * Remove the specified OffsetApplications from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $offsetApplications = $this->offsetApplicationsRepository->find($id);

        if (empty($offsetApplications)) {
            Flash::error('Offset Applications not found');

            return redirect(route('offsetApplications.index'));
        }

        $this->offsetApplicationsRepository->delete($id);

        Flash::success('Offset Applications deleted successfully.');

        return redirect(route('offsetApplications.index'));
    }

    public function saveOffsetApplications(Request $request) {
        $data = $request['Data'];

        $id = IDGenerator::generateID();
        $i = 0;
        foreach($data as $item) {
            // INSERT INTO OFFSET APPLICATIONS
            $offset = new OffsetApplications;
            $offset->OffsetBatchId = $id;
            $offset->id = $id . $i;
            $offset->PreparedBy = Auth::id();
            $offset->DatePrepared = date('Y-m-d');
            $offset->EmployeeId = $item['EmployeeId'];
            $offset->DateOfDuty = $item['DateOfDuty'];
            $offset->PurposeOfDuty = $item['Purpose'];
            $offset->DateOfOffset = $item['DateOfOffset'];
            $offset->OffsetReason = $item['Reason'];
            $offset->Status = isset($item['Status']) && $item['Status']=='APPROVED' ? $item['Status'] : 'FILED';
            $offset->save();

            $i++;
        }

        if (isset($item['Status']) && $item['Status']=='APPROVED') {

        } else {
            // INSERT SIGNATORY
            $user = Auth::user();

            $employee = DB::table('Employees')
                ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
                ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                ->select('Employees.LastName', 'Positions.Position', 'Positions.Department', 'Positions.ParentPositionId')
                ->whereRaw("Employees.id='" . $user->employee_id . "'")
                ->first();

            if ($employee != null) {
                if ($employee->ParentPositionId != null) {
                    // LOOP SIGNATORIES AND FETCH UPPER LEVEL POSITIONS
                    $signatories = [];
                    $parentPosId = $employee->ParentPositionId;
                    $dept = $employee->Department;
                    $sign = true;
                    $i = 0;
                    $rank = 0;

                    while ($sign) {
                        $signatoryParents = DB::table('users')
                            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
                            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
                            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                            ->select('users.id', 'Employees.FirstName', 'Employees.LastName', 'Employees.MiddleName', 'Employees.Suffix', 'Positions.Level', 'Positions.Position', 'Positions.ParentPositionId', 'Positions.id AS PositionId')
                            ->whereRaw("Positions.id='" . $parentPosId . "' ")
                            ->first();

                        if ($i > 3) {
                            break;
                        } else {
                            if ($signatoryParents != null && $signatoryParents->id != null) {
                                if ($signatoryParents->Level == 'Manager') {
                                    array_push($signatories, [
                                        'id' => $signatoryParents->id,
                                        'FirstName' => $signatoryParents->FirstName,
                                        'LastName' => $signatoryParents->LastName,
                                        'MiddleName' => $signatoryParents->MiddleName,
                                        'Suffix' => $signatoryParents->Suffix,
                                        'Position' => $signatoryParents->Position,
                                        'Level' => $signatoryParents->Level,
                                    ]);

                                    $offsetSig = OffsetSignatories::where('OffsetBatchId', $id)->where('EmployeeId', $signatoryParents->id)->first();
                                    if ($offsetSig == null) {
                                        $offsetSig = new OffsetSignatories;
                                        $offsetSig->id = IDGenerator::generateID() . "" . $i;
                                        $offsetSig->OffsetBatchId = $id;
                                        $offsetSig->EmployeeId = $signatoryParents->id;
                                        $offsetSig->Rank = ($rank+1);
                                        $offsetSig->Status = null;
                                        $offsetSig->save();

                                        $rank++;
                                    }
                                }
                            }

                            if ($signatoryParents->ParentPositionId != null) {
                                $parentPosId = $signatoryParents->ParentPositionId;
                                $sign = true;
                                $i++;
                            } else {
                                $sign = false;
                                break;
                            }
                        }
                    }
                }           
            }
        }

        return response()->json('ok', 200);
    }

    public function myApprovals(Request $request) {
        $offsets = DB::table('OffsetSignatories')
            ->leftJoin('OffsetApplications', 'OffsetSignatories.OffsetBatchId', '=', 'OffsetApplications.OffsetBatchId')
            ->leftJoin('users', 'OffsetApplications.PreparedBy', '=', 'users.id')
            ->leftJoin('Employees', 'OffsetApplications.EmployeeId', '=', 'Employees.id')
            ->whereRaw("OffsetSignatories.EmployeeId='" . Auth::id() . "' AND (OffsetApplications.Status IS NULL OR OffsetApplications.Status='FILED') AND OffsetSignatories.id IN 
                (SELECT TOP 1 x.id FROM OffsetSignatories x WHERE x.OffsetBatchId=OffsetSignatories.OffsetBatchId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select('OffsetApplications.*',
                'OffsetSignatories.id AS SignatoryId',
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'users.name')
            ->get();

        return view('/offset_applications/my_approvals', [
            'offsets' => $offsets,
        ]);
    }

    public function approve(Request $request) {
        $id = $request['id'];

        $offset = OffsetApplications::find($id);

        if ($offset != null) {
            $offset->Status = 'APPROVED';
            $offset->save();

            $employee = Employees::find($offset->EmployeeId);
            $user = Users::where('employee_id', $employee->id)->first();

            // INSERT START MORNING IN
            $attendance = new AttendanceData;
            $attendance->id = IDGenerator::generateIDandRandString();
            $attendance->BiometricUserId = $employee->BiometricsUserId;
            $attendance->EmployeeId = $employee->id;
            $attendance->UserId = $user != null ? $user->id : null;
            $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 07:31:00';
            $attendance->AbsentPermission = 'OFFSET';
            $attendance->save();

            // INSERT START MORNING OUT
            $attendance = new AttendanceData;
            $attendance->id = IDGenerator::generateIDandRandString();
            $attendance->BiometricUserId = $employee->BiometricsUserId;
            $attendance->EmployeeId = $employee->id;
            $attendance->UserId = $user != null ? $user->id : null;
            $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 12:05:00';
            $attendance->AbsentPermission = 'OFFSET';
            $attendance->save();

            // INSERT START AFTERNOON IN
            $attendance = new AttendanceData;
            $attendance->id = IDGenerator::generateIDandRandString();
            $attendance->BiometricUserId = $employee->BiometricsUserId;
            $attendance->EmployeeId = $employee->id;
            $attendance->UserId = $user != null ? $user->id : null;
            $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 12:45:00';
            $attendance->AbsentPermission = 'OFFSET';
            $attendance->save();

            // INSERT START AFTERNOON OUT
            $attendance = new AttendanceData;
            $attendance->id = IDGenerator::generateIDandRandString();
            $attendance->BiometricUserId = $employee->BiometricsUserId;
            $attendance->EmployeeId = $employee->id;
            $attendance->UserId = $user != null ? $user->id : null;
            $attendance->Timestamp = date('Y-m-d', strtotime($offset->DateOfOffset)) . ' 17:05:00';
            $attendance->AbsentPermission = 'OFFSET';
            $attendance->save();
        }

        return response()->json($offset, 200);
    }

    public function reject(Request $request) {
        $id = $request['id'];
        $notes = $request['Notes'];

        $offset = OffsetApplications::find($id);

        if ($offset != null) {
            $offset->Status = 'REJECTED';
            $offset->Notes = $notes;
            $offset->save();
        }

        return response()->json($offset, 200);
    }

    public function manualEntry(Request $request) {
        return view('/offset_applications/manual_entry', [
            'employees' => Employees::orderBy('LastName')->get(),
        ]);
    }
}
