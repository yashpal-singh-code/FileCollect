<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // If user itself inactive
        if (!$user->is_active) {

            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is disabled.']);
        }

        // If tenant owner inactive
        if ($user->created_by) {

            $owner = $user->owner;

            if ($owner && !$owner->is_active) {

                Auth::logout();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your company account has been disabled.']);
            }
        }

        return $next($request);
    }
}
