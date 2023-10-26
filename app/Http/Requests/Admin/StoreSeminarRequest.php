<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiRequest;

class StoreSeminarRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'publication_start_date_time' => 'required|string',
            'publication_end_date_time' => 'required|string',
            'event_startdate' => 'required|string',
            'event_enddate' => 'required|string',
            'seminar_title' => 'required|string',
            'is_hall_seminar' => 'required|boolean',
            'is_online_seminar' => 'required|boolean',
            'seminar_maximum_participant' => 'string',

            'seminar_attribute.*.icon_id' => 'required|integer',
            'seminar_images.*.file_name' => 'required|string',
            'seminar_images.*.file_path' => 'required|string',
            'seminar_details.headline' => 'string',
            'seminar_details.content' => 'string',
            'seminar_emails.optional_message_hall' => 'string',
            'seminar_emails.optional_message_online' => 'string',
            'seminar_questions.*.is_required_answer' => 'required|boolean',
            'seminar_questions.*.questionnaire_cd_format' => 'required|string',
            'seminar_questions.*.questionnaire_question' => 'required|string',
            'seminar_questions.*.answer' => 'required|string',
        ];
    }
}
