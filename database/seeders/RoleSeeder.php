<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // SaaS Platform Owner
        Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web'
        ]);

        // Tenant Owner
        Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);

        // Normal User
        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web'
        ]);
    }
}
