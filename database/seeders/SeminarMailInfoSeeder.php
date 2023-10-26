<?php

namespace Database\Seeders;

use App\Models\Seminar_Mail_Info;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarMailInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seminar_Mail_Info::factory(10)->create();
    }
}
