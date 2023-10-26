<?php

namespace App\Services;

use Carbon\Carbon;
use App\Utils\AppError;
use Illuminate\Support\Facades\DB;
use App\Models\{Icon, Seminar, Seminar_Icon, Seminar_Image, Seminar_Application, Seminar_Details, User};


class UserService
{
    protected $seminar;
    protected $image;
    protected $icons;
    protected $application;
    protected $seminar_icon;
    protected $seminar_detail;
    protected $user;

    public function __construct(
        Seminar $seminar,
        Seminar_Image $image,
        Icon $icons,
        Seminar_Application $application,
        Seminar_Icon $seminar_icon,
        Seminar_Details $seminar_detail,
        User $user
    ) {
        $this->seminar = $seminar;
        $this->image = $image;
        $this->icons = $icons;
        $this->application = $application;
        $this->seminar_icon = $seminar_icon;
        $this->seminar_detail = $seminar_detail;
        $this->user = $user;
    }

    /**
     * Retrieves an array of seminars or an AppError object.
     *
     * This function retrieves seminars that meet the following criteria:
     * - The publication start date and time is less than or equal to the current date and time.
     * - The publication end date and time is greater than or equal to the current date and time.
     * 
     * The retrieved seminars are ordered by SEMINAR_ID in descending order.
     *
     * @return array|AppError An array of seminars or an AppError object.
     */
    public function getSeminars(): array|AppError
    {
        $currentDateTime = Carbon::now();

        $seminars = $this->seminar->select(
            'SEMINAR_ID',
            'SEMINAR_TITLE',
            'IS_HALL_SEMINAR',
            'EVENT_STARTDATE',
            'EVENT_ENDDATE',
            'LIST_OVERVIEW',
            'SEMINAR_MAXIMUM_PARTICIPANT'
        )
            ->where("PUBLICATION_START_DATE_TIME", "<=", $currentDateTime)
            ->where('PUBLICATION_END_DATE_TIME', '>=', $currentDateTime)
            ->orderBy('SEMINAR_ID', 'desc')
            ->get()->toArray();

        return $this->listSeminar($seminars);
    }

    /**
     * Retrieves a list of seminars based on the provided data.
     *
     * @param array $datas An array of data used to filter the seminars.
     * @throws AppError|array An error or an array of seminar data.
     * @return array The list of seminars.
     */
    private function listSeminar(array $datas): AppError|array
    {

        if (count($datas) == 0)
            return new AppError(config('error.seminar_notfound'));

        $seminarData = [];
        foreach ($datas as $data) {
            $seminarData[] = [
                'is_hall_seminar' => boolval($data['IS_HALL_SEMINAR'] == 1 ? true : false),
                'images' => $this->getImages($data['SEMINAR_ID'], 1),
                'date' => $this->convertDate($data['EVENT_STARTDATE'], $data['EVENT_ENDDATE']),
                'is_accepting' => $this->seatStatus($data),
                'title' => $data['SEMINAR_TITLE'],
                'summary' => $data['LIST_OVERVIEW'],
                'max_participant' => $data['SEMINAR_MAXIMUM_PARTICIPANT'] ? intval($data['SEMINAR_MAXIMUM_PARTICIPANT']) : 'N/A',
                'icons' => $this->getIcons($data['SEMINAR_ID']),
                'url_to_go' => 'https://<host>/seminar/' . $data['SEMINAR_ID'],
            ];
        }

        return $seminarData;
    }

    /**
     * Retrieves an array of images based on the seminar ID and image category.
     *
     * @param int $seminarId The ID of the seminar.
     * @param int $imageCategory The category of the image.
     * @return array The list of images with their file paths.
     */
    private function getImages(int $seminarId, int $imageCategory): array
    {
        $images = $this->image->select('SEMINAR_ID', 'FILE_NAME', 'FILE_PATH')
            ->where('SEMINAR_ID', $seminarId)
            ->where('IMAGE_CATEGORY', $imageCategory)
            ->get();

        $imagesList = [];
        foreach ($images as $image) {
            $imagesList[] = [
                'file_path' => $image['FILE_PATH'] . '/' . $image['FILE_NAME']
            ];
        }
        return $imagesList;
    }


