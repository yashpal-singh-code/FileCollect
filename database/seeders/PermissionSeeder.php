<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [

            // Main Navigation
            'dashboard' => ['view'],

            'document_requests' => [
                'view',
                'create',
                'edit',
                'delete',
                'send'
            ],

            'clients' => [
                'view',
                'create',
                'edit',
                'delete'
            ],

            'templates' => [
                'view',
                'create',
                'edit',
                'delete'
            ],

            'teams' => [
                'view',
                'create',
                'edit',
                'delete'
            ],

            // Account Settings
            'company_settings' => [
                'view',
                'edit'
            ],

            'roles' => [
                'view',
                'create',
                'edit',
                'delete',
                'assign_permissions'
            ],

            'subscriptions' => [
                'view',
                'manage'
            ],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $module . '.' . $action,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin Role
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
        ]);

        $superAdmin->syncPermissions(Permission::all());
    }
}