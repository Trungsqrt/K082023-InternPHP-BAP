<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Seminar_Image;
use Illuminate\Database\Seeder;
use Database\Seeders\IconSeeder;
use Database\Factories\IconFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // SeminarSeeder::class,
            // UserSeeder::class,
            SeminarApplicationSeeder::class,
            // SeminarIconSeeder::class,
            // SeminarImageSeeder::class,
            // SeminarDetailsSeeder::class,
            // SeminarMailInfoSeeder::class
        ]);
    }
}
