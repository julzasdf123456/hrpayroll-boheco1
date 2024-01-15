<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripTickets extends Model
{
    public $table = 'TripTickets';

    public $fillable = [
        'id',
        'DatetimeFiled',
        'EmployeeId',
        'PurposeOfTravel',
        'Driver',
        'Status',
        'DatetimeDeparted',
        'DatetimeArrived',
        'UserId',
        'DateOfTravel',
        'Vehicle',
        'RequestGRS',
    ];

    protected $casts = [
        'id' => 'string',
        'DatetimeFiled' => 'datetime',
        'EmployeeId' => 'string',
        'PurposeOfTravel' => 'string',
        'Driver' => 'string',
        'Status' => 'string',
        'DatetimeDeparted' => 'datetime',
        'DatetimeArrived' => 'datetime',
        'UserId' => 'string',
        'DateOfTravel' => 'string',
        'Vehicle' => 'string',
        'RequestGRS' => 'string',
    ];

    public static array $rules = [
        'DatetimeFiled' => 'nullable',
        'EmployeeId' => 'nullable|string|max:50',
        'PurposeOfTravel' => 'nullable|string|max:3000',
        'Driver' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'DatetimeDeparted' => 'nullable',
        'DatetimeArrived' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'UserId' => 'nullable|string',
        'DateOfTravel' => 'nullable|string',
        'Vehicle' => 'nullable|string',
        'RequestGRS' => 'nullable|string',
    ];

    public static function getBgStatus($status) {
        if ($status == null | $status == 'FILED') {
            return 'bg-primary';
        } elseif ($status == 'APPROVED') {
            return 'bg-success';
        } else {
            return 'bg-danger';
        }
    }
}
