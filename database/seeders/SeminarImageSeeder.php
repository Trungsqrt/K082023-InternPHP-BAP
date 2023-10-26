<?php

namespace Database\Seeders;

use App\Models\Seminar_Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seminar_Image::factory(10)->create();
    }
}
