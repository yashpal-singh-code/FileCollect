<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OwnerPlanController extends Controller
{

    public function index()
    {
        $plans = Plan::orderBy('sort_order')->paginate(15);

        return view('owner.plans.index', compact('plans'));
    }


    public function create()
    {
        return view('owner.plans.create');
    }


    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|max:150',

            'monthly_price' => 'nullable|numeric',
            'yearly_price' => 'nullable|numeric',

            'currency' => 'nullable|string|max:10',

            'sort_order' => 'nullable|integer',

            'company_users' => 'nullable|integer',
            'clients' => 'nullable|integer',
            'document_requests' => 'nullable|integer',
            'template_limit' => 'nullable|integer',
            'request_templates' => 'nullable|integer',

            'storage_mb' => 'required|integer',
            'file_size_limit_mb' => 'required|integer',

            'whatsapp_limit' => 'nullable|integer',

            'usage_reset_type' => 'nullable|string',

            'stripe_product_id' => 'nullable|string',
            'stripe_price_monthly' => 'nullable|string',
            'stripe_price_yearly' => 'nullable|string',

            'allowed_mime_types' => 'nullable|array',
        ]);

        $data['slug'] = Str::slug($request->name);

        // plan state
        $data['is_free'] = $request->boolean('is_free');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_active'] = $request->boolean('is_active');

        // upload options
        $data['allow_zip'] = $request->boolean('allow_zip');
        $data['allow_video'] = $request->boolean('allow_video');
        $data['allow_multiple_uploads'] = $request->boolean('allow_multiple_uploads');

        // features
        $data['client_portal'] = $request->boolean('client_portal');
        $data['otp_login'] = $request->boolean('otp_login');
        $data['approve_workflow'] = $request->boolean('approve_workflow');
        $data['reupload_history'] = $request->boolean('reupload_history');
        $data['download_zip'] = $request->boolean('download_zip');
        $data['expiry_tracking'] = $request->boolean('expiry_tracking');
        $data['renewal_reminder'] = $request->boolean('renewal_reminder');
        $data['scheduled_reminder'] = $request->boolean('scheduled_reminder');
        $data['escalation_reminder'] = $request->boolean('escalation_reminder');
        $data['export_excel'] = $request->boolean('export_excel');
        $data['export_pdf'] = $request->boolean('export_pdf');
        $data['branding'] = $request->boolean('branding');
        $data['white_label'] = $request->boolean('white_label');
        $data['priority_support'] = $request->boolean('priority_support');

        Plan::create($data);

        return redirect()
            ->route('owner.plans.index')
            ->with('success', 'Plan created successfully');
    }


    public function edit(string $id)
    {
        $plan = Plan::findOrFail($id);

        return view('owner.plans.edit', compact('plan'));
    }


    public function update(Request $request, string $id)
    {

        $plan = Plan::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|max:150',

            'monthly_price' => 'nullable|numeric',
            'yearly_price' => 'nullable|numeric',

            'currency' => 'nullable|string|max:10',

            'sort_order' => 'nullable|integer',

            'company_users' => 'nullable|integer',
            'clients' => 'nullable|integer',
            'document_requests' => 'nullable|integer',
            'template_limit' => 'nullable|integer',
            'request_templates' => 'nullable|integer',

            'storage_mb' => 'required|integer',
            'file_size_limit_mb' => 'required|integer',

            'whatsapp_limit' => 'nullable|integer',

            'usage_reset_type' => 'nullable|string',

            'stripe_product_id' => 'nullable|string',
            'stripe_price_monthly' => 'nullable|string',
            'stripe_price_yearly' => 'nullable|string',

            'allowed_mime_types' => 'nullable|array',
        ]);

        $data['slug'] = Str::slug($request->name);

        // plan state
        $data['is_free'] = $request->boolean('is_free');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_active'] = $request->boolean('is_active');

        // upload options
        $data['allow_zip'] = $request->boolean('allow_zip');
        $data['allow_video'] = $request->boolean('allow_video');
        $data['allow_multiple_uploads'] = $request->boolean('allow_multiple_uploads');

        // features
        $data['client_portal'] = $request->boolean('client_portal');
        $data['otp_login'] = $request->boolean('otp_login');
        $data['approve_workflow'] = $request->boolean('approve_workflow');
        $data['reupload_history'] = $request->boolean('reupload_history');
        $data['download_zip'] = $request->boolean('download_zip');
        $data['expiry_tracking'] = $request->boolean('expiry_tracking');
        $data['renewal_reminder'] = $request->boolean('renewal_reminder');
        $data['scheduled_reminder'] = $request->boolean('scheduled_reminder');
        $data['escalation_reminder'] = $request->boolean('escalation_reminder');
        $data['export_excel'] = $request->boolean('export_excel');
        $data['export_pdf'] = $request->boolean('export_pdf');
        $data['branding'] = $request->boolean('branding');
        $data['white_label'] = $request->boolean('white_label');
        $data['priority_support'] = $request->boolean('priority_support');

        $plan->update($data);

        return redirect()
            ->route('owner.plans.index')
            ->with('success', 'Plan updated');
    }


    public function destroy(string $id)
    {

        $plan = Plan::findOrFail($id);

        $plan->delete();

        return redirect()
            ->route('owner.plans.index')
            ->with('success', 'Plan deleted');
    }
}
