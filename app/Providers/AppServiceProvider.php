<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentRequest;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Portal Access Rate Limiting
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('portal-access', function (Request $request) {
            return Limit::perMinute(10)->by(
                $request->ip() . '|' . $request->route('token')
            );
        });

        RateLimiter::for('portal-uploads', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->ip() . '|' . $request->route('token')
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Client Portal View Composer
        |--------------------------------------------------------------------------
        */

        View::composer(['client_portal.*', 'layouts.client'], function ($view) {

            if (!Auth::guard('client')->check()) {
                return;
            }

            $pendingRequestsCount = DocumentRequest::where(
                'client_id',
                Auth::guard('client')->id()
            )
                ->whereIn('status', ['sent', 'viewed'])
                ->count();

            $view->with('pendingRequestsCount', $pendingRequestsCount);
        });
    }
}
