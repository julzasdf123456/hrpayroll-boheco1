<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Notifications
 * @package App\Models
 * @version November 21, 2021, 7:03 am UTC
 *
 * @property string $UserId
 * @property string $Type
 * @property string $Content
 * @property string $Notes
 */
class Notifications extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Notifications';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'UserId',
        'Type',
        'Content',
        'Notes',
        'Status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'UserId' => 'string',
        'Type' => 'string',
        'Content' => 'string',
        'Notes' => 'string',
        'Status' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',     
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'UserId' => 'nullable|string|max:255',
        'Type' => 'nullable|string|max:100',
        'Content' => 'nullable|string|max:2000',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable|string',
        'updated_at' => 'nullable|string',        
        'Status' => 'string|nullable'
    ];

    public static function assessNotificationRoute($type, $id, $notifId) {
        if ($type == 'LEAVE_APPROVAL') {
            return route('leaveApplications.approvals', [$id]);
        } elseif ($type == 'LEAVE_INFO') {
            return route('leaveApplications.show', [$id]);
        } elseif ($type == 'PAYROLL_INFO') {
            return route('payrollIndices.payslip', [$id]);
        } elseif($type == 'INFO') {
            return route('notifications.show', [$notifId]);
        }
    }
}
