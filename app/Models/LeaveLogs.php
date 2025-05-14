<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveLogs extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'LeaveLogs';

    protected $fillable = [
        'UserId',
        'LeaveType',
        'PreviousBalance',
        'NowBalance',
        'GapFromBalances',
    ];

    public static $rules = [
        "create_leave_log" => [
            'UserId' => 'string|nullable',
            'LeaveType' => 'number|nullable',
            'PreviousBalance' => 'number|nullable',
            'NowBalance' => 'number|nullable',
            'GapFromBalances' => 'number|nullable',
        ]
    ];
}
