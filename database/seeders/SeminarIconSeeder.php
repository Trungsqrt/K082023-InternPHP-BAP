<?php

namespace Database\Seeders;

use App\Models\Seminar_Icon;
use Illuminate\Database\Seeder;

class SeminarIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seminar_Icon::factory(10)->create();
    }
}
