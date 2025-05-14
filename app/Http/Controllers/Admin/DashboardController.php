<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Thread;
use App\Models\Comment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalThreads = Thread::count();
        $totalComments = Comment::count();
        
        $recentUsers = User::latest()->take(5)->get();
        $recentThreads = Thread::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalThreads', 
            'totalComments',
            'recentUsers',
            'recentThreads'
        ));
    }
}