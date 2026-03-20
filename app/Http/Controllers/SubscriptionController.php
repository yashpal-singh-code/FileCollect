<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DocumentRequest;
use App\Models\DocumentUpload;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Subscription Overview
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Get subscription
        $subscription = $user->subscription('default');

        // Get payment methods
        $paymentMethods = $user->paymentMethods();

        // Get default payment method
        $defaultPaymentMethod = $user->defaultPaymentMethod();

        // Initialize variables
        $activePlan = null;
        $activeBilling = 'monthly';
        $currentPeriodEnd = null;

        // Detect active subscription plan
        if ($subscription && $subscription->valid()) {

            // Ensure subscription items loaded
            $subscription->loadMissing('items');

            // Get first subscription item
            $currentItem = $subscription->items->first();

            // Get Stripe price ID
            $currentPriceId = $currentItem?->stripe_price;

            // Detect active plan from price ID
            $activePlan = Plan::where(function ($query) use ($currentPriceId) {

                $query->where('stripe_price_monthly', $currentPriceId)
                    ->orWhere('stripe_price_yearly', $currentPriceId);
            })->first();

            // Detect billing cycle
            if ($activePlan) {

                $activeBilling = $currentPriceId === $activePlan->stripe_price_yearly
                    ? 'yearly'
                    : 'monthly';
            }

            // Get Stripe subscription details
            try {

                $stripeSub = $subscription->asStripeSubscription();

                $currentPeriodEnd = $stripeSub->current_period_end ?? null;
            } catch (\Exception $e) {

                $currentPeriodEnd = null;
            }
        }

        // Free plan fallback if no active plan
        if (!$activePlan) {

            $activePlan = Plan::where('is_free', true)->first();
        }

        // Get account owner
        $owner = $user->getAccountOwner();

        // Collect usage statistics
        $usage = [

            // Company users
            'users' => User::count(),

            // Clients
            'clients' => Client::where('owner_id', $owner->id)->count(),

            // Document requests
            'requests' => DocumentRequest::where('owner_id', $owner->id)->count(),

            // Storage used (MB)
            'storage' => round(
                DocumentUpload::where('owner_id', $owner->id)
                    ->sum('file_size') / 1024 / 1024
            ),
        ];

        // Load all available plans
        $plans = Plan::active()
            ->orderBy('monthly_price')
            ->get();

        // Mark active plan
        foreach ($plans as $plan) {

            if ($subscription && $subscription->valid()) {

                $plan->is_active = $activePlan && $activePlan->id === $plan->id;
            } else {

                $plan->is_active = $plan->isFree();
            }
        }

        // Get Stripe customer
        $customer = $user->asStripeCustomer();

        // Return billing dashboard view
        return view('subscriptions.index', [

            'user' => $user,

            'subscription' => $subscription,

            'activePlan' => $activePlan,

            'activeBilling' => $activeBilling,

            'currentPeriodEnd' => $currentPeriodEnd,

            'plans' => $plans,

            'usage' => $usage,

            'paymentMethods' => $paymentMethods,

            'paymentMethod' => $defaultPaymentMethod,

            'invoices' => $user->invoicesIncludingPending(),

            'customer' => $customer,
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Checkout (From Pricing Page)
    |--------------------------------------------------------------------------
    */
    public function checkout(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'plan'    => ['required', 'string'],
            'billing' => ['required', 'in:monthly,yearly'],
        ]);

        $plan = Plan::active()
            ->where('slug', $request->plan)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | FREE PLAN
        |--------------------------------------------------------------------------
        */
        if ($plan->isFree()) {

            DB::transaction(function () use ($user, $plan) {

                $subscription = $user->subscription('default');

                if ($subscription && !$subscription->ended()) {
                    $subscription->cancelNow();
                }

                $user->update([
                    'plan_id' => $plan->id,
                    'billing_cycle' => 'monthly',
                ]);
            });

            return redirect()->route('dashboard')
                ->with('success', 'Switched to Free plan successfully.');
        }

        $priceId = $plan->getStripePrice($request->billing);

        if (!$priceId) {
            return back()->with('error', 'Stripe price not configured.');
        }

        if ($user->hasIncompletePayment('default')) {
            return redirect()->route(
                'cashier.payment',
                $user->subscription('default')->latestPayment()->id
            );
        }

        $subscription = $user->subscription('default');

        if (!$subscription || $subscription->ended()) {

            return $user->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url'  => route('pricing'),
                    'customer_update' => ['address' => 'auto'],
                ]);
        }

        return $this->handleSwapLogic($user, $priceId);
    }

    /*
    |--------------------------------------------------------------------------
    | Swap (Dashboard)
    |--------------------------------------------------------------------------
    */
    public function swap(Request $request)
    {
        $request->validate([
            'plan'    => ['required', 'string'],
            'billing' => ['required', 'in:monthly,yearly'],
        ]);

        $user = $request->user();

        $plan = Plan::active()
            ->where('slug', $request->plan)
            ->firstOrFail();

        if ($plan->isFree()) {
            return redirect()->route('pricing')
                ->with('error', 'Use pricing page to switch to Free plan.');
        }

        $priceId = $plan->getStripePrice($request->billing);

        if (!$priceId) {
            return back()->with('error', 'Stripe price not configured.');
        }

        $subscription = $user->subscription('default');

        if (!$subscription || $subscription->ended()) {
            return $user->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url'  => route('pricing'),
                ]);
        }

        return $this->handleSwapLogic($user, $priceId);
    }

    /*
    |--------------------------------------------------------------------------
    | Shared Upgrade / Downgrade Logic (Stripe Safe)
    |--------------------------------------------------------------------------
    */
    private function handleSwapLogic($user, $priceId)
    {
        $subscription = $user->subscription('default');

        try {

            $subscription->loadMissing('items');

            $currentItem = $subscription->items->first();
            $currentPriceId = $currentItem?->stripe_price;

            if ($currentPriceId === $priceId) {
                return back()->with('info', 'You are already on this plan.');
            }

            // Get current plan from DB
            $currentPlan = Plan::where(function ($query) use ($currentPriceId) {
                $query->where('stripe_price_monthly', $currentPriceId)
                    ->orWhere('stripe_price_yearly', $currentPriceId);
            })->first();

            // Get new plan from DB
            $newPlan = Plan::where(function ($query) use ($priceId) {
                $query->where('stripe_price_monthly', $priceId)
                    ->orWhere('stripe_price_yearly', $priceId);
            })->first();

            if (!$newPlan) {
                return back()->with('error', 'Selected plan not found.');
            }

            // Determine billing cycle
            $billing = $priceId === $newPlan->stripe_price_yearly
                ? 'yearly'
                : 'monthly';

            // Compare using DB prices (NO Stripe API calls)
            $currentAmount = $currentPlan?->monthly_price ?? 0;
            $newAmount     = $newPlan->monthly_price ?? 0;

            DB::transaction(function () use ($subscription, $priceId, $user, $newPlan, $billing, $newAmount, $currentAmount) {

                if ($newAmount > $currentAmount) {

                    // Upgrade → Immediate
                    $subscription->swap($priceId);
                } else {

                    // Validate downgrade limits
                    $limitError = $this->validateDowngradeLimits($user, $newPlan);

                    if ($limitError) {
                        throw new \Exception($limitError);
                    }

                    // Downgrade without proration
                    $subscription->noProrate()->swap($priceId);
                }

                // 🔐 Sync DB plan with Stripe
                $user->update([
                    'plan_id' => $newPlan->id,
                    'billing_cycle' => $billing,
                ]);
            });

            return redirect()->route('dashboard')
                ->with('success', 'Subscription updated successfully.');
        } catch (\Throwable $e) {

            return back()->with('error', $e->getMessage() ?: 'Subscription update failed.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Subscription
    |--------------------------------------------------------------------------
    */
    public function cancel(Request $request)
    {
        $subscription = $request->user()->subscription('default');

        if ($subscription && !$subscription->ended()) {
            $subscription->cancel();
        }

        return back()->with(
            'success',
            'Subscription cancelled. Access remains until billing period ends.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Subscription
    |--------------------------------------------------------------------------
    */
    public function resume(Request $request)
    {
        $subscription = $request->user()->subscription('default');

        if ($subscription && $subscription->onGracePeriod()) {
            $subscription->resume();
        }

        return back()->with('success', 'Subscription resumed successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Stripe Billing Portal
    |--------------------------------------------------------------------------
    */
    public function portal(Request $request)
    {
        return $request->user()
            ->redirectToBillingPortal(route('dashboard'));
    }

    /*
    |--------------------------------------------------------------------------
    | Download Invoice
    |--------------------------------------------------------------------------
    */
    public function downloadInvoice(Request $request, $invoiceId)
    {
        return $request->user()->downloadInvoice($invoiceId, [
            'vendor'  => config('app.name'),
            'product' => 'Subscription Service',
        ]);
    }

    public function showInvoice(Request $request, $invoiceId)
    {
        $invoice = $request->user()->findInvoice($invoiceId);

        abort_if(!$invoice, 404);

        return view('subscriptions.invoice-view', compact('invoice'));
    }



    /*
|--------------------------------------------------------------------------
| Validate Downgrade Limits
|--------------------------------------------------------------------------
*/
    private function validateDowngradeLimits($user, Plan $newPlan)
    {
        $owner = $user->getAccountOwner();

        // 1️⃣ STORAGE CHECK
        $currentStorage = DocumentUpload::where('owner_id', $owner->id)
            ->sum('file_size');

        $newStorageLimit = $newPlan->storageBytes(); // in bytes

        if ($newStorageLimit !== null && $currentStorage > $newStorageLimit) {
            return 'Your current storage usage exceeds the new plan limit.';
        }

        // 2️⃣ COMPANY USERS CHECK
        $currentUsers = User::where(function ($q) use ($owner) {
            $q->where('id', $owner->id)
                ->orWhere('created_by', $owner->id);
        })->count();

        if (
            !$newPlan->hasUnlimited('company_users') &&
            $currentUsers > $newPlan->company_users
        ) {
            return 'You have more team members than allowed in this plan.';
        }

        // 3️⃣ CLIENT COUNT CHECK
        $currentClients = Client::where('owner_id', $owner->id)
            ->where('status', 'active')
            ->count();

        if (
            !$newPlan->hasUnlimited('clients') &&
            $currentClients > $newPlan->clients
        ) {
            return 'You have more active clients than allowed in this plan.';
        }

        // 4️⃣ MONTHLY DOCUMENT LIMIT CHECK
        $currentMonthlyDocs = DocumentRequest::where('owner_id', $owner->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        if (
            !$newPlan->hasUnlimited('document_requests') &&
            $currentMonthlyDocs > $newPlan->document_requests
        ) {
            return 'Your monthly document usage exceeds this plan limit.';
        }

        return null; // No issues
    }


    public function setDefaultPaymentMethod(Request $request)
    {
        $user = $request->user();

        $user->updateDefaultPaymentMethod($request->payment_method);

        return back()->with('success', 'Default payment method updated.');
    }

    public function removePaymentMethod(Request $request)
    {
        $user = $request->user();

        $paymentMethod = $user->findPaymentMethod($request->payment_method);

        if ($paymentMethod) {
            $paymentMethod->delete();
        }

        return back()->with('success', 'Payment method removed.');
    }
}
