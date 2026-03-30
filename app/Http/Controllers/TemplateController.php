<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:templates.view')->only(['index']);
        $this->middleware('permission:templates.create')->only(['create', 'store', 'duplicate']);
        $this->middleware('permission:templates.edit')->only(['edit', 'update']);
        $this->middleware('permission:templates.delete')->only(['destroy']);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function currentUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    protected function owner(): User
    {
        return $this->currentUser()->getAccountOwner();
    }

    protected function ownerId(): int
    {
        return $this->owner()->id;
    }

    protected function templateCount(): int
    {
        return Template::where('created_by', $this->ownerId())->count();
    }

    protected function templateLimit(): ?int
    {
        return $this->owner()->currentPlan()?->template_limit;
    }

    protected function limitReached(): bool
    {
        $limit = $this->templateLimit();

        if (is_null($limit)) {
            return false;
        }

        return $this->templateCount() >= $limit;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // Get account owner
        $owner = $this->owner();

        // Base query for owner's templates
        $query = Template::query()
            ->where('created_by', $owner->id)
            ->withCount('documentRequests');

        // Search templates by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->status === 'active') {
            $query->where('is_active', true);
        }

        if ($request->status === 'archived') {
            $query->where('is_active', false);
        }

        // Paginate results
        $templates = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Total templates count
        $totalTemplates = Template::where('created_by', $owner->id)->count();

        // Active templates count
        $activeTemplates = Template::where('created_by', $owner->id)
            ->where('is_active', true)
            ->count();

        // Plan limit
        $templateLimit = $this->templateLimit();

        // Used templates
        $usedTemplates = $totalTemplates;

        // Remaining templates allowed
        $remainingTemplates = is_null($templateLimit)
            ? null
            : max(0, $templateLimit - $usedTemplates);

        // Check if limit reached
        $limitReached = $this->limitReached();

        // Calculate usage percentage
        $usagePercent = !is_null($templateLimit) && $templateLimit > 0
            ? min(100, round(($usedTemplates / $templateLimit) * 100))
            : 0;

        // Return view
        return view('templates.index', compact(
            'templates',
            'totalTemplates',
            'activeTemplates',
            'templateLimit',
            'usedTemplates',
            'remainingTemplates',
            'limitReached',
            'usagePercent'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        if ($this->limitReached()) {
            return redirect()
                ->route('templates.index')
                ->withErrors('You have reached your template limit.');
        }

        return view('templates.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        // Plan limit check
        if ($this->limitReached()) {
            return redirect()
                ->route('templates.index')
                ->withErrors('Template limit reached.');
        }

        $owner = $this->owner();

        // Normalize before validation
        $request->merge([
            'is_active' => $request->boolean('is_active'),
        ]);

        // Validation
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('templates')
                    ->where(
                        fn($q) =>
                        $q->where('owner_id', $owner->id)
                    ),
            ],

            'description' => ['nullable', 'string'],

            'category' => ['nullable', 'string', 'max:100'],

            // JSON fields
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.label' => ['required', 'string', 'max:150'],
            'fields.*.type' => [
                'required',
                'string',
                'in:text,textarea,file,date,number,email,phone,select,checkbox',
            ],
            'fields.*.required' => ['nullable', 'boolean'],
            'fields.*.note' => ['nullable', 'string', 'max:500'],

            'default_due_days' => ['nullable', 'integer', 'min:1', 'max:365'],

            'is_active' => ['nullable', 'boolean'],
        ]);

        // Normalize fields after validation
        $validated['fields'] = collect($validated['fields'] ?? [])
            ->filter(fn($field) => !empty($field['label']))
            ->map(function ($field) {
                return [
                    'label' => $field['label'],
                    'type' => $field['type'] ?? 'file',
                    'required' => isset($field['required']) &&
                        ($field['required'] == 1 || $field['required'] === true),
                    'note' => trim($field['note'] ?? ''),
                ];
            })
            ->values()
            ->toArray();

        // System fields
        $validated['owner_id']   = $owner->id;
        $validated['created_by'] = $owner->id;
        $validated['is_locked']  = false;
        $validated['version']    = 1;
        $validated['usage_count'] = 0;

        // Create template
        Template::create($validated);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Template created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Request $request, Template $template)
    {
        $this->authorizeAccess($template);

        $inUse = DocumentRequest::where('template_uuid', $template->uuid)->exists();

        return view('templates.edit', [
            'template' => $template,
            'inUse'    => $inUse,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Template $template)
    {
        // Get account owner
        $owner = $this->owner();

        // Normalize boolean input
        $request->merge([
            'is_active' => $request->boolean('is_active'),
        ]);

        // Validate request
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('templates')
                    ->where(fn($q) => $q->where('created_by', $owner->id))
                    ->ignore($template->id),
            ],

            'description' => ['nullable', 'string'],

            'category' => ['nullable', 'string', 'max:100'],

            // Template fields
            'fields' => ['required', 'array', 'min:1'],

            'fields.*.label' => ['required', 'string', 'max:150'],

            'fields.*.type' => [
                'required',
                'string',
                'in:text,textarea,file,date,number,email,phone,select,checkbox',
            ],

            'fields.*.required' => ['nullable', 'boolean'],

            'fields.*.note' => ['nullable', 'string', 'max:500'],

            'default_due_days' => ['nullable', 'integer', 'min:1', 'max:365'],

            'is_active' => ['nullable', 'boolean'],
        ]);

        // Normalize fields
        $validated['fields'] = collect($validated['fields'] ?? [])
            ->filter(fn($field) => !empty($field['label']))
            ->map(function ($field) {

                return [
                    'label' => trim($field['label']),
                    'type' => $field['type'] ?? 'file',
                    'required' => isset($field['required']) &&
                        ($field['required'] == 1 || $field['required'] === true),
                    'note' => trim($field['note'] ?? ''),
                ];
            })
            ->values()
            ->toArray();

        // Optional: increment version when updating
        $validated['version'] = ($template->version ?? 1) + 1;

        // Update template
        $template->update($validated);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Template $template)
    {
        $this->authorizeAccess($template);

        $inUse = DocumentRequest::where('template_uuid', $template->uuid)->exists();

        if ($inUse) {
            return back()->with(
                'error',
                'This template is currently used in document requests and cannot be deleted.'
            );
        }

        $template->delete();

        return back()->with('success', 'Template deleted successfully.');
    }


    /*
|--------------------------------------------------------------------------
| DUPLICATE
|--------------------------------------------------------------------------
*/

    public function duplicate(Request $request, Template $template)
    {
        $this->authorizeAccess($template);

        if ($this->limitReached()) {
            return redirect()
                ->route('templates.index')
                ->withErrors('Template limit reached.');
        }

        $newTemplate = $this->duplicateTemplate($template);

        return redirect()
            ->route('templates.edit', $newTemplate)
            ->with('success', 'Template duplicated successfully.');
    }

    /*
|--------------------------------------------------------------------------
| INTERNAL DUPLICATION LOGIC
|--------------------------------------------------------------------------
*/

    protected function duplicateTemplate(Template $template): Template
    {
        $owner = $this->owner();

        $newTemplate = $template->replicate();

        $newTemplate->uuid           = Str::uuid(); // NEW UUID
        $newTemplate->owner_id       = $owner->id;
        $newTemplate->created_by     = $owner->id;
        $newTemplate->name           = $template->name . ' (Copy)';
        $newTemplate->version        = 1;
        $newTemplate->usage_count    = 0;
        $newTemplate->is_locked      = false;
        $newTemplate->is_active      = true;
        $newTemplate->last_used_at   = null;
        $newTemplate->deleted_at     = null;

        $newTemplate->save();

        return $newTemplate;
    }

    /*
    |--------------------------------------------------------------------------
    | SECURITY
    |--------------------------------------------------------------------------
    */

    protected function authorizeAccess(Template $template): void
    {
        if ($template->created_by !== $this->ownerId()) {
            abort(403, 'Unauthorized access.');
        }
    }
}