    /**
     * Retrieves the list of icons associated with a seminar.
     *
     * @param int $seminarId The ID of the seminar.
     * @return array The list of icons with their names.
     */
    private function getIcons(int $seminarId): array
    {
        $iconList = $this->seminar_icon->select('ICON_ID')
            ->with('icon')->where('SEMINAR_ID', $seminarId)->orderBy('ICON_ID')->get();

        $iconListName = [];
        foreach ($iconList as $icon) {
            $iconListName[] = [
                'icon' => $icon['icon']['ICON_NAME']
            ];
        }
        return $iconListName;
    }

    /**
     * Converts the given start and end dates into a formatted event date string.
     *
     * @param string $startDate The start date of the event.
     * @param string $endDate The end date of the event.
     * @return string The formatted event date string.
     */
    private function convertDate(string $startDate, string $endDate): string
    {
        $startDate = Carbon::parse($startDate)->locale('ja_JP');
        $endDate = Carbon::parse($endDate)->locale('ja_JP');

        $formattedStartDate = $startDate->isoFormat('Y年M月D日(ddd)');
        $formattedEndDate = '';

        if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {
            $formattedEndDate = $endDate->isoFormat('D日(ddd)');
        } else {
            $formattedEndDate = $endDate->isoFormat('M月D日(ddd)');
        }

        $eventDate = $formattedStartDate . ' + ' . $formattedEndDate;

        return $eventDate;
    }


    /**
     * Determines the status of a seminar seat.
     *
     * @param array $seminar An array containing information about the seminar.
     *                      The array should include the following keys:
     *                      - 'IS_HALL_SEMINAR': Indicates if the seminar is a hall seminar (1) or not (0).
     *                      - 'SEMINAR_ID': The ID of the seminar.
     *                      - 'SEMINAR_MAXIMUM_PARTICIPANT': The maximum number of participants allowed in the seminar.
     * @return string The status of the seat. Possible values are:
     *                - '満員御礼': Indicates that the seat is full.
     *                - '参加受付中': Indicates that the seat is available for registration.
     */
    private function seatStatus(array $seminar): string
    {
        if (
            $seminar['IS_HALL_SEMINAR'] != 1 ||
            $this->application
            ->select('SEMINAR_APPLICATION_ID')
            ->where('SEMINAR_ID', $seminar['SEMINAR_ID'])
            ->count() >= intval($seminar['SEMINAR_MAXIMUM_PARTICIPANT'])
        ) {
            return '満員御礼';
        };

        return '参加受付中';
    }

    /**
     * Retrieves the details of a seminar.
     *
     * @param mixed ...$payload The payload containing the seminar ID.
     * @throws AppError If an error occurs during the retrieval process.
     * @return AppError|array The seminar details or an error object.
     */
    public function getDetailSeminar(...$payload): AppError | array
    {
        [$seminar_id] = $payload;

        $seminar = $this->seminar->select(
            'SEMINAR_ID',
            'SEMINAR_TITLE',
            'IS_HALL_SEMINAR',
            'IS_ONLINE_SEMINAR',
            'EVENT_STARTDATE',
            'EVENT_ENDDATE',
            'LIST_OVERVIEW',
            'SEMINAR_MAXIMUM_PARTICIPANT',
            'PUBLICATION_START_DATE_TIME',
            'PUBLICATION_END_DATE_TIME',
        )
            ->where('SEMINAR_ID', $seminar_id)
            ->get();

        return $this->getListDetail($seminar, $payload);
    }

