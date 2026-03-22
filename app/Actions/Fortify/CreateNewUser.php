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

        /*
    |--------------------------------------------------------------------------
    | GET PLAN FROM SESSION (IMPORTANT)
    |--------------------------------------------------------------------------
    */
        $selectedPlanSlug = session('selected_plan');
        $selectedBilling  = session('selected_billing') ?? 'monthly';

        if ($selectedPlanSlug) {
            $plan = Plan::active()
                ->where('slug', $selectedPlanSlug)
                ->first();
        }

        /*
    |--------------------------------------------------------------------------
    | FALLBACK TO FREE PLAN
    |--------------------------------------------------------------------------
    */
        if (!isset($plan) || !$plan) {
            $plan = Plan::active()
                ->where('slug', 'free')
                ->firstOrFail();

            $selectedBilling = 'monthly';
        }

        /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */
        $user = User::create([
            'uuid' => Str::uuid(),
            'first_name' => trim($input['first_name']),
            'last_name'  => trim($input['last_name']),
            'phone' => $cleanPhone,
            'email' => strtolower($input['email']),
            'password' => Hash::make($input['password']),
            'plan_id' => $plan->id,
            'billing_cycle' => $selectedBilling,
            'is_owner' => true,
            'is_active' => true,
            'terms_accepted_at' => now(),
        ]);

        $user->assignRole('super_admin');

        return $user;
    }
}
