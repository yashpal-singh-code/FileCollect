<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Pricing Page
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $plans = Plan::active()
            ->ordered()
            ->get();

        return view('welcome', compact('plans'));
    }

    /*
    |--------------------------------------------------------------------------
    | Select Plan (Hardened)
    |--------------------------------------------------------------------------
    */
    public function select(Request $request)
    {
        // Strict validation (no exists rule)
        $validated = $request->validate([
            'plan' => ['required', 'string'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
        ]);

        // Fetch ONLY active plan
        $plan = Plan::active()
            ->where('slug', $validated['plan'])
            ->firstOrFail();

        $billing = $validated['billing_cycle'];

        /*
        |--------------------------------------------------------------------------
        | Prevent Yearly Billing Abuse
        |--------------------------------------------------------------------------
        */
        if ($billing === 'yearly' && is_null($plan->yearly_price)) {
            abort(400, 'Yearly billing not available for this plan.');
        }

        /*
        |--------------------------------------------------------------------------
        | FREE PLAN → Never Go To Checkout
        |--------------------------------------------------------------------------
        */
        if ($plan->isFree()) {

            if (!Auth::check()) {
                return redirect()->route('register', [
                    'plan' => $plan->slug,
                ]);
            }

            // Logged in user selecting free plan
            // You should activate free subscription internally (not Stripe)
            return redirect()->route('dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | PAID PLAN SECURITY CHECK
        |--------------------------------------------------------------------------
        */
        // Ensure Stripe price exists before redirecting
        $plan->getStripePrice($billing);
        // (this will abort if invalid or not configured)

        /*
        |--------------------------------------------------------------------------
        | NOT LOGGED IN → REGISTER FLOW
        |--------------------------------------------------------------------------
        */
        if (!Auth::check()) {
            return redirect()->route('register', [
                'plan' => $plan->slug,
                'billing' => $billing,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | LOGGED IN → CHECKOUT
        |--------------------------------------------------------------------------
        */
        return redirect()->route('subscriptions.checkout', [
            'plan' => $plan->slug,
            'billing' => $billing,
        ]);
    }
}
