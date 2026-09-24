<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Role::class);

        return view('roles.index', ['roles' =>
        Role::query()
            ->where('name', '!=', 'admin')
            ->orderBy('name')
            ->simplePaginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Role::class);

        $permissions = Permission::latest()->get();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        Gate::authorize('create', Role::class);

        $validated = $request->validated();

        $role = Role::create([
            'name' => Str::lower($validated['name'])
        ]);
        $role->givePermissionTo($validated['permissions']);

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        Gate::authorize('update', $role);

        $permissions = Permission::latest()->get();
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('update', $role);

        $validated = $request->validated();

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);

        $role->delete();

        return back()
            ->with('success', 'Role deleted succesfully!');
    }
}
