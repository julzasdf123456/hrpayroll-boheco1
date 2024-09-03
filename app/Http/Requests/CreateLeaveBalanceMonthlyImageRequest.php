<?php

namespace App\Http\Requests;

use App\Models\LeaveBalanceMonthlyImage;
use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveBalanceMonthlyImageRequest extends FormRequest
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
        return LeaveBalanceMonthlyImage::$rules;
    }
}
