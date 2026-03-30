<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Subscription;
use App\Models\Plan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;

class SubscriptionController extends Controller
{


    protected $api;

    public function __construct()
    {
        $this->middleware('permission:subscriptions.view')->only(['index']);
        $this->middleware('permission:subscriptions.manage')->only(['cancel', 'download']);


        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function processPlan()
    {
        $planSlug = session('selected_plan');
        $billing  = session('selected_billing');

        if (!$planSlug || !$billing) {
            return redirect('/')->with('error', 'Invalid plan selection');
        }

        $plan = Plan::where('slug', $planSlug)->first();

        if (!$plan) {
            return redirect('/');
        }

        // ✅ FREE PLAN
        if ($plan->is_free) {

            /** @var User $user */
            $user = Auth::user();

            $user->update([
                'plan_id' => $plan->id,
                'billing_cycle' => null, // ✅ FIXED
            ]);

            session()->forget(['selected_plan', 'selected_billing']);

            return redirect('/dashboard')->with('success', 'Free plan activated!');
        }

        return redirect()->route('billing');
    }


    public function billing()
    {
        $planSlug = session('selected_plan');
        $billing  = session('selected_billing');

        $plan = Plan::where('slug', $planSlug)->first();

        // ✅ FIX: session expired protection
        if (!$plan) {
            return redirect('/dashboard')->with('error', 'Session expired');
        }

        return view('billing.index', compact('plan', 'billing'));
    }


    /*
    |--------------------------------------------------------------------------
    | BILLING DASHBOARD
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();

        // ✅ Latest subscription
        $subscription = Subscription::where('user_id', $user->id)
            ->latest()
            ->first();

        $activePlan = null;

        // ✅ If ACTIVE → map plan from razorpay
        if ($subscription && $subscription->status === 'active') {
            $activePlan = Plan::where(function ($q) use ($subscription) {
                $q->where('razorpay_plan_monthly', $subscription->plan_id)
                    ->orWhere('razorpay_plan_yearly', $subscription->plan_id);
            })->first();
        }

        // ✅ If cancelled/completed → FORCE FREE
        if ($subscription && in_array($subscription->status, ['cancelled', 'completed'])) {
            $activePlan = Plan::where('is_free', true)->first();
        }

        // ✅ No subscription OR active fallback
        if (!$subscription || $subscription->status === 'active') {
            $activePlan = $activePlan ?: Plan::find($user->plan_id);
        }

        // ✅ billing
        $activeBilling = $subscription->type ?? $user->billing_cycle ?? 'monthly';

        // ✅ next billing only if active
        $nextBillingDate = null;

        if ($subscription && $subscription->status === 'active') {
            $nextBillingDate = $subscription->type === 'yearly'
                ? $subscription->created_at->copy()->addYear()
                : $subscription->created_at->copy()->addMonth();
        }

        $plans = Plan::active()->ordered()->get();

        $invoices = Invoice::where('user_id', $user->id)
            ->latest()
            ->take(10) // latest 10 (can change)
            ->get();

        return view('subscriptions.index', compact(
            'subscription',
            'activePlan',
            'activeBilling',
            'nextBillingDate',
            'plans',
            'invoices' // 🔥 add this
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE SUBSCRIPTION
    |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        try {

            $request->validate([
                'plan' => 'required|string',
                'billing' => 'required|in:monthly,yearly',
            ]);

            $user = Auth::user();

            // 🔥 Get latest subscription
            $existing = Subscription::where('user_id', $user->id)
                ->latest()
                ->first();

            // 🔥 If active → cancel it first (upgrade/downgrade flow)
            if ($existing && $existing->status === 'active') {

                try {
                    if ($existing->razorpay_subscription_id) {
                        $razorSub = $this->api->subscription->fetch(
                            $existing->razorpay_subscription_id
                        );

                        if ($razorSub->status !== 'completed') {
                            $razorSub->cancel();
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Auto cancel old subscription failed', [
                        'message' => $e->getMessage()
                    ]);
                }

                // update DB
                $existing->update([
                    'status' => 'cancelled',
                    'ends_at' => now()
                ]);
            }

            // 🔥 If still pending (created) → block
            if ($existing && $existing->status === 'created') {

                // 🔥 Cancel old pending subscription (failed attempt)
                try {
                    if ($existing->razorpay_subscription_id) {
                        $this->api->subscription
                            ->fetch($existing->razorpay_subscription_id)
                            ->cancel();
                    }
                } catch (\Exception $e) {
                    Log::error('Cancel failed pending subscription', [
                        'message' => $e->getMessage()
                    ]);
                }

                // ✅ VERY IMPORTANT: update DB status
                $existing->update([
                    'status' => 'failed'
                ]);
            }

            $plan = Plan::where('slug', $request->plan)->firstOrFail();

            $planId = $plan->getRazorpayPlanId($request->billing);

            if (!$planId) {
                return response()->json([
                    'error' => 'Razorpay plan not configured'
                ], 400);
            }

            $razorpaySub = $this->api->subscription->create([
                'plan_id' => $planId,
                'customer_notify' => 1,
                'total_count' => $request->billing === 'monthly' ? 12 : 1,
            ]);

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'type' => $request->billing,
                'plan_id' => $planId,
                'razorpay_subscription_id' => $razorpaySub['id'],
                'status' => 'created',
            ]);

            return response()->json([
                'id' => $razorpaySub['id']
            ]);
        } catch (\Exception $e) {

            Log::error('Subscription Create Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Subscription creation failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY PAYMENT
    |--------------------------------------------------------------------------
    */
    public function verify(Request $request)
    {
        try {

            $request->validate([
                'payment_id' => 'required',
                'subscription_id' => 'required',
                'signature' => 'required',
            ]);

            // ✅ Verify Razorpay signature
            $this->api->utility->verifyPaymentSignature([
                'razorpay_payment_id' => $request->payment_id,
                'razorpay_subscription_id' => $request->subscription_id,
                'razorpay_signature' => $request->signature
            ]);

            $sub = Subscription::where(
                'razorpay_subscription_id',
                $request->subscription_id
            )->first();

            if (!$sub) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Subscription not found'
                ], 404);
            }

