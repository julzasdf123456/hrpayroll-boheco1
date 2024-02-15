<?php
namespace App\Imports;

use App\Models\Bempc;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\IDGenerator;

class BEMPCUploads implements WithCalculatedFormulas, ToCollection {

    private $deductionFor, $userId, $deductionSchedule;

    public function __construct($deductionFor, $userId, $deductionSchedule) {
        $this->deductionFor = $deductionFor;
        $this->userId = $userId;
        $this->deductionSchedule = $deductionSchedule;
    }

    public function collection(Collection $rows) {
        foreach ($rows as $key => $row) {
            if ($key > 0) {
                $bempc = Bempc::create([
                    'id' => IDGenerator::generateIDandRandString(),
                    'EmployeeId' => $row[0],
                    'Amount' => $row[2],
                    'DeductionFor' => $this->deductionFor,
                    'UserId' => $this->userId,
                    'DeductionSchedule' => $this->deductionSchedule,
                ]);
            }
        }
    }
}