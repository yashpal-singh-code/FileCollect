<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        // Only allow viewing role permissions
        $this->middleware('permission:roles.view')->only(['manage']);
    }

    /*
    |--------------------------------------------------------------------------
    | Show Role Permission (READ-ONLY)
    |--------------------------------------------------------------------------
    */
    public function manage(Request $request)
    {
        // Hide sensitive roles (optional but recommended)
        $roles = Role::whereNotIn('name', ['owner', 'super_admin'])->get();

        // Get all permissions
        $permissions = Permission::all();

        $selectedRole = null;

        // If role selected → load its permissions
        if ($request->filled('role_id')) {
            $selectedRole = Role::with('permissions')
                ->whereNotIn('name', ['owner', 'super_admin']) // extra safety
                ->find($request->role_id);
        }

        return view('roles.manage', compact(
            'roles',
            'permissions',
            'selectedRole'
        ));
    }
}
