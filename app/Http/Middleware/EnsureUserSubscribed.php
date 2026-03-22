<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Subscription;

class EnsureUserSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $owner = $user->getAccountOwner();

        // ✅ Check active subscription
        $subscription = Subscription::where('user_id', $owner->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        // ✅ If NO subscription → allow FREE plan
        if (!$subscription) {
            return $next($request);
        }

        // ❌ Expired
        if ($subscription->ends_at && now()->greaterThan($subscription->ends_at)) {
            return redirect()->route('pricing')
                ->with('error', 'Your subscription has expired.');
        }

        // ❌ Cancelled
        if ($subscription->status === 'cancelled') {
            return redirect()->route('pricing')
                ->with('error', 'Subscription cancelled.');
        }

        // ❌ Not active
        if ($subscription->status !== 'active') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Subscription not active.');
        }

        return $next($request);
    }
}
