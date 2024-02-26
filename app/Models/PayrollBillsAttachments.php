<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollBillsAttachments extends Model
{
    public $table = 'PayrollBillsAttachments';

    public $fillable = [
        'id',
        'EmployeeId',
        'PayrollId',
        'BillingMonth',
        'Amount',
        'Surcharges',
        'AccountNumber',
        'BillAmount',
        'ScheduleDate',
        'Status',
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'PayrollId' => 'string',
        'BillingMonth' => 'date',
        'Amount' => 'decimal:2',
        'Surcharges' => 'decimal:2',
        'AccountNumber' => 'string',
        'BillAmount' => 'decimal:2',
        'ScheduleDate' => 'string',
        'Status' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'PayrollId' => 'nullable|string|max:60',
        'BillingMonth' => 'nullable',
        'Amount' => 'nullable|numeric',
        'Surcharges' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'AccountNumber' => 'nullable|string',
        'BillAmount' => 'nullable|string',
        'ScheduleDate' => 'nullable|string',
        'Status' => 'nullable|string',
    ];

    
}
