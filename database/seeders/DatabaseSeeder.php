<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(PermissionsSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(ExperienceSeeder::class);
        $this->call(CertificateSeeder::class);
        $this->call(EducationSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(ContactSeeder::class);
    }
}
