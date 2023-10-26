<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiRequest;

class ApplySeminarRequest extends ApiRequest
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
            'member_id' => 'required|integer',
            'seminar_application_category' => 'required|in:1,2',
        ];
    }
}
