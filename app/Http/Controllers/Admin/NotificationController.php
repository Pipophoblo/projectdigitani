<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show the notification sending page
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.notifications.index', compact('users'));
    }

    /**
     * Send notification to user(s)
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'message' => 'required|string|max:1000',
            'link' => 'nullable|url',
        ]);

        $notificationsSent = 0;

        foreach ($validated['user_ids'] as $userId) {
            UserNotification::create([
                'user_id' => $userId,
                'type' => 'system',
                'sender_id' => Auth::id(),
                'message' => $validated['message'],
                'link' => $validated['link'] ?? null,
            ]);

            $notificationsSent++;
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', "Notifikasi berhasil dikirim ke $notificationsSent pengguna");
    }
}