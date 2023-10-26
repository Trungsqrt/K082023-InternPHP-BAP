<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiRequest;

class GetDetailSeminarRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'seminar_id' => 'required|integer',
            'user_agent' => 'required|in:pc,sp',
        ];
    }

    /**
     * Retrieve the validation error messages.
     *
     * @return array <string, string>
     */
    public function messages()
    {
        return [
            'seminar_id.required' => 'missing_parameters',
            'user_agent.required' => 'missing_parameters',
            'user_agent.in' => 'invalid_user_agent',
        ];
    }
}
