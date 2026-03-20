<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Role Permission Management
    |--------------------------------------------------------------------------
    */
    public function manage(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        $selectedRole = null;

        if ($request->filled('role_id')) {
            $selectedRole = Role::with('permissions')
                ->find($request->role_id);
        }

        return view('roles.manage', compact(
            'roles',
            'permissions',
            'selectedRole'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Role Permissions
    |--------------------------------------------------------------------------
    */
    public function updatePermissions(Request $request)
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $role = Role::findOrFail($validated['role_id']);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()
            ->route('roles.manage', ['role_id' => $role->id])
            ->with('success', 'Permissions updated successfully.');
    }
}