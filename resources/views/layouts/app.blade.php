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
            line-height: 1.5;
        }
        
        .user-name:hover {
            text-decoration: underline;
        }
        
        /* Search container */
        .search-container {
            margin: 10px auto;
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
        
        .notification-dropdown {
        position: relative;
        display: inline-block;
        margin-right: 15px;
        }

        .bell {
            font-size: 20px;
            cursor: pointer;
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background-color: red;
            color: rgb(237, 223, 223);
            border-color: black;
            border-radius: 50%;
            padding: 2px 5px;
            font-size: 10px;
            min-width: 15px;
            text-align: center;
        }

        .notification-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #3648b1;
            min-width: 320px;
            max-width: 400px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 10;
            border-radius: 5px;
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid #4666b8;
            position: sticky;
            top: 0;
            background-color:#598fda;
            z-index: 1;
        }

        .notification-header h3 {
            margin: 0;
            font-size: 16px;
        }

        .notification-header button {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 12px;
            cursor: pointer;
            padding: 0;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #14056a;
            cursor: pointer;
        }

        .notification-item.unread {
            background-color: #2c2b49;
        }

        .notification-sender {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .notification-message {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .notification-time {
            color: #9bc7ea;
            font-size: 12px;
        }

        .notification-empty {
            padding: 15px;
            text-align: center;
            color: #666;
        }

        .container {
            max-width: 1500px;
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
                <li><a href="{{ route('articles.index') }}">ARTIKEL</a></li>                <li><a href="{{ route('forum.index') }}">FORUM TANI</a></li>
            </ul>
            <div class="user-section">
                @guest
                    <div class="auth-links">
                        <a href="{{ route('login') }}" class="user-name">LOGIN</a>
                        <a href="{{ route('register') }}" class="user-name">REGISTER</a>
                    </div>
                @else
                    <div class="notification-dropdown">
    <span class="bell" id="notification-bell">🔔<span class="notification-badge" id="notification-count" style="display: none;"></span></span>
    <div class="notification-content" id="notification-content">
        <div class="notification-header">
            <h3>Notifikasi</h3>
            <button id="mark-all-read">Tandai semua sudah dibaca</button>
        </div>
        <div class="notification-list" id="notification-list">
            <div class="notification-empty">Memuat notifikasi...</div>
        </div>
    </div>
</div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBell = document.getElementById('notification-bell');
        const notificationContent = document.getElementById('notification-content');
        const notificationList = document.getElementById('notification-list');
        const notificationCount = document.getElementById('notification-count');
        const markAllReadBtn = document.getElementById('mark-all-read');
        
        // Check if elements exist (user is logged in)
        if (!notificationBell) return;
        
        // Toggle notification dropdown
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Toggle notification content
            const isDisplayed = notificationContent.style.display === 'block';
            notificationContent.style.display = isDisplayed ? 'none' : 'block';
            
            // Load notifications when opening dropdown
            if (!isDisplayed) {
                loadNotifications();
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            notificationContent.style.display = 'none';
        });
        
        // Prevent closing when clicking inside dropdown
        notificationContent.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Mark all notifications as read
        markAllReadBtn.addEventListener('click', function() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error:', error));
        });
        
        // Function to load notifications
        function loadNotifications() {
            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationBadge(data.unread_count);
                        renderNotifications(data.notifications);
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML = '<div class="notification-empty">Error loading notifications</div>';
                });
        }
        
        // Function to update notification badge
        function updateNotificationBadge(count) {
            if (count > 0) {
                notificationCount.textContent = count > 99 ? '99+' : count;
                notificationCount.style.display = 'block';
            } else {
                notificationCount.style.display = 'none';
            }
        }
        
        // Function to render notifications
        function renderNotifications(notifications) {
            if (notifications.length === 0) {
                notificationList.innerHTML = '<div class="notification-empty">Tidak ada notifikasi</div>';
                return;
            }
            
            notificationList.innerHTML = '';
            
            notifications.forEach(notification => {
                const notificationItem = document.createElement('div');
                notificationItem.className = 'notification-item' + (notification.is_read ? '' : ' unread');
                notificationItem.dataset.id = notification.id;
                
                // Format timestamp
                const date = new Date(notification.created_at);
                const formattedDate = date.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'long', 
                    year: 'numeric',
                    hour: '2-digit', 
                    minute: '2-digit'
                });
                
                notificationItem.innerHTML = `
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">${formattedDate}</div>
                `;
                
                // Add click event to notification item
                notificationItem.addEventListener('click', function() {
                    // Mark as read if unread
                    if (!notification.is_read) {
                        markAsRead(notification.id);
                    }
                    
                    // Redirect to notification link if exists
                    if (notification.link) {
                        window.location.href = notification.link;
                    }
                });
                
                notificationList.appendChild(notificationItem);
            });
        }
        
        // Function to mark notification as read
        function markAsRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    const notificationItem = document.querySelector(`.notification-item[data-id="${id}"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                    }
                    
                    // Reload notifications to update badge
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Load notifications initially
        loadNotifications();
        
        // Load notifications every 30 seconds
        setInterval(loadNotifications, 30000);
    });
</script>
</body>
</html>