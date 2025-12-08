<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount('permissions', 'users')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            // Group permissions by their resource (e.g., 'view courses' -> 'courses')
            $parts = explode(' ', $permission->name);
            return end($parts);
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Create slug from name
        $slug = strtolower(str_replace(' ', '-', $validated['name']));

        $role = Role::create([
            'name' => $slug,
            'guard_name' => 'web',
        ]);

        // Sync permissions
        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$validated['name']}' created successfully!");
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return end($parts);
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent editing super-admin role name
        $nameRules = 'required|string|max:255|unique:roles,name,' . $role->id;
        if ($role->name === 'super-admin') {
            $nameRules = 'nullable';
        }

        $validated = $request->validate([
            'name' => $nameRules,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Update role name (except for super-admin)
        if ($role->name !== 'super-admin' && !empty($validated['name'])) {
            $slug = strtolower(str_replace(' ', '-', $validated['name']));
            $role->update(['name' => $slug]);
        }

        // Sync permissions (super-admin always gets all permissions)
        if ($role->name === 'super-admin') {
            $role->syncPermissions(Permission::all());
        } else {
            $role->syncPermissions($validated['permissions'] ?? []);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Role updated successfully!");
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting system roles
        $protectedRoles = ['super-admin', 'admin', 'student'];

        if (in_array($role->name, $protectedRoles)) {
            return back()->with('error', "Cannot delete the '{$role->name}' role. It is a system role.");
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', "Cannot delete role '{$role->name}'. It has {$role->users()->count()} user(s) assigned to it.");
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$roleName}' deleted successfully!");
    }

    /**
     * Show role details with assigned users.
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        $users = $role->users()->paginate(15);

        return view('admin.roles.show', compact('role', 'users'));
    }
}
