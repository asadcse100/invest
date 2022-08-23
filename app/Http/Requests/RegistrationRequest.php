<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    private $cbConfirm;

    public function __construct()
    {
        $this->cbConfirm = page_status('terms', true) ? 'required' : 'nullable';
    }

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
        $validate = [
            'name' => 'required|string|max:190',
            'email' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,9}$/ix|max:190|unique:users',
            'password' => 'required|min:6',
            'confirmation' => $this->cbConfirm,
        ];

        if (!empty(sys_settings('signup_form_fields')) && is_array(sys_settings('signup_form_fields'))) {
            foreach (sys_settings('signup_form_fields') as $key => $value) {
                if (($key == 'profile_phone' || $key == 'profile_country') && data_get($value, 'show') == 'yes') {
                    if (data_get($value, 'req') == 'yes') {
                        $validate[$key] = 'required|string';
                    } else {
                        $validate[$key] = 'nullable|string';
                    }
                } elseif ($key == 'profile_dob' && data_get($value, 'show') == 'yes') {
                    if (data_get($value, 'req') == 'yes') {
                        $validate[$key] = 'required|date_format:m/d/Y';
                    } else {
                        $validate[$key] = 'nullable|date_format:m/d/Y';
                    }
                } else {
                    continue;
                }
            }
        }

        return $validate;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'profile_dob.required' => __("Please enter your date of birth."),
            'profile_phone.required' => __("Please enter your valid phone number."),
            'profile_country.required' => __("Please select your country name."),
            'email.unique' => __("An account with the given email already exists."),
            'email.regex' => __("Please enter a valid email address.")
        ];
    }
}
