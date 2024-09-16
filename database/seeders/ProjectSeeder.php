<?php

namespace Database\Seeders;

use App\Models\Description;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Description::factory()->forProj()->create();
    }
}
