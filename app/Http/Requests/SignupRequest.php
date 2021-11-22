<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            "name" => "required|not_regex:/[!@#$%^&*()_=+-\{\}\[\]\\,.\/?><:;']+/i",
            "email" => "required|email:filter",
            "password" => "required|min:8|alpha_dash|confirmed",
            "jwt" => "present",
            "verificationToken" => "present",
            "isVerified" => "present"
        ];
    }
}
