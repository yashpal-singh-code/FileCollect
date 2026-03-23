<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX (List + Search + Filter)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();

        if (!$authUser) {
            abort(403);
        }

        $owner = $authUser->getAccountOwner();

        $usersQuery = User::where(function ($query) use ($owner) {
            $query->where('id', $owner->id)
                ->orWhere('created_by', $owner->id);
        })
            ->with('roles');

        if ($request->filled('search')) {
            $usersQuery->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $usersQuery->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->status !== null && $request->status !== '') {
            $usersQuery->where('is_active', $request->status);
        }

        $users = $usersQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::whereNotIn('name', ['super_admin'])->pluck('name');

        $planLimit = $owner->currentPlan()?->company_users;

        $usedUsers = $owner->getUsageCount('company_users');

        $remainingUsers = is_null($planLimit)
            ? null
            : max($planLimit - $usedUsers, 0);

        $limitReached = !is_null($planLimit)
            && $usedUsers >= $planLimit;

        $usagePercent = !is_null($planLimit) && $planLimit > 0
            ? min(100, round(($usedUsers / $planLimit) * 100))
            : 0;

        return view('users.index', compact(
            'users',
            'roles',
            'planLimit',
            'usedUsers',
            'remainingUsers',
            'limitReached',
            'usagePercent'
        ));
    }
    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        $this->authorizeOwnerAccess($user, $owner);

        return view('users.show', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        $this->enforcePlanLimit($owner);

        $roles = Role::whereNotIn('name', ['super_admin'])->get();

        return view('users.create', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        // 🔐 HARD PLAN CHECK (real security)
        if (!$owner->canUse('company_users')) {
            abort(403, 'User limit reached for your plan.');
        }
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            'role'       => 'required|string|exists:roles,name',
            'phone'      => 'nullable|string|max:20',
            'job_title'  => 'nullable|string|max:255',
        ]);

        // 🔒 Prevent super_admin assignment
        if (
            !Role::where('name', $request->role)
                ->whereNotIn('name', ['super_admin'])
                ->exists()
        ) {
            abort(403, 'Invalid role assignment.');
        }

        $user = User::create([
            'uuid'       => Str::uuid(),
            'created_by' => $owner->id,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => strtolower($request->email),
            'phone'      => $request->phone,
            'job_title'  => $request->job_title,
            'password'   => Hash::make($request->password),
            'is_owner'   => false,
            'is_active'  => true,
            'terms_accepted_at' => now(),
        ]);

        $user->assignRole($request->role);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        $this->authorizeOwnerAccess($user, $owner);

        $roles = Role::whereNotIn('name', ['super_admin', 'owner'])->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        $this->authorizeOwnerAccess($user, $owner);

        // ✅ Allow owner self-update only
        if ($user->is_owner && $authUser->id !== $user->id) {
            return back()->with('error', 'You cannot modify another owner.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|string|exists:roles,name',
            'password'   => 'nullable|min:6|confirmed',
            'phone'      => 'nullable|string|max:20',
            'job_title'  => 'nullable|string|max:255',
            'is_active'  => 'nullable|boolean',
        ]);

        // Prevent assigning super_admin
        if (!Role::where('name', $request->role)
            ->whereNotIn('name', ['super_admin'])
            ->exists()) {
            abort(403);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => strtolower($request->email),
            'phone'      => $request->phone,
            'job_title'  => $request->job_title,
            'is_active'  => $request->boolean('is_active'),
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // ✅ Owner role protection
        if ($user->is_owner) {
            $user->syncRoles(['owner']);
        } else {
            $user->syncRoles([$request->role]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/
    public function destroy(User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        // 🔐 Tenant protection
        $this->authorizeOwnerAccess($user, $owner);

        // 🔒 Block owner account
        if ($user->is_owner) {
            abort(403, 'Owner account cannot be deleted.');
        }

        // 🔒 Block super admin
        if ($user->hasRole('super_admin')) {
            abort(403, 'Super admin cannot be deleted.');
        }

        // 🔒 Block self deletion
        if ($user->id === $authUser->id) {
            abort(403, 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }



    /*
|--------------------------------------------------------------------------
| BULK DELETE
|--------------------------------------------------------------------------
*/
    public function bulkDelete(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        $ids = $request->input('selected_users', []);

        if (!empty($ids)) {

            User::whereIn('id', $ids)
                ->where('created_by', $owner->id) // tenant protection
                ->where('is_owner', false) // block owner
                ->where('id', '!=', $authUser->id) // block self
                ->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'super_admin');
                }) // block super admin
                ->delete();
        }

        return back()->with('success', 'Selected users deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | PLAN LIMIT ENFORCEMENT
    |--------------------------------------------------------------------------
    */
    private function enforcePlanLimit(User $owner): void
    {
        if (!$owner->canUse('company_users')) {
            abort(403, 'User limit reached for your plan.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT SECURITY
    |--------------------------------------------------------------------------
    */
    private function authorizeOwnerAccess(User $user, User $owner): void
    {
        if ($user->id !== $owner->id && $user->created_by !== $owner->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