    /**
     * Retrieves the details of a list item.
     *
     * @param object $seminar The seminar object.
     * @param mixed $payload The payload data.
     * @throws AppError When the seminar is empty or the publication period is invalid.
     * @return AppError|array The list item details or an error.
     */
    private function getListDetail(object $seminar, $payload): AppError|array
    {
        [$seminar_id, $member_id, $user_agent] = $payload;

        $currentDateTime = Carbon::now();

        if ($seminar->isEmpty()) {
            return new AppError(
                [
                    'code' => 500,
                    'message' => '掲載中のセミナーIDでない。'
                ]
            );
        }
        $seminar = $seminar->first();

        if (
            $seminar->PUBLICATION_START_DATE_TIME <= $currentDateTime
            && $currentDateTime <= $seminar->PUBLICATION_END_DATE_TIME
        ) {
            return [
                'seminar_id' => intval($seminar_id),
                'title' => $seminar['SEMINAR_TITLE'],
                'main_image_file' =>  $user_agent == 'pc' ?
                    (!empty($this->getImages($seminar_id, 2)) ? $this->getImages($seminar_id, 2)[0]['file_path'] : "") : (!empty($this->getImages($seminar_id, 5)) ? $this->getImages($seminar_id, 5)[0]['file_path'] : ""),

                'date' => $this->convertDate($seminar['EVENT_STARTDATE'], $seminar['EVENT_ENDDATE']),
                'is_hall_seminar' => boolval($seminar['IS_HALL_SEMINAR'] == 1 ? true : false),
                'is_online_seminar' => boolval($seminar['IS_ONLINE_SEMINAR'] == 1 ? true : false),
                'is_accepting' => $this->seatStatus($seminar->toArray()),
                'is_offline_appliciate' => $this->isApplied($seminar_id, $member_id, 1),
                'is_online_appliciate' => $this->isApplied($seminar_id, $member_id, 2),
                'summary' => $seminar['LIST_OVERVIEW'],
                'max_participants' => $seminar['SEMINAR_MAXIMUM_PARTICIPANT'] ? (string)($seminar['SEMINAR_MAXIMUM_PARTICIPANT']) : 'N/A',
                'details' => [
                    [
                        'detail_image_file' => !empty($this->getImages($seminar_id, 3)) ? $this->getImages($seminar_id, 3)[0]['file_path'] : "",
                        'detail_headline' => $this->getDetailitems($seminar_id)['HEADLINE'],
                        'detail_message' => $this->getDetailitems($seminar_id)['CONTENTS'],
                    ]
                ]

            ];
        }

        return new AppError(config('error.seminar_publication_period'));
    }

    /**
     * Checks if the given seminar is applied by the specified member for the given category.
     *
     * @param int $seminarId The ID of the seminar.
     * @param mixed $memberId The ID of the member.
     * @param int $category The category of the seminar application.
     * @return bool Returns true if the seminar is applied by the member for the category, false otherwise.
     */
    private function isApplied(int $seminarId, $memberId, int $category): bool
    {
        return $this->application
            ->select('SEMINAR_APPLICATION_ID')
            ->where('SEMINAR_ID', $seminarId)
            ->where('MEMBER_ID', $memberId)
            ->where('SEMINAR_APPLICATION_CATEGORY', $category)
            ->count() > 0;
    }

    /**
     * Retrieves the detail items for a given seminar ID.
     *
     * @param int $seminarId The ID of the seminar.
     * @return mixed The detail item for the given seminar ID.
     */
    private function getDetailitems(int $seminarId)
    {
        $item =  $this->seminar_detail->select('HEADLINE', 'CONTENTS')
            ->where('SEMINAR_ID', $seminarId)
            ->get()->first();

        return $item;
    }

    /**
     * Apply for a seminar.
     *
     * @param mixed ...$data The data required to apply for a seminar. [seminar_id, member_id, seminar_application_category]
     * @throws AppError|array If the user or seminar does not exist, or if the user has already applied for this seminar.
     * @return AppError|array If the application is successful, return an array with the seminar_id, member_id, and seminar_application_category.
     * @throws \Exception If there is an error during the transaction.
     */
    public function applySeminar(...$data): AppError|array
    {
        [$seminar_id, $member_id, $seminar_application_category] = $data;

        $id = auth()->guard('userapi')->user()->MEMBER_ID;
        // check if member_id is exist and seminar_id is exist and seminar_application_category if it is equal $seminar_application_category
        $user = $this->user->select('MEMBER_ID')->where('MEMBER_ID', $member_id)->get();
        if ($user->isEmpty()) {
            return new AppError([
                'code' => 400,
                'message' => 'User does not exist.'
            ]);
        }

        $seminar = $this->seminar->select('SEMINAR_ID')->where('SEMINAR_ID', $seminar_id)->get();
        if ($seminar->isEmpty()) {
            return new AppError(config('error.seminar_notfound'));
        }

        if ($this->isApplied($seminar_id, $member_id, $seminar_application_category)) {
            return new AppError([
                'code' => 400,
                'message' => 'You have already applied for this seminar.'
            ]);
        }

        try {
            DB::beginTransaction();

            $seminar = $this->application->create([
                'SEMINAR_ID' => $seminar_id,
                'MEMBER_ID' => $member_id,
                'SEMINAR_APPLICATION_CATEGORY' => $seminar_application_category,
                'QUESTIONNAIRE_ANSWER_ID' => random_int(1, 1000),
                'CREATE_PERSON_ID' => $id

            ]);
            DB::commit();

            return [
                'seminar_id' => $seminar_id,
                'member_id' => $member_id,
                'seminar_application_category' => $seminar_application_category
            ];
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }
}
