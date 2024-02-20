<?php

namespace App\Http\Requests;

use App\Models\LeaveConversions;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveConversionsRequest extends FormRequest
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
        $rules = LeaveConversions::$rules;
        
        return $rules;
    }
}
