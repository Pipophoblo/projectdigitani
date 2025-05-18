<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\UserNotification;

class PasswordResetController extends Controller
{
    /**
     * Display a listing of all password reset requests.
     */
    public function index()
    {
        $requests = PasswordResetRequest::with(['user'])
            ->latest()
            ->paginate(10);
        
        return view('admin.password-resets.index', compact('requests'));
    }

    /**
     * Show the form for processing a password reset request.
     */
    public function edit(PasswordResetRequest $passwordReset)
    {
        if (!$passwordReset->isPending()) {
            return redirect()->route('admin.password-resets.index')
                ->with('error', 'This request has already been processed.');
        }

        $passwordReset->load('user');
        
        return view('admin.password-resets.edit', compact('passwordReset'));
    }

    /**
     * Process the password reset request.
     */
    public function update(Request $request, PasswordResetRequest $passwordReset)
    {
        if (!$passwordReset->isPending()) {
            return redirect()->route('admin.password-resets.index')
                ->with('error', 'This request has already been processed.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'new_password' => 'required_if:action,approve|nullable|min:8|confirmed',
        ]);

        $passwordReset->status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        $passwordReset->processed_by = Auth::id();
        $passwordReset->processed_at = now();
        $passwordReset->save();

        // If approved, update the user's password
        if ($validated['action'] === 'approve') {
            $user = $passwordReset->user;
            $user->password = Hash::make($validated['new_password']);
            $user->save();

            // Create a notification for the user
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'system',
                'sender_id' => Auth::id(),
                'message' => 'Your password has been reset by an administrator.',
            ]);

            return redirect()->route('admin.password-resets.index')
                ->with('success', "Password for {$user->name} has been reset successfully.");
        }

        // Create a notification for rejection
        UserNotification::create([
            'user_id' => $passwordReset->user_id,
            'type' => 'system',
            'sender_id' => Auth::id(),
            'message' => 'Your password reset request has been rejected. Please contact an administrator for assistance.',
        ]);

        return redirect()->route('admin.password-resets.index')
            ->with('success', 'Password reset request has been rejected.');
    }

    /**
     * Reset password directly from user profile.
     */
    public function resetUserPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        // Create a notification for the user
        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'sender_id' => Auth::id(),
            'message' => 'Your password has been reset by an administrator.',
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Password reset successfully.');
    }
}