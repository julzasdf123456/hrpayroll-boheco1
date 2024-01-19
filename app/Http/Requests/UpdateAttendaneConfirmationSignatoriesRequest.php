<?php

namespace App\Http\Requests;

use App\Models\AttendaneConfirmationSignatories;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendaneConfirmationSignatoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = AttendaneConfirmationSignatories::$rules;
        
        return $rules;
    }
}
