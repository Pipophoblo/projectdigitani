<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetRequest;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Process the forgot password form.
     */
    public function sendPasswordResetRequest(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();
        
        // Check for existing pending requests
        $existingRequest = PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
            
        if ($existingRequest) {
            return back()->with('info', 'A password reset request for this email is already pending. Please wait for an admin to process it.');
        }

        // Create a new password reset request
        $passwordReset = PasswordResetRequest::create([
            'user_id' => $user->id,
            'token' => Str::random(60),
            'status' => 'pending',
        ]);

        // Notify administrators
        $admins = User::where('role', 'Admin')->get();
        foreach ($admins as $admin) {
            UserNotification::create([
                'user_id' => $admin->id,
                'type' => 'system',
                'sender_id' => $user->id,
                'message' => "New password reset request from {$user->name} ({$user->email}).",
                'link' => route('admin.password-resets.edit', $passwordReset),
            ]);
        }

        return back()->with('status', 'Your password reset request has been submitted. An administrator will process your request and set a new password for you.');
    }
}