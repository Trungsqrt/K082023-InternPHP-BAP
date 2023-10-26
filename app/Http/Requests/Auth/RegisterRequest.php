<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class RegisterRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|base64',
            'password_confirm' => 'required|base64|same:password',
        ];
    }

    /**
     * Returns an array of validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'authentication',
            'email.email' => 'email_format',
            'password.base64' => 'authentication',
            'password.required' => 'field_required.password_field_required',
            'first_name' => 'field_required.fname_field_required',
            'last_name' => 'field_required.lname_field_required',
            'password_confirm.required' => 'field_required.password_field_required',
            'password_confirm.same' => 'password_confirm_invalid',
        ];
    }
}
