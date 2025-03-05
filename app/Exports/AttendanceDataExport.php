<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;


class AttendanceDataExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, WithEvents
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
        $transposedData = [];

        // Add headings as the first column
        $transposedData[] = [
            'ID',
            'Department',
            'Timestamp',
            'Type',
            'Status',
            'Undertime/Overtime'
        ];

        // Loop through the data and create rows with corresponding data
        foreach ($attendance as $item) {
            $transposedData[] = [
                $item->id,
                $item->department,
                $item->timestamp,
                $item->type,
                // Add your other data fields here
                $item->status,  // Assuming 'status' is part of your query result
                $item->underovertime,  // Assuming 'underovertime' is part of your query result
            ];
        }

        return collect($transposedData); // Return as a collection
    }

    /**
     * Set the headings for the Excel file (left side)
     */
    public function headings(): array
    {
        return [
            'Field Name', // The leftmost column for headings
        ];
    }

    /**
     * Style the headings and other elements of the sheet
     */
    public function styles($sheet)
    {
        // Style the left column headings (bold, centered, background color)
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF00']],
            ],
        ];
    }

    /**
     * Define column formatting (e.g., date formatting)
     */
    public function columnFormats(): array
    {
        return [
            'B' => 'yyyy-mm-dd hh:mm:ss',  // For timestamp column
            'C' => 'text',                  // For the "type" column
            'D' => 'text',                  // For the "status" column
            'E' => '#,##0.00',              // For numeric columns (e.g., overtime/undertime in minutes)
        ];
    }

    /**
     * Additional events to modify sheet after it's generated
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                // Auto-size the leftmost column
                $sheet->getColumnDimension('A')->setAutoSize(true);

                // Auto-size the rest of the columns to fit the data
                foreach (range('B', 'Z') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}