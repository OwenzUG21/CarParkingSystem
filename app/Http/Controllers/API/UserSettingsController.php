<?php

namespace App\Http\Controllers\API;

// app/Http/Controllers/API/UserSettingsController.php


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserSettingsController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'vehicle' => 'sometimes|string',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address', 'vehicle']));

        return response()->json([
            'data' => [
                'user' => $user
            ],
            'message' => 'Profile updated successfully'
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();

        $user->update($request->only([
            'email_notifications', 'sms_notifications', 'push_notifications',
            'two_factor', 'share_location', 'theme_color', 'dark_mode'
        ]));

        return response()->json([
            'data' => [
                'user' => $user
            ],
            'message' => 'Settings updated successfully'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}