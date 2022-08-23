<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:190',
            'profile_display_name' => 'required|string|max:190',
            'profile_phone' => 'nullable|numeric',
            'profile_telegram' => 'nullable|string|max:190',
            'profile_dob' => 'nullable|date_format:m/d/Y',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'profile_dob.date_format' => __("Enter date of birth in this 'mm/dd/yyyy' format."),
        ];
    }
}
