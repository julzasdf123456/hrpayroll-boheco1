<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\IDGenerator;

class SMSNotifications extends Model
{
    public $table = 'SMSNotifications';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'ContactNumber',
        'Message',
        'Status',
        'Source',
        'SourceId',
        'Notes',
        'AIFacilitator'
    ];

    protected $casts = [
        'id' => 'string',
        'ContactNumber' => 'string',
        'Message' => 'string',
        'Status' => 'string',
        'Source' => 'string',
        'SourceId' => 'string',
        'Notes' => 'string',
        'AIFacilitator' => 'string'
    ];

    public static array $rules = [
        'ContactNumber' => 'nullable|string|max:50',
        'Message' => 'nullable|string|max:1000',
        'Status' => 'nullable|string|max:50',
        'Source' => 'nullable|string|max:50',
        'SourceId' => 'nullable|string|max:90',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Notes' => 'nullable|string|max:500',
        'AIFacilitator' => 'nullable|string|max:50'
    ];

    public static function sendSMS($contacts, $message, $source, $sourceId) {
        if ($contacts != null) {
            
            // check if many numbers
            $contactNos = explode(",", $contacts);

            // loop numbers
            for($i=0; $i<count($contactNos); $i++) {
                $contactNo = trim($contactNos[$i]);
                // check if number is valid (8 digit up)
                if (strlen($contactNo) > 8) {
                    $sms = new SMSNotifications;
                    $sms->id = IDGenerator::generateIDandRandString();
                    $sms->ContactNumber = $contactNo;
                    $sms->Message = $message;
                    $sms->Status = 'PENDING';
                    $sms->AIFacilitator = 'Calisto';
                    $sms->Source = $source;
                    $sms->SourceId = $sourceId;
                    $sms->save();
                }
            }
        }
    }
}
