<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAttendaneConfirmationsRequest;
use App\Http\Requests\UpdateAttendaneConfirmationsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AttendaneConfirmationsRepository;
use Illuminate\Http\Request;
use App\Models\AttendaneConfirmations;
use App\Models\AttendaneConfirmationSignatories;
use App\Models\Employees;
use App\Models\IDGenerator;
use App\Models\Users;
use App\Models\AttendanceData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class AttendaneConfirmationsController extends AppBaseController
{
    /** @var AttendaneConfirmationsRepository $attendaneConfirmationsRepository*/
    private $attendaneConfirmationsRepository;

    public function __construct(AttendaneConfirmationsRepository $attendaneConfirmationsRepo)
    {
        $this->middleware('auth');
        $this->attendaneConfirmationsRepository = $attendaneConfirmationsRepo;
    }

    /**
     * Display a listing of the AttendaneConfirmations.
     */
    public function index(Request $request)
    {
        $attendaneConfirmations = $this->attendaneConfirmationsRepository->paginate(10);

        return view('attendane_confirmations.index')
            ->with('attendaneConfirmations', $attendaneConfirmations);
    }

    /**
     * Show the form for creating a new AttendaneConfirmations.
     */
    public function create()
    {
        return view('attendane_confirmations.create', [
            'employees' => Employees::orderBy('LastName')->get(),
        ]);
    }

    /**
     * Store a newly created AttendaneConfirmations in storage.
     */
    public function store(CreateAttendaneConfirmationsRequest $request)
    {
        $input = $request->all();

        if ($input['AMIn'] != null) {
            $input['AMIn'] = date('Y-m-d H:i:s', strtotime($input['AMIn']));
        }

        if ($input['AMOut'] != null) {
            $input['AMOut'] = date('Y-m-d H:i:s', strtotime($input['AMOut']));
        }

        if ($input['PMIn'] != null) {
            $input['PMIn'] = date('Y-m-d H:i:s', strtotime($input['PMIn']));
        }

        if ($input['PMOut'] != null) {
            $input['PMOut'] = date('Y-m-d H:i:s', strtotime($input['PMOut']));
        }

        if ($input['OTIn'] != null) {
            $input['OTIn'] = date('Y-m-d H:i:s', strtotime($input['OTIn']));
        }

        if ($input['OTOut'] != null) {
            $input['OTOut'] = date('Y-m-d H:i:s', strtotime($input['OTOut']));
        }

        $attendaneConfirmations = $this->attendaneConfirmationsRepository->create($input);

        // INSERT SIGNATORY
        $signatory = AttendaneConfirmationSignatories::where('AttendanceConfirmationId', $input['id'])->first();
        if ($signatory != null) {
            $signatory->delete();
        }
        
        $user = Auth::user();

        $employee = DB::table('Employees')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->select('Employees.LastName', 'Positions.Position', 'Positions.Department', 'Positions.ParentPositionId')
            ->whereRaw("Employees.id='" . $input['EmployeeId'] . "'")
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

                                $signatory = new AttendaneConfirmationSignatories;
                                $signatory->id = IDGenerator::generateID() . "" . $i;
                                $signatory->AttendanceConfirmationId = $input['id'];
                                $signatory->EmployeeId = $signatoryParents->id;
                                $signatory->Rank = ($rank+1);
                                $signatory->Status = null;
                                $signatory->save();

                                $rank++;
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

        return redirect(route('home'));
    }

    /**
     * Display the specified AttendaneConfirmations.
     */
    public function show($id)
    {
        $attendaneConfirmations = $this->attendaneConfirmationsRepository->find($id);

        if (empty($attendaneConfirmations)) {
            Flash::error('Attendane Confirmations not found');

            return redirect(route('attendaneConfirmations.index'));
        }

        return view('attendane_confirmations.show')->with('attendaneConfirmations', $attendaneConfirmations);
    }

    /**
     * Show the form for editing the specified AttendaneConfirmations.
     */
    public function edit($id)
    {
        $attendaneConfirmations = $this->attendaneConfirmationsRepository->find($id);

        if (empty($attendaneConfirmations)) {
            Flash::error('Attendane Confirmations not found');

            return redirect(route('attendaneConfirmations.index'));
        }

        return view('attendane_confirmations.edit')->with('attendaneConfirmations', $attendaneConfirmations);
    }

    /**
     * Update the specified AttendaneConfirmations in storage.
     */
    public function update($id, UpdateAttendaneConfirmationsRequest $request)
    {
        $attendaneConfirmations = $this->attendaneConfirmationsRepository->find($id);

        if (empty($attendaneConfirmations)) {
            Flash::error('Attendane Confirmations not found');

            return redirect(route('attendaneConfirmations.index'));
        }

        $attendaneConfirmations = $this->attendaneConfirmationsRepository->update($request->all(), $id);

        Flash::success('Attendane Confirmations updated successfully.');

        return redirect(route('attendaneConfirmations.index'));
    }

    /**
     * Remove the specified AttendaneConfirmations from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $attendaneConfirmations = $this->attendaneConfirmationsRepository->find($id);

        if (empty($attendaneConfirmations)) {
            Flash::error('Attendane Confirmations not found');

            return redirect(route('attendaneConfirmations.index'));
        }

        $this->attendaneConfirmationsRepository->delete($id);

        $employee = Employees::find($attendaneConfirmations->EmployeeId);
        $user = Users::where('employee_id', $employee->id)->first();

        // DELETE START MORNING IN
        if ($attendaneConfirmations->AMIn != null) {
            AttendanceData::where('BiometricUserId', $employee->BiometricsUserId)
                ->where('EmployeeId', $employee->id)
                ->where('Timestamp', $attendaneConfirmations->AMIn)
                ->delete();
        }            

        // DELETE START MORNING OUT
        if ($attendaneConfirmations->AMOut != null) {
            AttendanceData::where('BiometricUserId', $employee->BiometricsUserId)
                ->where('EmployeeId', $employee->id)
                ->where('Timestamp', $attendaneConfirmations->AMOut)
                ->delete();
        }            

        // DELETE START AFTERNOON IN
        if ($attendaneConfirmations->PMIn != null) {
            AttendanceData::where('BiometricUserId', $employee->BiometricsUserId)
                ->where('EmployeeId', $employee->id)
                ->where('Timestamp', $attendaneConfirmations->PMIn)
                ->delete();
        }
        
        // DELETE START AFTERNOON OUT
        if ($attendaneConfirmations->PMOut != null) {
            AttendanceData::where('BiometricUserId', $employee->BiometricsUserId)
                ->where('EmployeeId', $employee->id)
                ->where('Timestamp', $attendaneConfirmations->PMOut)
                ->delete();
        }

        // DELETE START OT IN
        if ($attendaneConfirmations->OTIn != null) {
            AttendanceData::where('BiometricUserId', $employee->BiometricsUserId)
                ->where('EmployeeId', $employee->id)
                ->where('Timestamp', $attendaneConfirmations->OTIn)
                ->delete();
        }
        
        // DELETE START OT OUT
        if ($attendaneConfirmations->OTOut != null) {
            AttendanceData::where('BiometricUserId', $employee->BiometricsUserId)
                ->where('EmployeeId', $employee->id)
                ->where('Timestamp', $attendaneConfirmations->OTOut)
                ->delete();
        }

        return response()->json($attendaneConfirmations, 200);

        // Flash::success('Attendane Confirmations deleted successfully.');

        // return redirect(route('attendaneConfirmations.index'));
    }

    public function myApprovals(Request $request) {
        $ats = DB::table('AttendanceConfirmationSignatories')
            ->leftJoin('AttendanceConfirmations', 'AttendanceConfirmationSignatories.AttendanceConfirmationId', '=', 'AttendanceConfirmations.id')
            ->leftJoin('users', 'AttendanceConfirmations.UserId', '=', 'users.id')
            ->leftJoin('Employees', 'AttendanceConfirmations.EmployeeId', '=', 'Employees.id')
            ->whereRaw("AttendanceConfirmationSignatories.EmployeeId='" . Auth::id() . "' AND (AttendanceConfirmations.Status IS NULL OR AttendanceConfirmations.Status='FILED') AND AttendanceConfirmationSignatories.id IN 
                (SELECT TOP 1 x.id FROM AttendanceConfirmationSignatories x WHERE x.AttendanceConfirmationId=AttendanceConfirmationSignatories.AttendanceConfirmationId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select('AttendanceConfirmations.*',
                'AttendanceConfirmationSignatories.id AS SignatoryId',
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'users.name')
            ->get();

        return view('/attendane_confirmations/my_approvals', [
            'ats' => $ats,
        ]);
    }

    public function approve(Request $request) {
        $id = $request['id'];

        $ats = AttendaneConfirmations::find($id);

        if ($ats != null) {
            $ats->Status = 'APPROVED';
            $ats->save();

            $employee = Employees::find($ats->EmployeeId);
            $user = Users::where('employee_id', $employee->id)->first();

            // INSERT START MORNING IN
            if ($ats->AMIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->AMIn;
                $attendance->save();
            }            

            // INSERT START MORNING OUT
            if ($ats->AMOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->AMOut;
                $attendance->save();
            }            

            // INSERT START AFTERNOON IN
            if ($ats->PMIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->PMIn;
                $attendance->save();
            }
            
            // INSERT START AFTERNOON OUT
            if ($ats->PMOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->PMOut;
                $attendance->save();
            }

            // INSERT START OT IN
            if ($ats->OTIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->OTIn;
                $attendance->save();
            }
            
            // INSERT START OT OUT
            if ($ats->OTOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->OTOut;
                $attendance->save();
            }
        }

        return response()->json($ats, 200);
    }

    public function reject(Request $request) {
        $id = $request['id'];
        $notes = $request['Notes'];

        $ats = AttendaneConfirmations::find($id);

        if ($ats != null) {
            $ats->Status = 'REJECTED';
            $ats->Notes = $notes;
            $ats->save();
        }

        return response()->json($ats, 200);
    }

    public function manualEntry(Request $request) {
        return view('/attendane_confirmations/manual_entry', [
            'employees' => Employees::orderBy('LastName')->get(),
        ]);
    }

    public function saveManualEntry(CreateAttendaneConfirmationsRequest $request) {
        $input = $request->all();

        if ($input['AMIn'] != null) {
            $input['AMIn'] = date('Y-m-d H:i:s', strtotime($input['AMIn']));
        }

        if ($input['AMOut'] != null) {
            $input['AMOut'] = date('Y-m-d H:i:s', strtotime($input['AMOut']));
        }

        if ($input['PMIn'] != null) {
            $input['PMIn'] = date('Y-m-d H:i:s', strtotime($input['PMIn']));
        }

        if ($input['PMOut'] != null) {
            $input['PMOut'] = date('Y-m-d H:i:s', strtotime($input['PMOut']));
        }

        if ($input['OTIn'] != null) {
            $input['OTIn'] = date('Y-m-d H:i:s', strtotime($input['OTIn']));
        }

        if ($input['OTOut'] != null) {
            $input['OTOut'] = date('Y-m-d H:i:s', strtotime($input['OTOut']));
        }

        $attendaneConfirmations = $this->attendaneConfirmationsRepository->create($input);

        $ats = AttendaneConfirmations::find($input['id']);

        if ($ats != null) {
            $employee = Employees::find($ats->EmployeeId);
            $user = Users::where('employee_id', $employee->id)->first();

            // INSERT START MORNING IN
            if ($ats->AMIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->AMIn;
                $attendance->save();
            }            

            // INSERT START MORNING OUT
            if ($ats->AMOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->AMOut;
                $attendance->save();
            }            

            // INSERT START AFTERNOON IN
            if ($ats->PMIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->PMIn;
                $attendance->save();
            }
            
            // INSERT START AFTERNOON OUT
            if ($ats->PMOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->PMOut;
                $attendance->save();
            }

            // INSERT START OT IN
            if ($ats->OTIn != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->OTIn;
                $attendance->save();
            }
            
            // INSERT START OT OUT
            if ($ats->OTOut != null) {
                $attendance = new AttendanceData;
                $attendance->id = IDGenerator::generateIDandRandString();
                $attendance->BiometricUserId = $employee->BiometricsUserId;
                $attendance->EmployeeId = $employee->id;
                $attendance->UserId = $user != null ? $user->id : null;
                $attendance->Timestamp = $ats->OTOut;
                $attendance->save();
            }
        }

        Flash::success('Attendance confirmation for saved!');

        return redirect(route('attendanceConfirmations.manual-entry'));
    }
}
