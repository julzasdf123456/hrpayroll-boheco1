<?php

namespace App\Http\Requests;

use App\Models\IncentivesYearEndDetails;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIncentivesYearEndDetailsRequest extends FormRequest
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
        $rules = IncentivesYearEndDetails::$rules;
        
        return $rules;
    }
}
