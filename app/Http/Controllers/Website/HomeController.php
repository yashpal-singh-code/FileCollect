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
    | Select Plan (UPDATED FLOW 🚀)
    |--------------------------------------------------------------------------
    */
    public function select(Request $request)
    {
        $validated = $request->validate([
            'plan' => ['required', 'string'],
            'billing' => ['required', 'in:monthly,yearly'],
        ]);

        // ✅ Get plan
        $plan = Plan::active()
            ->where('slug', $validated['plan'])
            ->firstOrFail();

        $billing = $validated['billing'];

        /*
        |--------------------------------------------------------------------------
        | Validate Yearly Support
        |--------------------------------------------------------------------------
        */
        if ($billing === 'yearly' && is_null($plan->yearly_price)) {
            abort(400, 'Yearly billing not available.');
        }

        /*
        |--------------------------------------------------------------------------
        | Store in Session (CRITICAL)
        |--------------------------------------------------------------------------
        */
        session([
            'selected_plan' => $plan->slug,
            'selected_billing' => $billing,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOT LOGGED IN → REGISTER (FIXED)
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
        | LOGGED IN → PROCESS PLAN
        |--------------------------------------------------------------------------
        */
        return redirect()->route('process.plan');
    }
}
