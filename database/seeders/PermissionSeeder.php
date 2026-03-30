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

        /*
        |--------------------------------------------------------------------------
        | Modules & Actions
        |--------------------------------------------------------------------------
        */

        $modules = [

            'dashboard' => ['view'],

            'document_requests' => [
                'view',
                'create',
                'edit',
                'delete',
                'send',
                'link',
                'download',
                'download_all'
            ],

            'clients' => ['view', 'create', 'edit', 'delete'],

            'templates' => ['view', 'create', 'edit', 'delete'],

            'teams' => ['view', 'create', 'edit', 'delete'],

            'subscriptions' => ['view', 'manage'],

            'company_settings' => ['view', 'edit'],

            'roles' => [
                'view',
                'create',
                'edit',
                'delete',
                'assign_permissions'
            ],

            'support' => ['view', 'create'],

        ];

        /*
        |--------------------------------------------------------------------------
        | Create Permissions
        |--------------------------------------------------------------------------
        */

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $module . '.' . $action,
                    'guard_name' => 'web',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $owner      = Role::firstOrCreate(['name' => 'owner']);
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $manager    = Role::firstOrCreate(['name' => 'manager']);
        $editor     = Role::firstOrCreate(['name' => 'editor']);
        $viewer     = Role::firstOrCreate(['name' => 'viewer']);

        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */

        // OWNER → Full system control
        $owner->syncPermissions(Permission::all());

        // SUPER ADMIN → Full tenant control
        $superAdmin->syncPermissions(Permission::all());

        // ADMIN → Almost everything except role control
        $admin->syncPermissions([
            'dashboard.view',

            'document_requests.view',
            'document_requests.create',
            'document_requests.edit',
            'document_requests.delete',
            'document_requests.send',
            'document_requests.link',
            'document_requests.download',
            'document_requests.download_all',

            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',

            'templates.view',
            'templates.create',
            'templates.edit',
            'templates.delete',

            'teams.view',
            'teams.create',
            'teams.edit',
            'teams.delete',

            'subscriptions.view',
            'subscriptions.manage',

            'company_settings.view',
            'company_settings.edit',

            'support.view',
            'support.create',

        ]);

        // MANAGER → Limited control
        $manager->syncPermissions([
            'dashboard.view',

            'document_requests.view',
            'document_requests.create',
            'document_requests.edit',

            'clients.view',
            'clients.create',
            'clients.edit',

            'templates.view',
            'templates.create',
            'templates.edit',

            'teams.view',

            'support.view',
        ]);

        // EDITOR → Content only
        $editor->syncPermissions([
            'dashboard.view',

            'document_requests.view',
            'document_requests.create',
            'document_requests.edit',

            'templates.view',
            'templates.create',
            'templates.edit',
        ]);

        // VIEWER → Read only
        $viewer->syncPermissions([
            'dashboard.view',

            'document_requests.view',
            'clients.view',
            'templates.view',
            'teams.view',
        ]);
    }
}
