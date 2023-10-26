<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiRequest;

class DeleteSeminarRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'seminars_id' => 'required|array|min:1'
        ];
    }
}
