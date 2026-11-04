<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AccountDetailsController extends Controller
{
    // Both roles may edit their OWN name and profile photo; email and role remain unchanged.
    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = $data['name'];
        if ($request->hasFile('avatar')) {
            $previous = $user->avatar_path;
            $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
            $user->save();
            if ($previous) Storage::disk('public')->delete($previous);
        } else {
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => [
                'id' => $user->id, 'name' => $user->name, 'email' => $user->email,
                'avatar_url' => $user->avatar_path ? Storage::disk('public')->url($user->avatar_path) : null,
            ],
        ]);
    }
}
