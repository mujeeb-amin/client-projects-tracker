<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountDetailsController extends Controller
{
    // Update the logged-in user's name and profile photo.
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user->name = $data['name'];

        if ($request->hasFile('avatar')) {

            // Keep the previous photo path.
            $previous = $user->avatar_path;

            // Store the new photo in storage/app/public/profile-photos.
            $newPath = $request->file('avatar')
                ->store('profile-photos', 'public');

            $user->avatar_path = $newPath;

            $user->save();

            // Delete the previous photo after saving the new one.
            if ($previous && $previous !== $newPath) {
                Storage::disk('public')->delete($previous);
            }

        } else {
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar_path
                    ? Storage::disk('public')->url($user->avatar_path)
                    : null,
            ],
        ]);
    }
}