<?php

namespace Database\Factories;

use App\Models\Seminar_Details;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seminar_Details>
 */
class Seminar_DetailsFactory extends Factory
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
            'DISPLAY_ORDER' => fake()->randomElement([1, 2, 3, 4]),
            'HEADLINE' => fake()->word(),
            'CONTENTS' => fake()->paragraph(3)
        ];
    }
}
