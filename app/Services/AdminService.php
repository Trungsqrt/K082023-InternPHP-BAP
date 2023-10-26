<?php

namespace App\Services;

use DateTimeZone;
use Carbon\Carbon;
use App\Utils\AppError;
use App\Models\{
    Seminar,
    Seminar_Icon,
    Questionnaire,
    Seminar_Image,
    Seminar_Details,
    Seminar_Mail_Info,
    Seminar_Application,
    Questionnaire_Question
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class AdminService
{
    protected $seminar;
    protected $image;
    protected $icons;
    protected $application;
    protected $seminar_icon;
    protected $seminar_detail;
    protected $seminar_mail;

    // AdminService
    public function __construct(
        Seminar $seminar,
        Seminar_Image $image,
        Seminar_Application $application,
        Seminar_Icon $seminar_icon,
        Seminar_Details $seminar_detail,
        Seminar_Mail_Info $seminar_mail
    ) {
        $this->seminar = $seminar;
        $this->image = $image;
        $this->application = $application;
        $this->seminar_icon = $seminar_icon;
        $this->seminar_detail = $seminar_detail;
        $this->seminar_mail = $seminar_mail;
    }

    /**
     * Retrieves a list of seminars based on the provided keyword and category.
     *
     * @param string|null $keyword The keyword to filter by (optional).
     * @param string|null $category The category to filter by (optional).
     * @throws AppError Throws an AppError if there is an error retrieving the seminars.
     * @return array Returns an array of seminars.
     */
    public function getSeminars(string |null $keyword, string | null $category): AppError|array
    {
        $currentDateTime = Carbon::now();

        $seminars = $this->seminar->with('applications')
            ->where("PUBLICATION_START_DATE_TIME", "<=", $currentDateTime)
            ->where('PUBLICATION_END_DATE_TIME', '>=', $currentDateTime)
            ->when($keyword, function ($query) use ($keyword) {
                $query->filterByKeyword($keyword);
            })
            ->when($category, function ($query) use ($category) {
                $query->filterByCategory($category);
            })->orderBy('SEMINAR_ID', 'desc')->get()->toArray();

        return $this->listSeminar($seminars);
    }

    /**
     * Retrieves a list of seminar data based on the given array of datas.
     *
     * @param array $datas The array of datas to filter the seminar data.
     * @return AppError|array The list of seminar data or an instance of AppError if no data is found.
     */
    public function listSeminar(array $datas): AppError|array
    {

        if (count($datas) == 0)
            return new AppError(config('error.seminar_notfound'));

        $seminarData = [];
        foreach ($datas as $data) {

            $seminarData[] = [
                'seminar_title' => $data['SEMINAR_TITLE'],
                'is_hall_seminar' => $data['IS_HALL_SEMINAR'] == 1 ? true : false,
                'is_online_seminar' => $data['IS_ONLINE_SEMINAR'] == 1 ? true : false,
                'seminar_id' => $data['SEMINAR_ID'],
                'list_overview' => $data['LIST_OVERVIEW'],
                'event_startdate' => $data['EVENT_STARTDATE'],
                'event_enddate' => $data['EVENT_ENDDATE'],
                'publication_start_date_time' => $data['PUBLICATION_START_DATE_TIME'],
                'publication_end_date_time' => $data['PUBLICATION_END_DATE_TIME'],

                'seminar_application' => $this->applicationCount($data['applications']),
            ];
        }

        return $seminarData;
    }

    /**
     * Counts the number of applications per category.
     *
     * @param array $applications The array of applications.
     * @return array Returns an array containing the count of applications per category.
     */
    private function applicationCount(array $applications)
    {
        $applicationCounts = [];

        foreach ($applications as $application) {
            $category = $application['SEMINAR_APPLICATION_CATEGORY'];
            if (!isset($applicationCounts[$category])) {
                $applicationCounts[$category] = 0;
            }
            $applicationCounts[$category]++;
        }

        $applications = array_map(function ($category, $count) {
            return [
                'seminar_application_category' => $category,
                'seminar_application_member' => $count,
            ];
        }, array_keys($applicationCounts), array_values($applicationCounts));

        return $applications;
    }

    /**
     * Create a seminar using the provided data.
     *
     * @param array $data The data for creating the seminar.
     * @throws AppError|array The error or array of seminar details.
     * @return AppError|array The error or array of seminar details.
     */
    public function createSeminar(array $data): AppError|array
    {
        try {
            DB::beginTransaction();

            // Check if the publication period is valid
            if ($data['publication_end_date_time'] < Carbon::now(new DateTimeZone('Asia/Ho_Chi_Minh'))) {
                return new AppError(config('error.seminar_publication_period'));
            }

            if (
                $data['publication_end_date_time'] < $data['publication_start_date_time']
                || $data['event_enddate'] < $data['event_startdate']
            ) {
                return new AppError(config('error.seminar_publication_period'));
            }

            $questionnaire = $this->createQuestionnaire();
            $questionnaireQuestion = $this->createQuestionnaireQuestion($data, $questionnaire);
            $entrySeminar = $this->createEntrySeminar($data, $questionnaire);
            $icons = $this->createIcons($data, $entrySeminar);
            $images = $this->createImages($data, $entrySeminar);
            $details = $this->createSeminarDetails($data, $entrySeminar);
            $mail_info = $this->createMailInfo($data, $entrySeminar);

            DB::commit();

            return [
                'event_startdate' => $entrySeminar['EVENT_STARTDATE'],
                'event_enddate' => $entrySeminar['EVENT_ENDDATE'],
                'publication_start_date_time' => $entrySeminar['PUBLICATION_START_DATE_TIME'],
                'publication_end_date_time' => $entrySeminar['PUBLICATION_END_DATE_TIME'],
                'seminar_title' => $entrySeminar['SEMINAR_TITLE'],
                'is_hall_seminar' => (bool)$entrySeminar['IS_HALL_SEMINAR'],
                'is_online_seminar' => (bool)$entrySeminar['IS_ONLINE_SEMINAR'],
                'seminar_maximum_participant' => $entrySeminar['SEMINAR_MAXIMUM_PARTICIPANT'],

                'seminar_attribute' => array_map(function ($icon) {
                    return ['icon_id' => $icon['ICON_ID']];
                }, $icons),
                'seminar_images' => array_map(function ($image) {
                    return [
                        'file_name' => $image['FILE_NAME'],
                        'file_path' => $image['FILE_PATH']
                    ];
                }, $images),
                'seminar_details' => [
                    'headline' => $details['HEADLINE'],
                    'content' => $details['CONTENTS']
                ],
                'seminar_emails' => [
                    'optional_message_hall' => $mail_info['OPTIONAL_MESSAGE_HALL'],
                    'optional_message_online' => $mail_info['OPTIONAL_MESSAGE_ONLINE']
                ],
                'seminar_questions' => array_map(function ($question) {
                    return [
                        'is_required_answer' => $question['IS_REQUIRED_ANSWER'],
                        'questionnaire_cd_format' => (string)$question['QUESTIONNAIRE_CDFORMAT'],
                        'questionnaire_question' => $question['QUESTIONNAIRE_QUESTION'],
                        'answer' => $question['ANSWER']
                    ];
                }, $questionnaireQuestion)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Creates a questionnaire and returns the created object.
     *
     * @return object The created questionnaire object.
     */
    private function createQuestionnaire(): object
    {
        $id = auth()->guard('adminapi')->user()->EMPLOYEE_ID;

        $questionnaire = Questionnaire::create([
            'CREATE_PERSON_ID' => $id
        ]);
        return $questionnaire;
    }

    /**
     * Creates a questionnaire question.
     *
     * @param array $data The data to be used for creating the questionnaire question.
     * @param object $questionnaire The questionnaire object.
     * @throws AppError|array An AppError object or an array.
     * @return AppError|array An AppError object or an array.
     */
    private function createQuestionnaireQuestion(array $data, object $questionnaire): AppError|array
    {
        $id = auth()->guard('adminapi')->user()->EMPLOYEE_ID;
        // create questionnaire_question
        $questionnaire_question = [];
        foreach ($data['seminar_questions'] as $question) {
            $questionnaire_question[] = [
                'QUESTIONNAIRE_ID' => $questionnaire->QUESTIONNAIRE_ID,
                'IS_REQUIRED_ANSWER' => $question['is_required_answer'],
                'QUESTIONNAIRE_CDFORMAT' => intval($question['questionnaire_cd_format']),
                'QUESTIONNAIRE_QUESTION' => $question['questionnaire_question'],
                "ANSWER" => $question['answer'],
                'CREATE_PERSON_ID' => $id
            ];
        }
        Questionnaire_Question::insert($questionnaire_question);

        return $questionnaire_question;
    }

    /**
     * Creates a new entry in the seminar table.
     *
     * @param array $data The data used to create the seminar entry.
     * @param mixed $questionnaire The questionnaire associated with the seminar.
     * @return object The newly created seminar object.
     */
    private function createEntrySeminar(array $data, $questionnaire): object
    {
        $id = auth()->guard('adminapi')->user()->EMPLOYEE_ID;
        // create seminar
        $seminar = Seminar::create([
            'EVENT_STARTDATE' => $data['event_startdate'],
            'EVENT_ENDDATE' => $data['event_enddate'],
            'PUBLICATION_START_DATE_TIME' => $data['publication_start_date_time'],
            'PUBLICATION_END_DATE_TIME' => $data['publication_end_date_time'],
            'SEMINAR_TITLE' => $data['seminar_title'],
            'IS_HALL_SEMINAR' => $data['is_hall_seminar'] == true ? 1 : 0,
            'IS_ONLINE_SEMINAR' => $data['is_online_seminar'] == true ? 1 : 0,
            'SEMINAR_MAXIMUM_PARTICIPANT' => intval($data['seminar_maximum_participant']),
            'QUESTIONNAIRE_ID' => $questionnaire->QUESTIONNAIRE_ID,
            'CREATE_PERSON_ID' => $id
        ]);

        return $seminar;
    }

    /**
     * Creates icons based on the given data and seminar.
     *
     * @param array $data The data used to create the icons.
     * @param object $seminar The seminar object.
     * @throws AppError|array If an error occurs during the icon creation process.
     * @return AppError|array The created icons.
     */
    private function createIcons(array $data, object $seminar): AppError|array
    {
        $id = auth()->guard('adminapi')->user()->EMPLOYEE_ID;
        $icons = [];
        foreach ($data['seminar_attribute'] as $icon) {
            $icons[] = [
                'SEMINAR_ID' => $seminar->SEMINAR_ID,
                'ICON_ID' => $icon['icon_id'],
                'CREATE_PERSON_ID' => $id
            ];
        }
        Seminar_Icon::insert($icons);

        return $icons;
    }

    /**
     * Create seminar images.
     *
     * @param array $data The data array containing seminar images.
     * @param object $seminar The seminar object.
     * @throws AppError|array If an error occurs during image creation or if the input data is invalid.
     * @return AppError|array The created images.
     */
    private function createImages(array $data, object $seminar): AppError|array
    {
        $id = auth()->guard('adminapi')->user()->EMPLOYEE_ID;
        /**
         * IMAGE_CATEGORY will be set:
         *      14 to first four images (with general category 1:pc(一覧) - 4:sp(一覧))
         *      25 to one next image (with main category 2:pc(メイン) - 5:sp(メイン))
         *      36 to last image(if exist) (with sub category 3:pc(サブ) - 6:sp(サブ))
         */

        /**
         * DISPLAY_ORDER will be set:
         *      1 -> 4 to first four images
         *      1 to next image
         *      1 to last image(if exist)
         */
        $displayOrder = 1;

        foreach ($data['seminar_images'] as $index => $image) {
            if ($image !== null) {

                // Reset the displayOrder for the 5th and 6th image
                if ($index == 4 || $index == 5) {
                    $displayOrder = 1;
                }
                // 'IMAGE_CATEGORY' will carry either 14, 25 or 36 depending on the key value
                $imageCategory = ($index < 4) ? 14 : ($index == 4 ? 25 : 36);

                $imagesPC[] = [
                    'SEMINAR_ID' => $seminar->SEMINAR_ID,
                    'FILE_NAME' => $image['file_name'],
                    'FILE_PATH' => $image['file_path'],
                    'IMAGE_CATEGORY' => intval($imageCategory / 10),
                    'DISPLAY_ORDER' => $displayOrder,
                    'CREATE_PERSON_ID' => $id
                ];

                $imagesSP[] = [
                    'SEMINAR_ID' => $seminar->SEMINAR_ID,
                    'FILE_NAME' => $image['file_name'],
                    'FILE_PATH' => $image['file_path'],
                    'IMAGE_CATEGORY' => intval($imageCategory % 10),
                    'DISPLAY_ORDER' => $displayOrder,
                    'CREATE_PERSON_ID' => $id
                ];

                if ($index < 4)
                    $displayOrder += 1;
            }
        }
        Seminar_Image::insert($imagesPC);
        Seminar_Image::insert($imagesSP);

        return $imagesPC;
    }

    /**
     * Creates seminar details.
     *
     * @param array $data The data for creating seminar details.
     * @param object $seminar The seminar object.
     * @throws Some_Exception_Class Description of exception.
     * @return object The created seminar details object.
     */
    private function createSeminarDetails(array $data, object $seminar): object
    {
        $id = auth()->guard('adminapi')->user()->EMPLOYEE_ID;

        // create seminar details
        $details = Seminar_Details::create([
            'SEMINAR_ID' => $seminar->SEMINAR_ID,
            'HEADLINE' => $data['seminar_details']['headline'],
            'CONTENTS' => $data['seminar_details']['content'],
            'CREATE_PERSON_ID' => $id
        ]);

        return $details;
    }

    /**
     * Creates a mail info object based on the provided data and seminar object.
     *
     * @param array $data An array containing the data for creating the mail info.
     * @param object $seminar The seminar object for which the mail info is created.
     * @return object The created mail info object.
     */
    private function createMailInfo(array $data, object $seminar): object
    {
        $id = auth()->guard('adminapi')->user()->EMPLOYEE_ID;
        // create seminar mail
        $mail_info = Seminar_Mail_Info::create([
            'SEMINAR_ID' => $seminar->SEMINAR_ID,
            'MAIL_CATEGORY' =>  random_int(1, 3),
            'OPTIONAL_MESSAGE_HALL' => $data['seminar_emails']['optional_message_hall'],
            'OPTIONAL_MESSAGE_ONLINE' => $data['seminar_emails']['optional_message_online'],
            'CREATE_PERSON_ID' => $id

        ]);

        return $mail_info;
    }

    /**
     * Deletes a seminar based on the given data.
     *
     * @param array $data An array containing the data of the seminar to be deleted.
     * @throws \Exception If an error occurs during the deletion process.
     * @return AppError|string A string indicating the success of the deletion or an AppError object if the seminar is not found.
     */
    public function deleteSeminar(array $data): AppError|string
    {
        $ids = Arr::flatten($data);

        try {
            DB::beginTransaction();

            $existingIds = $this->seminar->whereIn('SEMINAR_ID', $ids)
                ->where('IS_DELETE', 0)
                ->pluck('SEMINAR_ID')
                ->toArray();

            $missingIds = array_diff($ids, $existingIds);

            if (!empty($missingIds)) {
                return new AppError(config('error.seminar_notfound'));
            }

            $this->seminar->select('SEMINAR_ID')->whereIn('SEMINAR_ID', $existingIds)->update(['IS_DELETE' => 1]);
            $this->seminar_icon->select('SEMINAR_ID')->whereIn('SEMINAR_ID', $existingIds)->update(['IS_DELETE' => 1]);
            $this->application->select('SEMINAR_ID')->whereIn('SEMINAR_ID', $existingIds)->update(['IS_DELETE' => 1]);
            $this->seminar_detail->select('SEMINAR_ID')->whereIn('SEMINAR_ID', $existingIds)->update(['IS_DELETE' => 1]);
            $this->image->select('SEMINAR_ID')->whereIn('SEMINAR_ID', $existingIds)->update(['IS_DELETE' => 1]);
            $this->seminar_mail->select('SEMINAR_ID')->whereIn('SEMINAR_ID', $existingIds)->update(['IS_DELETE' => 1]);

            DB::commit();

            return "Delete successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
