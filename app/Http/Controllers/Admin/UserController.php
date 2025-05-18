<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    
    public function show(User $user)
    {
        // Load user related data
        $user->load('threads', 'comments');
        
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:Admin,Petani,Mahasiswa,Peneliti,Dosen,Umum',
        ]);
        
        $validatedData['password'] = Hash::make($validatedData['password']);
        
        User::create($validatedData);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
    
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|string|in:Admin,Petani,Mahasiswa,Peneliti,Dosen,Umum',
        ]);
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $validatedData['password'] = Hash::make($request->password);
        }
        
        $user->update($validatedData);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
    
    public function destroy(User $user)
    {
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}