<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $subscription = $user->subscription('default');

        // ✅ Free users allowed
        if (!$subscription) {
            return $next($request);
        }

        // ❌ Block incomplete payments
        if ($subscription->stripe_status === 'incomplete') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Please complete your payment.');
        }

        // ❌ Block past_due
        if ($subscription->stripe_status === 'past_due') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Payment required.');
        }

        // ❌ Block canceled & ended
        if ($subscription->ended()) {
            return redirect()->route('pricing')
                ->with('error', 'Subscription expired.');
        }

        // ✅ Only allow ACTIVE
        if ($subscription->stripe_status !== 'active') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Subscription not active.');
        }

        return $next($request);
    }
}