<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withTrashed()->orderBy('id', 'desc');
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $allowedRoles = auth()->user()->role === 'administrator' ? ['administrator', 'admin', 'organizer', 'client'] : ['organizer', 'client'];
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', \Illuminate\Validation\Rule::in($allowedRoles)],
            'artist_name' => ['nullable', 'string', 'max:255', 'required_if:role,organizer'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index', ['role' => $validated['role']])->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (auth()->user()->role !== 'administrator' && in_array($user->role, ['admin', 'administrator'])) {
            abort(403, 'You do not have permission to manage structural admins.');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'administrator' && in_array($user->role, ['admin', 'administrator'])) {
            abort(403, 'You do not have permission to manage structural admins.');
        }

        $allowedRoles = auth()->user()->role === 'administrator' ? ['administrator', 'admin', 'organizer', 'client'] : ['organizer', 'client'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', \Illuminate\Validation\Rule::in($allowedRoles)],
            'artist_name' => ['nullable', 'string', 'max:255', 'required_if:role,organizer'],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', \Illuminate\Validation\Rules\Password::defaults()]]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index', ['role' => $validated['role']])->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'administrator' && in_array($user->role, ['admin', 'administrator'])) {
            abort(403, 'You do not have permission to deactivate structural admins.');
        }

        $role = $user->role;
        $user->delete(); // Soft delete
        return redirect()->route('admin.users.index', ['role' => $role])->with('success', 'User deactivated successfully.');
    }

    public function activate($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        if (auth()->user()->role !== 'administrator' && in_array($user->role, ['admin', 'administrator'])) {
            abort(403, 'You do not have permission to activate structural admins.');
        }
        
        $role = $user->role;
        $user->restore(); // Restore soft deleted
        return redirect()->route('admin.users.index', ['role' => $role])->with('success', 'User activated successfully.');
    }
}
