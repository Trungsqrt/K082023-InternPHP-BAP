<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seminar_Mail_Info>
 */
class Seminar_Mail_InfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seminar_id = DB::table('TT_SEMINAR')->pluck('SEMINAR_ID');

        return [
            'SEMINAR_ID' => $seminar_id->random(),
            'MAIL_CATEGORY' => fake()->randomElement([1, 2, 3]),
            'OPTIONAL_MESSAGE_HALL' => fake()->paragraph(3),
            'OPTIONAL_MESSAGE_ONLINE' => fake()->paragraph(3),
        ];
    }
}
