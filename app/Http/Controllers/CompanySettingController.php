<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | Show Page
    |--------------------------------------------------------------------------
    | Modes:
    | - create (no record)
    | - view   (record exists)
    | - edit   (?edit=true)
    */
    public function show(Request $request)
    {
        $company = CompanySetting::where('owner_id', Auth::id())->first();

        if (!$company) {
            $mode = 'create';
        } elseif ($request->query('edit') === 'true') {
            $mode = 'edit';
        } else {
            $mode = 'view';
        }

        return view('company_settings.form', compact('company', 'mode'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        if (CompanySetting::where('owner_id', Auth::id())->exists()) {
            abort(403);
        }

        $validated = $this->validateData($request);

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = $request
                ->file('company_logo')
                ->store('company_logos', 's3');
        }

        $validated['owner_id'] = Auth::id();

        CompanySetting::create($validated);

        return redirect()
            ->route('company-settings.show')
            ->with('success', 'Company created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        $company = CompanySetting::where('owner_id', Auth::id())->firstOrFail();

        $validated = $this->validateData($request);

        if ($request->hasFile('company_logo')) {

            if (
                $company->company_logo &&
                Storage::disk('s3')->exists($company->company_logo)
            ) {
                Storage::disk('s3')->delete($company->company_logo);
            }

            $validated['company_logo'] = $request
                ->file('company_logo')
                ->store('company_logos', 's3');
        }

        $company->update($validated);

        return redirect()
            ->route('company-settings.show')
            ->with('success', 'Company updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
    private function validateData(Request $request)
    {
        return $request->validate([
            'company_name'   => 'required|string|max:150',
            'company_logo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'email'          => 'nullable|email|max:150',
            'phone'          => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:150',
            'address_line_2' => 'nullable|string|max:150',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'postal_code'    => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:80',
        ]);
    }
}
