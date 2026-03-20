<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Str;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'max:25'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'terms'    => ['accepted'],
        ])->validate();

        $cleanPhone = preg_replace('/[^0-9+]/', '', $input['phone']);

        $freePlan = Plan::active()
            ->where('slug', 'free')
            ->firstOrFail();

        $user = User::create([
            'uuid' => Str::uuid(),
            'first_name' => trim($input['first_name']),
            'last_name'  => trim($input['last_name']),
            'phone' => $cleanPhone,
            'email' => strtolower($input['email']),
            'password' => Hash::make($input['password']),
            'plan_id' => $freePlan->id,
            'billing_cycle' => 'monthly',
            'is_owner' => true,
            'is_active' => true,
            'terms_accepted_at' => now(),
        ]);

        $user->assignRole('super_admin');

        return $user;
    }
}
