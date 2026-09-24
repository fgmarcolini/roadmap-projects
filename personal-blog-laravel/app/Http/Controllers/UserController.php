<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $user = Auth::user();
        return view('users.index', [
            'users' =>
            User::latest()
                ->except($user)
                ->simplePaginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', User::class);

        $roles = Role::latest()->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create(
            collect($validated)
                ->only(['name', 'email', 'password'])
                ->toArray()
        );
        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('edit', $user);

        $roles = Role::latest()->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->update(
            collect($validated)
                ->only(['name', 'email', 'password'])
                ->filter()
                ->all()
        );

        if (isset($validated['role'])) {
            $user->syncRoles($validated['role']);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return back()
            ->with('success', 'User deleted succesfully!');
    }
}
