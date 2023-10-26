<?php

namespace App\Http\Requests;

class PresignUrlRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fileName' => 'required|string',
            'folderPath' => 'required|string',
            'contentType' => 'required|string'
        ];
    }
}
