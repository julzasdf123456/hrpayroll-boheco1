<?php

namespace App\Http\Controllers;

use App\Models\Positions;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class HrReportsController extends Controller
{
    public function attendanceForm()
    {

        $departmentData = Positions::select('department')->distinct()->get()->pluck('department')->unique()->toArray();

        $departments = array_map(function ($item){
            return $item;
        }, 
        $departmentData);

        return view('hr_reports.attendance_form', [ 'departments' => $departments ]);
    }

    public function attendanceByDepartment(Request $req) {
        try {
            $position = Positions::select('department')
                ->where('department', $req->input('department'))
                ->first();
            $date1 = Carbon::parse($req->input('from_date'))->format('Y-m-d');
            $date2 = Carbon::parse($req->input('to_date'))->format('Y-m-d');
            if (Carbon::parse($date1)->greaterThan(Carbon::parse($date2))) {
                throw new Exception('From date must be less than or equal to to date');
            }
            \Log::info("Positions: ". $position);

            $employees = DB::select("
            select 
                a.id,a.firstname,a.middlename,a.lastname,
                c.position, c.department
            from employees as a
                left join employeesdesignations as b on a.id = b.employeeid
                left join positions as c on c.id = b.positionid
            where c.department = ? order by a.lastname;
            ",[$position['department']]);


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
                'employees' => $employees
            ]);

        } catch (Exception $e) {
            session()->flash('error', $e ?? "Inputs are invalid");
            // return redirect()->back();
        }


    }

    public function attendanceDataByEmployee() {

    }
}
