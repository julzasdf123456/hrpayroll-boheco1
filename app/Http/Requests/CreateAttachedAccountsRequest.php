<?php

namespace App\Http\Requests;

use App\Models\AttachedAccounts;
use Illuminate\Foundation\Http\FormRequest;

class CreateAttachedAccountsRequest extends FormRequest
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
        return AttachedAccounts::$rules;
    }
}
