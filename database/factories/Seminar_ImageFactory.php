<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seminar_Image>
 */
class Seminar_ImageFactory extends Factory
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
            'IMAGE_CATEGORY' => fake()->randomElement([1, 2, 3, 4, 5, 6]),
            'DISPLAY_ORDER' => fake()->randomElement([1, 2, 3, 4]),
            'FILE_NAME' =>  fake()->word(),
            'FILE_PATH' => fake()->filePath()
        ];
    }
}
