<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->superUser();
    }

    /**
     * Inserts the super user role in the DB.
     *
     * @return void
     */
    private function superUser()
    {
        $role = Role::create(
            [
                'code'        => 'GPRS_SUPER_USR',
                'description' => 'System super user - has the maximum permissions of all users.',
            ]
        );

        // SUPER USER - Has all system permissions.
        $allPermissions = Permission::all();
        $role->permissions()->sync($allPermissions->pluck('id')->toArray());
    }
}
