<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use App\Models\Plan;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Custom Redirect Logic after Login
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', [
                'request' => $request
            ]);
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });


        // Custom Registration View with Plan Selection
        Fortify::registerView(function (Request $request) {

            $planSlug = $request->query('plan', 'free');
            $billing  = $request->query('billing', 'monthly');

            // Validate billing strictly
            if (!in_array($billing, ['monthly', 'yearly'])) {
                $billing = 'monthly';
            }

            // Fetch only ACTIVE plan
            $plan = Plan::active()
                ->where('slug', $planSlug)
                ->first();

            // If invalid plan → fallback to free
            if (!$plan) {
                $plan = Plan::active()
                    ->where('slug', 'free')
                    ->firstOrFail();

                $planSlug = $plan->slug;
                $billing  = 'monthly';
            }

            // Prevent yearly abuse if not available
            if ($billing === 'yearly' && is_null($plan->yearly_price)) {
                $billing = 'monthly';
            }

            // 🔐 Only now store validated values in session
            session([
                'selected_plan' => $planSlug,
                'selected_billing' => $billing,
            ]);

            $price = null;

            if (!$plan->isFree()) {
                $price = $billing === 'yearly'
                    ? $plan->yearly_price
                    : $plan->monthly_price;
            }

            return view('auth.register', compact(
                'plan',
                'planSlug',
                'billing',
                'price'
            ));
        });


        // Custom Redirect Logic after Registration

        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    $plan = session('selected_plan', 'free');
                    $billing = session('selected_billing', 'monthly');

                    session()->forget(['selected_plan', 'selected_billing']);

                    if ($plan !== 'free') {
                        return redirect()->route('subscriptions.checkout', [
                            'plan' => $plan,
                            'billing' => $billing,
                        ]);
                    }

                    return redirect()->route('dashboard');
                }
            };
        });



        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {

                public function toResponse($request)
                {
                    $user = $request->user();

                    if ($user->hasRole('owner') && $user->saas_owner) {
                        return redirect()->route('owner.dashboard');
                    }

                    return redirect()->route('dashboard');
                }
            };
        });


        // Fortify Actions
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
