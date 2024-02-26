<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidBills extends Model
{
    public $table = 'PaidBills';

    protected $connection = 'sqlsrv_billing';

    public $timestamps = false;

    public $fillable = [
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

    protected $casts = [
        'AccountNumber' => 'string',
        'BillNumber' => 'string',
        'ServicePeriodEnd' => 'datetime',
        'PaymentType' => 'string',
        'ORNumber' => 'string',
        'Teller' => 'string',
        'DCRNumber' => 'string',
        'PostingDate' => 'datetime',
        'ORDate' => 'datetime',
        'Form2306' => 'string',
        'Form2307' => 'string'
    ];

    public static array $rules = [
        'BillNumber' => 'nullable|string|max:10',
        'ServicePeriodEnd' => 'required',
        'Power' => 'nullable',
        'Meter' => 'nullable',
        'PR' => 'nullable',
        'Others' => 'nullable',
        'NetAmount' => 'nullable',
        'PaymentType' => 'nullable|string|max:20',
        'ORNumber' => 'nullable|string|max:20',
        'Teller' => 'nullable|string|max:50',
        'DCRNumber' => 'nullable|string|max:20',
        'PostingDate' => 'nullable',
        'PostingSequence' => 'nullable',
        'PromptPayment' => 'nullable',
        'Surcharge' => 'nullable',
        'SLAdjustment' => 'nullable',
        'OtherDeduction' => 'nullable',
        'ORDate' => 'nullable',
        'MDRefund' => 'nullable',
        'Form2306' => 'nullable|string|max:50',
        'Form2307' => 'nullable|string|max:50',
        'Amount2306' => 'nullable',
        'Amount2307' => 'nullable',
        'ServiceFee' => 'nullable',
        'Others1' => 'nullable',
        'Others2' => 'nullable'
    ];

    
}
