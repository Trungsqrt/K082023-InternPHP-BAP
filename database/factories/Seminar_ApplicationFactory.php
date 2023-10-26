<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seminar_Application>
 */
class Seminar_ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seminar_id = DB::table('TT_SEMINAR')->pluck('SEMINAR_ID');
        $member_id = DB::table('TT_MEMBER')->pluck('MEMBER_ID');
        return [
            'SEMINAR_ID' => fake()->randomElement($seminar_id),
            'MEMBER_ID' => fake()->randomElement($member_id),
            'SEMINAR_APPLICATION_CATEGORY' => fake()->randomElement([1, 2]),
            'QUESTIONNAIRE_ANSWER_ID' => fake()->numberBetween(1, 1000),
        ];
    }
}
