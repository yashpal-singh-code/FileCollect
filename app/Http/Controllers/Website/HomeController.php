<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

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


    public function contact()
    {
        return view('website.contact');
    }


    public function submit(Request $request)
    {
        // 🔐 Rate Limit (anti-spam)
        $key = 'contact-form:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => 'Too many requests. Please try again later.'
            ]);
        }

        RateLimiter::hit($key, 60); // 5 requests per minute

        // ✅ Validation
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        // 🛡️ Honeypot (hidden field protection)
        if ($request->filled('website')) {
            return back(); // bot detected
        }

        // ✅ Save in DB (with extra tracking)
        Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ Send Email
        try {
            Mail::raw(
                "New Contact Message\n\nName: {$validated['name']}\nEmail: {$validated['email']}\nSubject: {$validated['subject']}\nMessage: {$validated['message']}",
                function ($mail) {
                    $mail->to('support@filecollect.in')
                        ->subject('📩 New Contact Message');
                }
            );
        } catch (\Exception $e) {
            // optional: log error
            Log::error('Mail failed: ' . $e->getMessage());
        }

        // ✅ Success
        return back()->with('success', '✅ Message sent successfully!');
    }
}
