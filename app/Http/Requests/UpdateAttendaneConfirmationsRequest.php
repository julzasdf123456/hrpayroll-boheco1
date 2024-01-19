<?php

namespace App\Http\Requests;

use App\Models\AttendaneConfirmations;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendaneConfirmationsRequest extends FormRequest
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
        $rules = AttendaneConfirmations::$rules;
        
        return $rules;
    }
}
