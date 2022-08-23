<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'profile_address_line_1' => 'required|string|max:190',
            'profile_address_line_2' => 'nullable|string|max:190|',
            'profile_city' => 'nullable|string|max:190',
            'profile_zip' => 'nullable|string|max:50',
            'profile_state' => 'required|string|max:190',
            'profile_country' => 'required|string|max:190',
            'profile_nationality' => 'nullable|string|max:190',
        ];
    }
}
