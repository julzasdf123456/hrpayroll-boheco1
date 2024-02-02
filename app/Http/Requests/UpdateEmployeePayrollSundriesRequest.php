<?php

namespace App\Http\Requests;

use App\Models\EmployeePayrollSundries;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeePayrollSundriesRequest extends FormRequest
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
        $rules = EmployeePayrollSundries::$rules;
        
        return $rules;
    }
}