            DB::transaction(function () use ($sub, $request) {

                // ✅ Activate subscription
                $sub->update([
                    'status' => 'active',
                    'razorpay_payment_id' => $request->payment_id,
                    'razorpay_signature' => $request->signature,
                ]);

                // ✅ Get plan
                $plan = Plan::where(function ($q) use ($sub) {
                    $q->where('razorpay_plan_monthly', $sub->plan_id)
                        ->orWhere('razorpay_plan_yearly', $sub->plan_id);
                })->first();

                // ✅ Update user plan
                if ($plan) {
                    $sub->user->update([
                        'plan_id' => $plan->id,
                        'billing_cycle' => $sub->type,
                    ]);
                }

                // 🔥🔥 CREATE INVOICE (MAIN ADDITION)
                if ($plan) {
                    \App\Models\Invoice::create([
                        'user_id' => $sub->user_id,
                        'plan_id' => $plan->id,
                        'invoice_number' => 'INV-' . strtoupper(\Illuminate\Support\Str::random(8)),
                        'amount' => $sub->type === 'yearly'
                            ? $plan->yearly_price
                            : $plan->monthly_price,
                        'currency' => 'INR',
                        'status' => 'paid',
                        'razorpay_payment_id' => $request->payment_id,
                        'paid_at' => now(),
                    ]);
                }

                // ✅ Clear session
                session()->forget(['selected_plan', 'selected_billing']);
            });

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {

            Log::error('Verification Failed', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function failed(Request $request)
    {
        try {

            $subId = $request->subscription_id;

            if ($subId) {
                Subscription::where('razorpay_subscription_id', $subId)
                    ->update(['status' => 'failed']);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {

            Log::error('Failed payment update error', [
                'message' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed'], 500);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | CANCEL SUBSCRIPTION
    |--------------------------------------------------------------------------
    */
    public function cancel(Request $request)
    {
        $sub = Subscription::where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$sub) {
            return back()->with('error', 'No subscription found.');
        }

        // 🔥 FREE PLAN
        $freePlan = Plan::where('is_free', true)->first();

        if (in_array($sub->status, ['cancelled', 'completed'])) {

            if ($freePlan) {
                $sub->user->update([
                    'plan_id' => $freePlan->id,
                    'billing_cycle' => null,
                ]);
            }

            return back()->with('error', 'Subscription already ended.');
        }

        if ($sub->status !== 'active') {
            return back()->with('error', 'Subscription is not active.');
        }

        try {

            $razorSub = $this->api->subscription->fetch(
                $sub->razorpay_subscription_id
            );

            if ($razorSub->status === 'completed') {

                $sub->update([
                    'status' => 'completed',
                    'ends_at' => now()
                ]);

                if ($freePlan) {
                    $sub->user->update([
                        'plan_id' => $freePlan->id,
                        'billing_cycle' => null,
                    ]);
                }

                return back()->with('error', 'Subscription already completed.');
            }

            // ✅ cancel
            $razorSub->cancel();

            $sub->update([
                'status' => 'cancelled',
                'ends_at' => now()
            ]);

            // 🔥 DOWNGRADE
            if ($freePlan) {
                $sub->user->update([
                    'plan_id' => $freePlan->id,
                    'billing_cycle' => null,
                ]);
            }

            return back()->with('success', 'Subscription cancelled.');
        } catch (\Exception $e) {

            Log::error('Cancel Failed', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', 'Cancel failed.');
        }
    }


    public function webhook(Request $request)
    {
        try {

            $payload = $request->all();
            $event = $payload['event'] ?? null;

            Log::info('Razorpay Webhook Received', ['event' => $event]);

            /*
        |--------------------------------------------------------------------------
        | ✅ PAYMENT SUCCESS
        |--------------------------------------------------------------------------
        */
            if ($event === 'invoice.paid') {

                $subscriptionId = $payload['payload']['subscription']['entity']['id'] ?? null;
                $paymentId = $payload['payload']['payment']['entity']['id'] ?? null;

                if (!$subscriptionId) {
                    Log::error('Webhook: Missing subscription ID');
                    return response()->json(['status' => 'error'], 400);
                }

                $sub = Subscription::where('razorpay_subscription_id', $subscriptionId)->first();

                if (!$sub) {
                    Log::error('Webhook: Subscription not found', ['id' => $subscriptionId]);
                    return response()->json(['status' => 'error'], 404);
                }

                // ✅ Activate
                $sub->update(['status' => 'active']);

                // ✅ Get plan (FIXED)
                $plan = Plan::where(function ($q) use ($sub) {
                    $q->where('razorpay_plan_monthly', $sub->plan_id)
                        ->orWhere('razorpay_plan_yearly', $sub->plan_id);
                })->first();

                if (!$plan) {
                    Log::error('Webhook: Plan not found', ['plan_id' => $sub->plan_id]);
                    return response()->json(['status' => 'error'], 404);
                }

                // ✅ Amount (FIXED)
                $amount = $sub->type === 'yearly'
                    ? $plan->yearly_price
                    : $plan->monthly_price;

                // ✅ Prevent duplicate invoice
                if ($paymentId && !Invoice::where('razorpay_payment_id', $paymentId)->exists()) {

                    Invoice::create([
                        'user_id' => $sub->user_id,
                        'plan_id' => $plan->id, // ✅ FIXED
                        'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                        'amount' => $amount, // ✅ FIXED
                        'currency' => 'INR',
                        'status' => 'paid',
                        'razorpay_payment_id' => $paymentId,
                        'paid_at' => now(),
                    ]);
                }
            }

            /*
        |--------------------------------------------------------------------------
        | ❌ PAYMENT FAILED
        |--------------------------------------------------------------------------
        */
            if ($event === 'invoice.payment_failed') {

                $subscriptionId = $payload['payload']['subscription']['entity']['id'] ?? null;

                $sub = Subscription::where('razorpay_subscription_id', $subscriptionId)->first();

                if ($sub) {
                    $sub->update(['status' => 'failed']);
                }
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {

            Log::error('Webhook Exception', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        $invoice = Invoice::with(['user', 'plan'])->findOrFail($id);
        $userCompany = CompanySetting::where('owner_id', $invoice->user_id)->first();
        $saas = config('company');

        // Logic: 18% GST Breakdown
        $total = (float) $invoice->amount;
        $subtotal = $total / 1.18;
        $taxAmount = $total - $subtotal;

        $data = [
            'invoice'       => $invoice,
            'saas'          => $saas,
            'userCompany'   => $userCompany,
            'subtotal'      => $subtotal,
            'cgst'          => $taxAmount / 2,
            'sgst'          => $taxAmount / 2,
            'total'         => $total,
            'amountInWords' => $this->numToWords($total) . " Rupees Only",
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data)->setPaper('a4', 'portrait');
        return $pdf->download("FileCollect_INV_{$invoice->invoice_number}.pdf");
    }

    // Pro Indian Number to Words Helper
    private function numToWords($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' => 'nineteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'forty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred
                    : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $result = implode('', array_reverse($str));
        return ucfirst($result);
    }
}
