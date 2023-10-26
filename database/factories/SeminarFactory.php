<?php

namespace Database\Factories;

use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seminar>
 */
class SeminarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'SEMINAR_TITLE' => fake()->paragraph(1),
            'IS_HALL_SEMINAR' => fake()->boolean(50),
            'IS_ONLINE_SEMINAR' => fake()->boolean(50),
            'LIST_OVERVIEW' => fake()->paragraph(1),
            'ONLINE_VIEW_URL' => fake()->url(),


            'PUBLICATION_START_DATE_TIME' => $publicStart =  Carbon::now(new DateTimeZone('Asia/Ho_Chi_Minh')),
            // start after 1 week
            'EVENT_STARTDATE' => $eventStart = $publicStart->copy()->addWeek(),

            // end publication at event start
            'PUBLICATION_END_DATE_TIME' => $eventStart->copy(),
            'EVENT_ENDDATE' => fake()->dateTimeBetween($eventStart, '+10 days')->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh')),
        ];
    }
}
