<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-list')->only(['index', 'show']);
        $this->middleware('permission:role-create')->only(['create', 'store']);
        $this->middleware('permission:role-edit')->only(['edit', 'update']);
        $this->middleware('permission:role-delete')->only(['destroy']);
    }

    public function index(): View
    {
        $roles = Role::withCount('users')->orderByDesc('id')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permission = Permission::orderBy('name')->get();

        return view('roles.create', compact('permission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permission' => ['required', 'array', 'min:1'],
            'permission.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        $role->syncPermissions($validated['permission']);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role): View
    {
        $rolePermissions = $role->permissions()->orderBy('name')->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit(Role $role): View
    {
        $permission = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->all();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permission' => ['required', 'array', 'min:1'],
            'permission.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permission']);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'Admin') {
            return redirect()
                ->route('roles.index')
                ->with('error', 'The Admin role cannot be deleted.');
        }

        if (DB::table('model_has_roles')->where('role_id', $role->id)->exists()) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'This role is assigned to users and cannot be deleted.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
