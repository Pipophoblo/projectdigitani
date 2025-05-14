<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin {name?} {email?} {password?}';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (!$name) {
            $name = $this->ask('Enter admin name');
        }

        if (!$email) {
            $email = $this->ask('Enter admin email');
        }

        // Validate email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email or email already exists!');
            return 1;
        }

        if (!$password) {
            $password = $this->secret('Enter admin password (min 8 characters)');
            
            if (strlen($password) < 8) {
                $this->error('Password must be at least 8 characters!');
                return 1;
            }
            
            $confirmPassword = $this->secret('Confirm password');
            
            if ($password !== $confirmPassword) {
                $this->error('Passwords do not match!');
                return 1;
            }
        }

        // Create the admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'Admin'
        ]);

        $this->info("Admin user [{$user->email}] created successfully!");
        return 0;
    }
}