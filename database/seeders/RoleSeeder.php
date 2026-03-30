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

        $roles = [
            'owner',        // SaaS owner (you)
            'super_admin',  // Tenant owner
            'admin',
            'manager',
            'editor',
            'viewer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }
    }
}
