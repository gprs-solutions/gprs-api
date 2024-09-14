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
    }
}
