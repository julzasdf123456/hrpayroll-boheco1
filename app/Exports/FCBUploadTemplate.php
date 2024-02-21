<?php

namespace App\Exports;

use App\Models\Employees;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Facades\DB;

class FCBUploadTemplate implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles, WithEvents, WithCustomStartCell
{
    private $data, $totalPayroll, $payrollDate;

    public function __construct(array $data, $totalPayroll, $payrollDate)
    {
        $this->data = $data;
        $this->totalPayroll = $totalPayroll;
        $this->payrollDate = $payrollDate;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function headings(): array
    {
        return [
            'ACCTNO',
            'ACCTNAME',
            'AMOUNT',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            'A1:C3' => [
                'font' => ['bold' => true],
                // 'alignment' => ['horizontal' => 'center'],
            ],
            // 2 => [
            //     'alignment' => ['horizontal' => 'center'],
            // ],
            // 4 => [
            //     'font' => ['bold' => true],
            //     'alignment' => ['horizontal' => 'center'],
            // ],
            // 7 => [
            //     'font' => ['bold' => true],
            //     'alignment' => ['horizontal' => 'center'],
            // ],
        ];
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                // $event->sheet->mergeCells(sprintf('A1:E1'));
                // $event->sheet->mergeCells(sprintf('A2:E2'));

                // $event->sheet->mergeCells(sprintf('A4:E4'));
                
                $event->sheet->setCellValue('A1', 'MERCHANT NAME:');
                $event->sheet->setCellValue('B1', 'boheco1');
                $event->sheet->setCellValue('C1', 'TOTAL PAYROLL');

                $event->sheet->setCellValue('A2', 'PAYROLL DATE:');
                $event->sheet->setCellValue('B2', date('m/d/Y', strtotime($this->payrollDate)));
                $event->sheet->setCellValue('C2', $this->totalPayroll);

                $event->sheet->setCellValue('A' . (count($this->data)+4), 'end here');
                $event->sheet->setCellValue('B' . (count($this->data)+4), 'end here');
                $event->sheet->setCellValue('C' . (count($this->data)+4), 'end here');
            }
        ];
    }
}