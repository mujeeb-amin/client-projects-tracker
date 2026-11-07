<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,

            'avatar_url' => $user->avatar_path
                ? Storage::disk('public')->url($user->avatar_path)
                : null,

            'email_verified' => $user->email_verified_at !== null,
            'created_at' => $user->created_at?->toIso8601String(),
            'updated_at' => $user->updated_at?->toIso8601String(),
        ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your password information.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        $user->password = Hash::make(
            $request->input('password')
        );

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}