<?php
namespace App\Imports;

use App\Models\Bempc;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Facades\DB;
use App\Models\IncentiveDetails;
use App\Models\IDGenerator;

class BEMPCUploads implements WithCalculatedFormulas, ToCollection {

    private $deductionFor, $userId, $deductionSchedule, $releasingType;

    public function __construct($deductionFor, $userId, $deductionSchedule, $releasingType) {
        $this->deductionFor = $deductionFor;
        $this->userId = $userId;
        $this->deductionSchedule = $deductionSchedule;
        $this->releasingType = $releasingType;
    }

    public function collection(Collection $rows) {
        foreach ($rows as $key => $row) {
            if ($key > 0) {
                // DELETE FIRST IF BEMPC DATA EXISTS
                Bempc::where('EmployeeId', $row[0])
                    ->where('DeductionFor', $this->deductionFor)
                    ->where('Year', date('Y'))
                    ->where('DeductionSchedule', $this->deductionSchedule)
                    ->where('ReleaseType', $this->releasingType)
                    ->delete();

                $bempc = Bempc::create([
                    'id' => IDGenerator::generateIDandRandString(),
                    'EmployeeId' => $row[0],
                    'Amount' => $row[2],
                    'DeductionFor' => $this->deductionFor,
                    'UserId' => $this->userId,
                    'DeductionSchedule' => $this->deductionSchedule,
                    'Year' => date('Y'),
                    'ReleaseType' => $this->releasingType,
                ]);

                // UPDATE TOTAL IN INCENTIVES DETAILS
                switch ($this->deductionFor) {
                    case '13th Month Pay - 1st Half':
                        $checkId = DB::table('IncentiveDetails')
                            ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
                            ->whereRaw("Incentives.Year='" . date('Y') . "' AND IncentiveDetails.EmployeeId='" . $row[0] . "' AND Incentives.IncentiveName='" . $this->deductionFor . "' AND Incentives.ReleaseType='" . $this->releasingType . "'")
                            ->select('IncentiveDetails.id', 'IncentiveDetails.SubTotal', 'IncentiveDetails.OtherDeductions', 'IncentiveDetails.NetPay')
                            ->first();

                        if ($checkId != null) {
                            $subTtl = floatval($checkId->SubTotal);
                            $otherDeductions = floatval($checkId->OtherDeductions);
                            $bempcAmnt = $row[2] != null && is_numeric($row[2]) ? floatval($row[2]) : 0;
                            
                            $total = $subTtl - ($otherDeductions + $bempcAmnt);
                            IncentiveDetails::where('id', $checkId->id)
                                ->update(['BEMPC' => $bempcAmnt, 'NetPay' => $total]);
                        }
                        break;

                    case '13th Month Pay - 2nd Half':
                        $checkId = DB::table('IncentiveDetails')
                            ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
                            ->whereRaw("Incentives.Year='" . date('Y') . "' AND IncentiveDetails.EmployeeId='" . $row[0] . "' AND Incentives.IncentiveName='" . $this->deductionFor . "' AND Incentives.ReleaseType='" . $this->releasingType . "'")
                            ->select('IncentiveDetails.id', 'IncentiveDetails.SubTotal', 'IncentiveDetails.OtherDeductions', 'IncentiveDetails.NetPay')
                            ->first();

                        if ($checkId != null) {
                            $subTtl = floatval($checkId->SubTotal);
                            $otherDeductions = floatval($checkId->OtherDeductions);
                            $bempcAmnt = $row[2] != null && is_numeric($row[2]) ? floatval($row[2]) : 0;
                            
                            $total = $subTtl - ($otherDeductions + $bempcAmnt);
                            IncentiveDetails::where('id', $checkId->id)
                                ->update(['BEMPC' => $bempcAmnt, 'NetPay' => $total]);
                        }
                        break;
                    
                    default:
                        $checkId = DB::table('IncentiveDetails')
                            ->leftJoin('Incentives', 'IncentiveDetails.IncentivesId', '=', 'Incentives.id')
                            ->whereRaw("Incentives.Year='" . date('Y') . "' AND IncentiveDetails.EmployeeId='" . $row[0] . "' AND Incentives.IncentiveName='" . $this->deductionFor . "' AND Incentives.ReleaseType='" . $this->releasingType . "'")
                            ->select('IncentiveDetails.id', 'IncentiveDetails.SubTotal', 'IncentiveDetails.OtherDeductions', 'IncentiveDetails.NetPay')
                            ->first();

                        if ($checkId != null) {
                            $subTtl = floatval($checkId->SubTotal);
                            $otherDeductions = floatval($checkId->OtherDeductions);
                            $bempcAmnt = $row[2] != null && is_numeric($row[2]) ? floatval($row[2]) : 0;
                            
                            $total = $subTtl - ($otherDeductions + $bempcAmnt);
                            IncentiveDetails::where('id', $checkId->id)
                                ->update(['BEMPC' => $bempcAmnt, 'NetPay' => $total]);
                        }
                        break;
                }
            }
        }
    }
}