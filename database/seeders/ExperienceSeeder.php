<?php

namespace Database\Seeders;

use App\Models\Description;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Description::factory()->forExp()->count(5)->create();
    }
}
