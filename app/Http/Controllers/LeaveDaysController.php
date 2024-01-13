<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveDaysRequest;
use App\Http\Requests\UpdateLeaveDaysRequest;
use App\Repositories\LeaveDaysRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\LeaveDays;
use App\Models\IDGenerator;
use App\Models\LeaveApplications;
use App\Models\HolidaysList;
use Illuminate\Support\Facades\DB;
use \DatePeriod;
use \DateTime;
use \DateInterval;
use Flash;
use Response;

class LeaveDaysController extends AppBaseController
{
    /** @var  LeaveDaysRepository */
    private $leaveDaysRepository;

    public function __construct(LeaveDaysRepository $leaveDaysRepo)
    {
        $this->middleware('auth');
        $this->leaveDaysRepository = $leaveDaysRepo;
    }

    /**
     * Display a listing of the LeaveDays.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveDays = $this->leaveDaysRepository->all();

        return view('leave_days.index')
            ->with('leaveDays', $leaveDays);
    }

    /**
     * Show the form for creating a new LeaveDays.
     *
     * @return Response
     */
    public function create()
    {
        return view('leave_days.create');
    }

    /**
     * Store a newly created LeaveDays in storage.
     *
     * @param CreateLeaveDaysRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveDaysRequest $request)
    {
        $input = $request->all();

        $leaveDays = $this->leaveDaysRepository->create($input);

        Flash::success('Leave Days saved successfully.');

        return redirect(route('leaveDays.index'));
    }

    /**
     * Display the specified LeaveDays.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveDays = $this->leaveDaysRepository->find($id);

        if (empty($leaveDays)) {
            Flash::error('Leave Days not found');

            return redirect(route('leaveDays.index'));
        }

        return view('leave_days.show')->with('leaveDays', $leaveDays);
    }

    /**
     * Show the form for editing the specified LeaveDays.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveDays = $this->leaveDaysRepository->find($id);

        if (empty($leaveDays)) {
            Flash::error('Leave Days not found');

            return redirect(route('leaveDays.index'));
        }

        return view('leave_days.edit')->with('leaveDays', $leaveDays);
    }

    /**
     * Update the specified LeaveDays in storage.
     *
     * @param int $id
     * @param UpdateLeaveDaysRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveDaysRequest $request)
    {
        $leaveDays = $this->leaveDaysRepository->find($id);

        if (empty($leaveDays)) {
            Flash::error('Leave Days not found');

            return redirect(route('leaveDays.index'));
        }

        $leaveDays = $this->leaveDaysRepository->update($request->all(), $id);

        Flash::success('Leave Days updated successfully.');

        return redirect(route('leaveDays.index'));
    }

    /**
     * Remove the specified LeaveDays from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveDays = $this->leaveDaysRepository->find($id);

        if (empty($leaveDays)) {
            Flash::error('Leave Days not found');

            return redirect(route('leaveDays.index'));
        }

        $this->leaveDaysRepository->delete($id);

        // Flash::success('Leave Days deleted successfully.');

        // return redirect(route('leaveDays.index'));
        return response()->json('ok', 200);
    }

    public function addDays(Request $request) {
        $leaveId = $request['LeaveId'];
        $from = $request['From'];
        $to = $request['To'];
        
        $output = "";

        $leaveApplication = LeaveApplications::find($leaveId);
        $leaveBalance = DB::table('LeaveBalances')
            ->select(DB::raw($leaveApplication->LeaveType . ' AS Balance'))
            ->where('EmployeeId', $leaveApplication->EmployeeId)
            ->first();
        $holidaysList = HolidaysList::whereRaw("HolidayDate > GETDATE()")->get();
        $holidays = [];
        foreach ($holidaysList as $item) {
            array_push($holidays, date('Y-m-d', strtotime($item->HolidayDate)));
        }

        if ($from == $to) {
            $day = date('D', strtotime($to));
            if ($day == 'Sun') {

            } else {

                // CHECK NO OF DAYS FROM BALANCE
                $prevLeaveDays = LeaveDays::where('LeaveId', $leaveId)->get();
                $totalPrev = 0;
                foreach($prevLeaveDays as $item) {
                    $totalPrev += floatval($item->Longevity);
                }
                
                $balance = floatval($leaveBalance->Balance);

                //CHECK IF DAY EXISTS ALREADY
                $leaveDayCheck = LeaveDays::where('LeaveDate', $from)->first();
                if ($leaveDayCheck == null) {
                    // CHECK IF THERE ARE STILL AVAILABLE BALANCE
                    $remain = $balance - $totalPrev;

                    if ($remain >= 1) {
                        $lid = IDGenerator::generateIDandRandString();
                        $leaveDays = new LeaveDays;
                        $leaveDays->id = $lid;
                        $leaveDays->LeaveId = $leaveId;
                        $leaveDays->LeaveDate = $from;
                        $leaveDays->Longevity = 1;
                        $leaveDays->Duration = 'WHOLE';
                        $leaveDays->save();

                        $output .= "<tr id='" . $lid . "'>
                                        <td>" . date('D, M d, Y', strtotime($from)) . "</td>
                                        <td>
                                            <select id='longevity-" . $lid . "' class='form-control form-control-sm'>
                                                <option value='WHOLE' selected>Whole Day</option>
                                                <option value='AM'>Morning Only</option>
                                                <option value='PM'>Afternoon Only</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class='btn btn-xs btn-danger float-right' onclick=deleteDate('" . $lid . "')><i class='fas fa-trash'></i></button>
                                            <button class='btn btn-xs btn-primary float-right' onclick=updateLongevity('" . $lid . "')><i class='fas fa-check-circle'></i></button>
                                        </td>
                                    </tr>";
                    } elseif ($remain < 1 && $remain >= .5) {
                        $lid = IDGenerator::generateIDandRandString();
                        $leaveDays = new LeaveDays;
                        $leaveDays->id = $lid;
                        $leaveDays->LeaveId = $leaveId;
                        $leaveDays->LeaveDate = $from;
                        $leaveDays->Longevity = 0.5;
                        $leaveDays->Duration = 'AM';
                        $leaveDays->save();

                        $output .= "<tr id='" . $lid . "'>
                                        <td>" . date('D, M d, Y', strtotime($from)) . "</td>
                                        <td>
                                            <select id='longevity-" . $lid . "' class='form-control form-control-sm'>
                                                <option value='WHOLE'>Whole Day</option>
                                                <option value='AM' selected>Morning Only</option>
                                                <option value='PM'>Afternoon Only</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class='btn btn-xs btn-danger float-right' onclick=deleteDate('" . $lid . "')><i class='fas fa-trash'></i></button>
                                            <button class='btn btn-xs btn-primary float-right' onclick=updateLongevity('" . $lid . "')><i class='fas fa-check-circle'></i></button>
                                        </td>
                                    </tr>";
                    } elseif ($remain == -.5) {
                        $lid = IDGenerator::generateIDandRandString();
                        $leaveDays = new LeaveDays;
                        $leaveDays->id = $lid;
                        $leaveDays->LeaveId = $leaveId;
                        $leaveDays->LeaveDate = $from;
                        $leaveDays->Longevity = 0.5;
                        $leaveDays->Duration = 'AM';
                        $leaveDays->save();

                        $output .= "<tr id='" . $lid . "'>
                                        <td>" . date('D, M d, Y', strtotime($from)) . "</td>
                                        <td>
                                            <select id='longevity-" . $lid . "' class='form-control form-control-sm'>
                                                <option value='WHOLE'>Whole Day</option>
                                                <option value='AM' selected>Morning Only</option>
                                                <option value='PM'>Afternoon Only</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class='btn btn-xs btn-danger float-right' onclick=deleteDate('" . $lid . "')><i class='fas fa-trash'></i></button>
                                            <button class='btn btn-xs btn-primary float-right' onclick=updateLongevity('" . $lid . "')><i class='fas fa-check-circle'></i></button>
                                        </td>
                                    </tr>";
                    } else {
                        // SKIP ADD, NO BALANCE LEFT
                    }                    
                }
            }
        } else {
            $to = date('Y-m-d', strtotime($to . ' +1 day'));

            $period = new DatePeriod(
                new DateTime($from),
                new DateInterval('P1D'),
                new DateTime($to)
           );

           // CHECK NO OF DAYS FROM BALANCE
           $prevLeaveDays = LeaveDays::where('LeaveId', $leaveId)->get();
           $totalPrev = 0;
           foreach($prevLeaveDays as $item) {
                $totalPrev += floatval($item->Longevity);
           }

           $balance = floatval($leaveBalance->Balance);
    
           $arr = [];
           foreach ($period as $key => $value) {
                // array_push($arr,
                //     $value->format('Y-m-d')
                // ); 
                // CHECK IF DAY IS HOLIDAY
                if (in_array($value->format('Y-m-d'), $holidays)) {
                    // skip if holiday
                } else {
                    $day = $value->format('D');
                    if ($day == 'Sun') {
        
                    } else {
                        //CHECK IF DAY EXISTS ALREADY
                        $leaveDayCheck = LeaveDays::where('LeaveDate', $value->format('Y-m-d'))
                            ->whereRaw("(Status IS NULL OR Status NOT IN ('REJECTED'))")
                            ->first();
                        if ($leaveDayCheck == null) {
                            // CHECK IF THERE ARE STILL AVAILABLE BALANCE
                            $remain = $balance - $totalPrev;

                            if ($remain >= 1) {
                                $lid = IDGenerator::generateIDandRandString();
                                $leaveDays = new LeaveDays;
                                $leaveDays->id = $lid;
                                $leaveDays->LeaveId = $leaveId;
                                $leaveDays->LeaveDate = $value->format('Y-m-d');
                                $leaveDays->Longevity = 1;
                                $leaveDays->Duration = 'WHOLE';
                                $leaveDays->save();
                
                                $output .= "<tr id='" . $lid . "'>
                                                <td>" . $value->format('D, M d, Y') . "</td>
                                                <td>
                                                    <select id='longevity-" . $lid . "' class='form-control form-control-sm'>
                                                        <option value='WHOLE' selected>Whole Day</option>
                                                        <option value='AM'>Morning Only</option>
                                                        <option value='PM'>Afternoon Only</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class='btn btn-xs btn-danger float-right' onclick=deleteDate('" . $lid . "')><i class='fas fa-trash'></i></button>
                                                    <button class='btn btn-xs btn-primary float-right' onclick=updateLongevity('" . $lid . "')><i class='fas fa-check-circle'></i></button>
                                                </td>
                                            </tr>";
                                $totalPrev += 1;
                            } elseif ($remain < 1 && $remain >= .5) {
                                $lid = IDGenerator::generateIDandRandString();
                                $leaveDays = new LeaveDays;
                                $leaveDays->id = $lid;
                                $leaveDays->LeaveId = $leaveId;
                                $leaveDays->LeaveDate = $value->format('Y-m-d');
                                $leaveDays->Longevity = 0.5;
                                $leaveDays->Duration = 'AM';
                                $leaveDays->save();
                
                                $output .= "<tr id='" . $lid . "'>
                                                <td>" . $value->format('D, M d, Y') . "</td>
                                                <td>
                                                    <select id='longevity-" . $lid . "' class='form-control form-control-sm'>
                                                        <option value='WHOLE'>Whole Day</option>
                                                        <option value='AM' selected>Morning Only</option>
                                                        <option value='PM'>Afternoon Only</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class='btn btn-xs btn-danger float-right' onclick=deleteDate('" . $lid . "')><i class='fas fa-trash'></i></button>
                                                    <button class='btn btn-xs btn-primary float-right' onclick=updateLongevity('" . $lid . "')><i class='fas fa-check-circle'></i></button>
                                                </td>
                                            </tr>";
                                $totalPrev += 0.5;
                            } elseif ($remain == -.5) {
                                $lid = IDGenerator::generateIDandRandString();
                                $leaveDays = new LeaveDays;
                                $leaveDays->id = $lid;
                                $leaveDays->LeaveId = $leaveId;
                                $leaveDays->LeaveDate = $from;
                                $leaveDays->Longevity = 0.5;
                                $leaveDays->Duration = 'AM';
                                $leaveDays->save();
        
                                $output .= "<tr id='" . $lid . "'>
                                                <td>" . date('D, M d, Y', strtotime($from)) . "</td>
                                                <td>
                                                    <select id='longevity-" . $lid . "' class='form-control form-control-sm'>
                                                        <option value='WHOLE'>Whole Day</option>
                                                        <option value='AM' selected>Morning Only</option>
                                                        <option value='PM'>Afternoon Only</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class='btn btn-xs btn-danger float-right' onclick=deleteDate('" . $lid . "')><i class='fas fa-trash'></i></button>
                                                    <button class='btn btn-xs btn-primary float-right' onclick=updateLongevity('" . $lid . "')><i class='fas fa-check-circle'></i></button>
                                                </td>
                                            </tr>";
                                $totalPrev += 0.5;
                            } else {
                                // NO AVAILABLE LEAVE BALANCE LEFT
                            }                        
                        }                    
                    }  
                }           
            }
        }
        

       return response()->json($output, 200);
    }

    public function updateLongevity(Request $request) {
        $longevity = $request['Longevity'];
        $id = $request['id'];
        $leaveId = $request['LeaveId'];

        // CHECK AVAILABLE BALANCE
        $leaveApplication = LeaveApplications::find($leaveId);
        $leaveBalance = DB::table('LeaveBalances')
            ->select(DB::raw($leaveApplication->LeaveType . ' AS Balance'))
            ->where('EmployeeId', $leaveApplication->EmployeeId)
            ->first();

        $prevLeaveDays = LeaveDays::where('LeaveId', $leaveId)->get();
        $totalPrev = 0;
        foreach($prevLeaveDays as $item) {
            $totalPrev += floatval($item->Longevity);
        }

        $balance = floatval($leaveBalance->Balance);

        if ($longevity=='WHOLE') {
            $longevityValue = 1;
        } else {
            $longevityValue = .5;
        }

        $remain = $balance - ($totalPrev + $longevityValue);

        if ($remain < 0) {
            return response()->json('Insufficient Balance', 401);
        } else {
            LeaveDays::where('id', $id)
                ->update(['Longevity' => $longevityValue, 'Duration' => $longevity]);

            return response()->json('ok', 200);
        }        
    }
}
