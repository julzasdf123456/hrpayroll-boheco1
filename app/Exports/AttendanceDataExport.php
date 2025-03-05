<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;


class AttendanceDataExport implements FromCollection
{

    protected $department;
    protected $date1;
    protected $date2;

    // Constructor to accept department, date1, and date2
    public function __construct($department, $date1, $date2)
    {
        $this->department = $department;
        $this->date1 = $date1;
        $this->date2 = $date2;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $attendance = DB::select("
            select 
                a.id,
                c.department,
                d.timestamp,d.type,
                case
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
                order by d.timestamp asc;
            ", [$this->department, $this->date1, $this->date2]);

            return collect($attendance);
    }
}
