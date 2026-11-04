<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Support\AccessPermissions;

class UserManagementController extends Controller
{
    private function requireAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'super_admin', 403, 'Only Super Admin can manage users.');
    }

    public function index(Request $request)
    {
        $this->requireAdmin($request);
        return view('users.index', [
            'users' => User::orderBy('name')->get(['id', 'name', 'email', 'role', 'avatar_path', 'is_active', 'permissions']),
        ]);
    }

    public function store(Request $request)
    {
        $this->requireAdmin($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', Rule::in(AccessPermissions::keys())],
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = 'user'; // Never take role from the browser.
        $user->is_active = true;
        $user->permissions = AccessPermissions::normalize($data['permissions'] ?? []);
        if ($request->hasFile('avatar')) {
            $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'Normal user created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $this->requireAdmin($request);
        abort_unless($user->role === 'user', 403, 'Super Admin account must be edited through My Profile.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', Rule::in(AccessPermissions::keys())],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->permissions = AccessPermissions::normalize($data['permissions'] ?? []);
        if (!empty($data['password'])) $user->password = Hash::make($data['password']);
        $previous = null;
        if ($request->hasFile('avatar')) {
            $previous = $user->avatar_path;
            $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }
        $user->save();
        if ($previous) Storage::disk('public')->delete($previous);

        return $request->expectsJson()
            ? response()->json([
                'success' => true, 'message' => 'User updated successfully.',
                'user' => [
                    'id' => $user->id, 'name' => $user->name, 'email' => $user->email,
                    'avatar_url' => $user->avatar_path ? Storage::disk('public')->url($user->avatar_path) : null,
                    'permissions' => $user->permissions ?? [],
                ],
            ])
            : redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        $this->requireAdmin($request);
        abort_unless($user->role === 'user', 403, 'Super Admin cannot be deactivated.');
        $user->is_active = !$user->is_active;
        $user->save();
        $message = $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.';
        return $request->expectsJson()
            ? response()->json(['message' => $message, 'is_active' => (bool) $user->is_active])
            : redirect()->route('users.index')->with('success', $message);
    }

    public function destroy(Request $request, User $user)
    {
        $this->requireAdmin($request);
        abort_unless($user->role === 'user', 403, 'Super Admin cannot be deleted.');
        // Avoid deleting an account with audit/history implications by accident.
        // This deletion concerns only the user row. Review user-owned data relations before enabling in production.
        $user->delete();
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }
        return $request->expectsJson()
            ? response()->json(['message' => 'User deleted successfully.'])
            : redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

}
