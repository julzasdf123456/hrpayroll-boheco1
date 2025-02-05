<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendaneConfirmations extends Model
{
    public $table = 'AttendanceConfirmations';

    public $fillable = [
        'id',
        'EmployeeId',
        'Reason',
        'AMIn',
        'AMOut',
        'PMIn',
        'PMOut',
        'OTIn',
        'OTOut',
        'Status',
        'Notes',
        'UserId',
    ];

    protected $casts = [
        'id' => 'string',
        'EmployeeId' => 'string',
        'Reason' => 'string',
        'AMIn' => 'datetime',
        'AMOut' => 'datetime',
        'PMIn' => 'datetime',
        'PMOut' => 'datetime',
        'OTIn' => 'datetime',
        'OTOut' => 'datetime',
        'Status' => 'string',
        'Notes' => 'string',
        'UserId' => 'string',
    ];

    public static array $rules = [
        'EmployeeId' => 'nullable|string|max:50',
        'Reason' => 'nullable|string|max:2000',
        'AMIn' => 'nullable',
        'AMOut' => 'nullable',
        'PMIn' => 'nullable',
        'PMOut' => 'nullable',
        'OTIn' => 'nullable',
        'OTOut' => 'nullable',
        'Status' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'UserId' => 'nullable|string',
    ];

    public static function validateTimeSlot($time, $timeSlot, $attConfColumn) {
        if ($timeSlot != null) {
            if ($timeSlot === $attConfColumn) {
                return $time;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
