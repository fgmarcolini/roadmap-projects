<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Register extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated();

        $role = Role::findOrCreate('user');
        $user = User::create($validated);
        $user->assignRole($role);

        Auth::login($user);

        return redirect()->route('articles.index')->with('success', 'Logged in successfully!');
    }
}
