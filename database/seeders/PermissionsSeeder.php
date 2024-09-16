<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Permission::create(
            [
                'code'        => 'GPRS_USR_CREATE',
                'description' => 'Allows the creation of users',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_USR_UPDATE',
                'description' => 'Allows the update of users',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_USR_GET',
                'description' => 'Allows the listing of users',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_USR_DELETE',
                'description' => 'Allows the deletion of users',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EXP_CREATE',
                'description' => 'Allows the creation of experiences',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EXP_UPDATE',
                'description' => 'Allows the update of experiences',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EXP_GET',
                'description' => 'Allows the listing of experiences',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EXP_DELETE',
                'description' => 'Allows the deletion of experiences',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_CERT_CREATE',
                'description' => 'Allows the creation of certifications',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_CERT_UPDATE',
                'description' => 'Allows the update of certifications',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_CERT_GET',
                'description' => 'Allows the listing of certifications',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_CERT_DELETE',
                'description' => 'Allows the deletion of certifications',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EDU_CREATE',
                'description' => 'Allows the creation of education histories',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EDU_UPDATE',
                'description' => 'Allows the update of education histories',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EDU_GET',
                'description' => 'Allows the listing of education histories',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_EDU_DELETE',
                'description' => 'Allows the deletion of education histories',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_SKILL_CREATE',
                'description' => 'Allows the creation of skills',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_SKILL_UPDATE',
                'description' => 'Allows the update of skills',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_SKILL_GET',
                'description' => 'Allows the listing of skills',
            ]
        );

        Permission::create(
            [
                'code'        => 'GPRS_SKILL_DELETE',
                'description' => 'Allows the deletion of skills',
            ]
        );
    }
}
