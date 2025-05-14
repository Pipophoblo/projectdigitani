<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IPB Digitani Forum')</title>
    <!-- CSS -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #fff6f7;
            color: #333;
        }
        
        /* Navbar styling */
        .navbar {
            background: linear-gradient(to right, #a2c4f3, #466fbf);
            padding: 10px 30px;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 40px;
            margin-right: 10px;
        }
        
        .logo span {
            font-weight: bold;
            font-size: 18px;
        }
        
        .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
            margin: 0;
            padding: 0;
        }
        
        .nav-links li a {
            text-decoration: none;
            color: white;
            font-weight: 500;
        }
        
        .nav-links li a:hover {
            text-decoration: underline;
        }
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .bell {
            font-size: 20px;
        }
        
        .user-name {
            color: white;
            text-decoration: none;
            font-size: 14px;
            line-height: 1.2;
        }
        
        .user-name:hover {
            text-decoration: underline;
        }
        
        /* Search container */
        .search-container {
            margin: 20px auto;
            width: 80%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .search-container input[type="text"] {
            width: 100%;
            max-width: 700px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            font-size: 16px;
        }
        
        .search-container button {
            padding: 10px 15px;
            background-color: #003087;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
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
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            border-top: 1px solid #ddd;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="{{ asset('assets/image 11.png') }}" alt="IPB Digitani Logo" onerror="this.onerror=null; this.src='https://via.placeholder.com/40x40?text=IPB';">
            </div>
            <ul class="nav-links">
                <li><a href="/">HOME</a></li>
                <li><a href="#">KONSULTANI</a></li>
                <li><a href="#">ARTIKEL</a></li>
                <li><a href="{{ route('forum.index') }}">FORUM TANI</a></li>
            </ul>
            <div class="user-section">
                @guest
                    <div class="auth-links">
                        <a href="{{ route('login') }}" class="user-name">LOGIN</a>
                        <a href="{{ route('register') }}" class="user-name">REGISTER</a>
                    </div>
                @else
                    <span class="bell">🔔</span>
                    <div class="dropdown">
                        <a href="#" class="user-name">
                            {{ Auth::user()->name }}<br>
                            <small>{{ Auth::user()->role ?? 'Member' }}</small>
                        </a>
                        <div class="dropdown-content">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-btn">Logout</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} IPB Digitani Forum. All rights reserved.</p>
    </footer>

    <style>
        /* Dropdown styles for user menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-btn {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            background: none;
            width: 100%;
            text-align: left;
            border: none;
            cursor: pointer;
        }
        
        .dropdown-btn:hover {
            background-color: #f1f1f1;
        }
        
        .auth-links {
            display: flex;
            gap: 15px;
        }
    </style>
@yield('scripts')
</body>
</html>