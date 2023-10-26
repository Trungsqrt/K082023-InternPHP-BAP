<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class LoginRequest extends ApiRequest
{
    /**
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|base64',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'authentication',
            'email.email' => 'email_format',
            'password.required' => 'field_required.password_field_required',
            'password.base64' => 'authentication',
        ];
    }
}
