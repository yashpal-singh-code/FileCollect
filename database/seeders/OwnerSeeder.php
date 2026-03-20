<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure owner role exists
        $role = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web'
        ]);

        // Create or update SaaS Owner
        $user = User::updateOrCreate(
            ['email' => 'owner@filecollect.com'],
            [
                'uuid' => Str::uuid(),

                'saas_owner' => true, // IMPORTANT

                'first_name' => 'Platform',
                'last_name' => 'Owner',

                'password' => Hash::make('111'),

                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Assign role only if not already assigned
        if (!$user->hasRole('owner')) {
            $user->assignRole($role);
        }
    }
}
