<?php

namespace App\Http\Requests;

use App\Models\EmployeeBonuses;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeBonusesRequest extends FormRequest
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
        return EmployeeBonuses::$rules;
    }
}
