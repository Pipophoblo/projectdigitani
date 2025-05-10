@extends('layouts.app')

@section('title', 'Register - IPB Digitani')

@section('styles')
<style>
    .auth-container {
        max-width: 500px;
        margin: 50px auto;
        padding: 30px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .auth-header {
        margin-bottom: 30px;
        text-align: center;
    }

    .auth-header h1 {
        font-size: 24px;
        color: #003087;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .btn-primary {
        background-color: #003087;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #002066;
    }

    .mt-4 {
        margin-top: 20px;
    }

    .text-center {
        text-align: center;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h1>Register for IPB Digitani Forum</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                <option value="" disabled selected>Select your role</option>
                <option value="Petani" {{ old('role') == 'Petani' ? 'selected' : '' }}>Petani</option>
                <option value="Mahasiswa" {{ old('role') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="Peneliti" {{ old('role') == 'Peneliti' ? 'selected' : '' }}>Peneliti</option>
                <option value="Dosen" {{ old('role') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="Umum" {{ old('role') == 'Umum' ? 'selected' : '' }}>Umum</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <small class="form-text text-muted">Password must be at least 8 characters</small>
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <button type="submit" class="btn-primary">
                Register
            </button>
        </div>

        <div class="mt-4 text-center">
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </form>
</div>
@endsection