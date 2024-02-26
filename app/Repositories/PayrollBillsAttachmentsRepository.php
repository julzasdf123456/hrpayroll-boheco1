<?php

namespace App\Repositories;

use App\Models\PayrollBillsAttachments;
use App\Repositories\BaseRepository;

class PayrollBillsAttachmentsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'EmployeeId',
        'PayrollId',
        'BillingMonth',
        'Amount',
        'Surcharges'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PayrollBillsAttachments::class;
    }
}
