<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seminar_Icon>
 */
class Seminar_IconFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $seminar_id = DB::table('TT_SEMINAR')->pluck('SEMINAR_ID');
        $icon_id = DB::table('TM_ICON')->pluck('ICON_ID');

        return [
            'SEMINAR_ID' => $seminar_id->random(),
            'ICON_ID' => $icon_id->random(),
        ];
    }
}
