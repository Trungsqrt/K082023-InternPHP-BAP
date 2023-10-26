<?php

namespace Database\Seeders;

use App\Models\Seminar_Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seminar_Application::factory(100)->create();
    }
}
