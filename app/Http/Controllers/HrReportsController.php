<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceDataExport;
use Carbon\Carbon;

use App\Models\Employees;
use App\Models\AttendanceData;
use App\Models\OffsetApplications;
use App\Models\EmployeeDayOffs;
use App\Models\Positions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HrReportsController extends Controller
{
    public function attendanceForm()
    {

        $departmentData = Positions::select('department')->distinct()->get()->pluck('department')->unique()->toArray();

        $departments = array_map(
            function ($item) {
                return $item;
            },
            $departmentData
        );

        return view('hr_reports.attendance_form', ['departments' => $departments]);
    }



    public function attendanceByDepartment(Request $req)
    {
        try {
            $position = Positions::select('department')
                ->where('department', $req->input('department'))
                ->first();
            $date1 = Carbon::parse($req->input('from_date'))->format('Y-m-d');
            $date2 = Carbon::parse($req->input('to_date'))->format('Y-m-d');
            if (Carbon::parse($date1)->greaterThan(Carbon::parse($date2))) {
                throw new Exception('From date must be less than or equal to to date');
            }

            $employees = DB::select("
            select distinct
                a.id,a.firstname,a.middlename,a.lastname,
                c.position, c.department
            from employees as a
                left join employeesdesignations as b on a.id = b.employeeid
                left join positions as c on c.id = b.positionid
            where 
                c.department = ?
                and c.position not like '%chief%'
                and a.dateended is null 
            order by a.lastname;
            ", [$position['department']]);


            // $attendanceData = DB::select("
            // select 
            //     a.id,
            //     c.department,
            //     d.timestamp,d.type,
            //     case
            //         when 
            //             cast(d.timestamp as time) >= '00:00:00' 
            //             and cast(d.timestamp as time) <= dateadd(minute, 6, e.starttime)
            //             and d.type = 'AM IN'
            //         then 'Present'
            //         when 
            //             cast(d.timestamp as time) > dateadd(minute, 6, e.starttime) 
            //             and cast(d.timestamp as time) <= dateadd(minute, 15, e.starttime)
            //             and d.type = 'AM IN'
            //         then 'Late'
            //         when 
            //             cast(d.timestamp as time) <= dateadd(minute, 30, e.starttime)
            //             and cast(d.timestamp as time) >= e.breakstart
            //             and d.type = 'AM OUT' 
            //             and e.breakstart is not null 
            //         then 'Undertime'
            //         when 
            //             cast(d.timestamp as time) >= e.breakstart
            //             and cast(d.timestamp as time) <= dateadd(minute, 30, e.breakstart)
            //             and d.type = 'AM OUT' 
            //             and e.breakend is not null 
            //         then 'Present'
            //         when 
            //             cast(d.timestamp as time) > dateadd(minute, -30, e.breakend)
            //             and cast(d.timestamp as time) < dateadd(minute, 6, e.breakend)
            //             and d.type = 'PM IN' 
            //             and e.breakend is not null 
            //         then 'Present'
            //         when 
            //             cast(d.timestamp as time) >= dateadd(minute, 6, e.breakend)
            //             and cast(d.timestamp as time) <= dateadd(minute, 15, e.breakend)
            //             and d.type = 'PM IN' 
            //             and e.breakend is not null 
            //         then 'Late'
            //         when 
            //             cast(d.timestamp as time) >= dateadd(minute, 30, e.breakend)
            //             and cast(d.timestamp as time) < e.endtime
            //             and d.type = 'PM OUT'
            //         then 'Undertime'
            //         when 
            //             cast(d.timestamp as time) > e.endtime
            //             and cast(d.timestamp as time) <= dateadd(hour, 1, e.endtime)
            //             and d.type = 'PM OUT'
            //         then 'Present'
            //         when 
            //             cast(d.timestamp as time) > dateadd(hour, 1, e.endtime)
            //             and cast(d.timestamp as time) <= '23:59:59'
            //             and d.type = 'PM OUT' 
            //         then 'Present'
            //         when 
            //             d.type is null or d.type = 'AMBIGUOS' 
            //         then null
            //         else 'Absent'
            //     end as status,
            //     case
            //         when 
            //             cast(d.timestamp as time) >= dateadd(minute, 30, e.starttime)
            //             and cast(d.timestamp as time) < e.breakstart
            //             and d.type = 'AM OUT' 
            //             and e.breakstart is not null  
            //         then datediff(minute, cast(d.timestamp as time), e.starttime)  -- UNDERTIME
            //         when 
            //             cast(d.timestamp as time) >= dateadd(minute, 30, e.breakend)  -- UNDERTIME
            //             and cast(d.timestamp as time) < e.endtime
            //             and d.type = 'PM OUT' 
            //         then datediff(minute, cast(d.timestamp as time), e.endtime) -- OVERTIME
            //         when 
            //             status = 'Overtime' 
            //             and d.type = 'PM OUT' 
            //         then datediff(minute, e.breakend, d.timestamp)
            //         else 0
            //     end as underovertime
            //     from attendancedata as d
            //         left join employees as a on a.biometricsuserid = d.biometricuserid
            //         left join employeesdesignations as b on a.id = b.employeeid
            //         left join positions as c on c.id = b.positionid
            //         left join payrollschedules as e on a.payrollscheduleid = e.id
            //     where 
            //         c.department = ? 
            //         and a.dateended is null 
            //         and cast(d.timestamp as date) between ? and ?
            //     order by d.timestamp asc;
            // ", [$position['department'], $date1, $date2]);


            $dateRange = [];
            $start = Carbon::parse($date1);
            $end = Carbon::parse($date2);

            while ($start->lte($end)) {
                $dateRange[] = $start->format('Y-m-d');
                $start->addDay();
            }


            return view('hr_reports.attendance_analysis', [
                'department' => $position['department'],
                'dates' => $dateRange,
                'date1' => $date1,
                'date2' => $date2,
                'employees' => $employees,
                // 'attendanceData' => $attendanceData
            ]);

            // $date = '2025-01-31';
            // return response()->json(
            //     collect($attendanceData)
            //         ->where('id', '268-201210')
            //         // ->where('type', 'AM IN')
            //         ->where('status', 'Present')
            //         ->filter(function ($item) use ($date) {
            //             return Carbon::parse($item->timestamp)->toDateString() === Carbon::parse($date)->toDateString();
            //         })
            // );

        } catch (Exception $e) {
            session()->flash('error', $e ?? "Inputs are invalid");
            // return redirect()->back();
        }
    }

    public function attendanceDataByEmployee(Request $req)
    {
        try {
            $department = Positions::select('department')
            ->where('department', $req->input('department'))
            ->first()['department'];

            if (!$department) {
                throw new Exception('Department invalid.');
            }

            $date1 = Carbon::parse($req->input('date1'))->format('Y-m-d');
            $date2 = Carbon::parse($req->input('date2'))->format('Y-m-d');
            if (Carbon::parse($date1)->greaterThan(Carbon::parse($date2))) {
                throw new Exception('From date must be less than or equal to to date');
            }

            $attendanceData = DB::select("
            select 
                a.id,
                c.department,
                d.timestamp,d.type,
                case
                    when 
                        datename(weekday, cast(d.timestamp as date)) in ('Saturday','Sunday')
                    then 'Present'
                    when 
                        cast(d.timestamp as time) >= '00:00:00' 
                        and cast(d.timestamp as time) <= dateadd(minute, 6, e.starttime)
                        and d.type = 'AM IN'
                    then 'Present'
                    when 
                        cast(d.timestamp as time) > dateadd(minute, 6, e.starttime) 
                        and cast(d.timestamp as time) <= dateadd(minute, 15, e.starttime)
                        and d.type = 'AM IN'
                    then 'Late'
                    when 
                        cast(d.timestamp as time) <= dateadd(minute, 30, e.starttime)
                        and cast(d.timestamp as time) >= e.breakstart
                        and d.type = 'AM OUT' 
                        and e.breakstart is not null 
                    then 'Undertime'
                    when 
                        cast(d.timestamp as time) >= e.breakstart
                        and cast(d.timestamp as time) <= dateadd(minute, 30, e.breakstart)
                        and d.type = 'AM OUT' 
                        and e.breakend is not null 
                    then 'Present'
                    when 
                        cast(d.timestamp as time) > dateadd(minute, -30, e.breakend)
                        and cast(d.timestamp as time) < dateadd(minute, 6, e.breakend)
                        and d.type = 'PM IN' 
                        and e.breakend is not null 
                    then 'Present'
                    when 
                        cast(d.timestamp as time) >= dateadd(minute, 6, e.breakend)
                        and cast(d.timestamp as time) <= dateadd(minute, 15, e.breakend)
                        and d.type = 'PM IN' 
                        and e.breakend is not null 
                    then 'Late'
                    when 
                        cast(d.timestamp as time) >= dateadd(minute, 30, e.breakend)
                        and cast(d.timestamp as time) < e.endtime
                        and d.type = 'PM OUT'
                    then 'Undertime'
                    when 
                        cast(d.timestamp as time) > e.endtime
                        and cast(d.timestamp as time) <= dateadd(hour, 1, e.endtime)
                        and d.type = 'PM OUT'
                    then 'Present'
                    when 
                        cast(d.timestamp as time) > dateadd(hour, 1, e.endtime)
                        and cast(d.timestamp as time) <= '23:59:59'
                        and d.type = 'PM OUT' 
                    then 'Present'
                    when 
                        d.type is null or d.type = 'AMBIGUOS' 
                    then null
                    else 'Absent'
                end as status,
                case
                    when 
                        cast(d.timestamp as time) >= dateadd(minute, 30, e.starttime)
                        and cast(d.timestamp as time) < e.breakstart
                        and d.type = 'AM OUT' 
                        and e.breakstart is not null  
                    then datediff(minute, cast(d.timestamp as time), e.starttime)  -- UNDERTIME
                    when 
                        cast(d.timestamp as time) >= dateadd(minute, 30, e.breakend)  -- UNDERTIME
                        and cast(d.timestamp as time) < e.endtime
                        and d.type = 'PM OUT' 
                    then datediff(minute, cast(d.timestamp as time), e.endtime) -- OVERTIME
                    when 
                        status = 'Overtime' 
                        and d.type = 'PM OUT' 
                    then datediff(minute, e.breakend, d.timestamp)
                    else 0
                end as underovertime
                from attendancedata as d
                    left join employees as a on a.biometricsuserid = d.biometricuserid
                    left join employeesdesignations as b on a.id = b.employeeid
                    left join positions as c on c.id = b.positionid
                    left join payrollschedules as e on a.payrollscheduleid = e.id
                where 
                    c.department = ?
                    and a.dateended is null 
                    and cast(d.timestamp as date) between ? and ?
                order by d.timestamp desc;
            ", [$department, $date1, $date2]);

            // return response()->json(
            //     [
            //         'department' => $department,
            //         'date1' => $date1,
            //         'date2' => $date2,
            //         'data' => $attendanceData
            //     ]
            // , 200);
            return response()->json(
                [
                    'data' => $attendanceData
                ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function getAttendanceExport(Request $request) {
        $department = $request['department']; 
        $date1 = $request['from_date'];
        $date2 = $request['to_date'];

        return Excel::download(new AttendanceDataExport($department, $date1, $date2), 'attendance_data.xlsx');
    }
}
