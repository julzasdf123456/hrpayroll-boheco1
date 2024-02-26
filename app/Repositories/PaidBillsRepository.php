<?php

namespace App\Repositories;

use App\Models\PaidBills;
use App\Repositories\BaseRepository;

class PaidBillsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'BillNumber',
        'ServicePeriodEnd',
        'Power',
        'Meter',
        'PR',
        'Others',
        'NetAmount',
        'PaymentType',
        'ORNumber',
        'Teller',
        'DCRNumber',
        'PostingDate',
        'PostingSequence',
        'PromptPayment',
        'Surcharge',
        'SLAdjustment',
        'OtherDeduction',
        'ORDate',
        'MDRefund',
        'Form2306',
        'Form2307',
        'Amount2306',
        'Amount2307',
        'ServiceFee',
        'Others1',
        'Others2'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PaidBills::class;
    }
}
