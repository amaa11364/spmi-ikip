<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>@yield('title', 'Dashboard') - Q-TRACK SPMI</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}">

    <!-- SEO: Prevent Search Engine Indexing -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <meta name="slurp" content="noindex, nofollow">
    <meta name="duckduckbot" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-brown: #996600;
            --secondary-brown: #b37400;
            --dark-brown: #7a5200;
            --light-brown: #fff9e6;
            --success-green: #28a745;
            --info-blue: #17a2b8;
            --warning-yellow: #ffc107;
            --danger-red: #dc3545;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            width: 100%;
        }
        
        /* ===== LAYOUT CONTAINER ===== */
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            width: 100%;
        }
        
        /* ===== SIDEBAR STYLES ===== */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: left 0.3s ease;
            z-index: 1050;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .sidebar-logo {
            width: 45px;
            height: auto;
        }

        .sidebar-brand-text h5 {
            margin: 0;
            font-weight: 600;
            color: white;
            font-size: 18px;
        }

        .sidebar-brand-text small {
            color: rgba(255,255,255,0.8);
            font-size: 11px;
            display: block;
            margin-top: 2px;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-badge.admin {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .role-badge.verifikator {
            background: var(--info-blue);
            color: white;
        }

        .role-badge.user {
            background: var(--success-green);
            color: white;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            padding: 15px 0;
            height: calc(100vh - 160px);
            overflow-y: auto;
        }

        .nav-section {
            padding: 15px 15px 5px;
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-item {
            list-style: none;
            margin: 2px 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 12px;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .nav-link span {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-link .badge {
            background: var(--danger-red);
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            min-width: 18px;
            text-align: center;
        }

        .nav-link .badge.warning {
            background: var(--warning-yellow);
            color: var(--dark-gray);
        }

        /* Dropdown Styles */
        .dropdown-toggle {
            cursor: pointer;
            justify-content: space-between;
        }

        .dropdown-arrow {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .dropdown-menu-custom {
            display: none;
            list-style: none;
            padding: 5px 0;
            margin: 0;
            background: rgba(0,0,0,0.2);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 8px 15px 8px 45px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            gap: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .dropdown-item i {
            width: 16px;
            font-size: 13px;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .text-danger {
            color: #ff6b6b !important;
        }

        .text-danger:hover {
            background: rgba(220, 53, 69, 0.2) !important;
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar,
        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track,
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb,
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content-wrapper {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            background-color: #f4f6f9;
            transition: margin-left 0.3s ease;
            width: calc(100% - 280px);
        }
        
        /* ===== TOP NAVBAR ===== */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 0 20px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1040;
            width: 100%;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--primary-brown);
            font-size: 20px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-toggle:hover {
            background: var(--light-brown);
        }
        
        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-title i {
            color: var(--primary-brown);
            font-size: 20px;
        }
        
        .search-box {
            background: var(--light-gray);
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            width: 300px;
        }
        
        .search-box i {
            color: #adb5bd;
            margin-right: 10px;
            font-size: 14px;
        }
        
        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notification-icon {
            position: relative;
            color: #6c757d;
            font-size: 18px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .notification-icon:hover {
            background: var(--light-gray);
        }
        
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--danger-red);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: var(--light-brown);
            height: 45px;
        }
        
        .user-profile:hover {
            background: #ffe6cc;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            background: var(--primary-brown);
            flex-shrink: 0;
        }
        
        .avatar-color-0 { background: #996600; }
        .avatar-color-1 { background: #aa7700; }
        .avatar-color-2 { background: #bb8800; }
        .avatar-color-3 { background: #cc9900; }
        .avatar-color-4 { background: #ddaa00; }
        .avatar-color-5 { background: #eebb00; }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 13px;
            color: var(--dark-gray);
            line-height: 1.2;
        }
        
        .user-role {
            font-size: 10px;
            color: #6c757d;
            line-height: 1.2;
        }
        
        /* Dropdown Menu User */
        .dropdown-menu-custom-user {
            position: absolute;
            top: 50px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            width: 200px;
            display: none;
            z-index: 1060;
            border: 1px solid #e9ecef;
        }
        
        .dropdown-menu-custom-user.show {
            display: block;
        }
        
        .dropdown-menu-custom-user .dropdown-item {
            padding: 10px 15px;
            color: var(--dark-gray);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }
        
        .dropdown-menu-custom-user .dropdown-item i {
            width: 18px;
            color: var(--primary-brown);
        }
        
        .dropdown-menu-custom-user .dropdown-item:hover {
            background: var(--light-brown);
        }
        
        .dropdown-divider {
            height: 1px;
            background: #e9ecef;
            margin: 5px 0;
        }
        
        /* ===== CONTENT AREA ===== */
        .content-area {
            padding: 20px;
            min-height: calc(100vh - 60px);
        }
        
        /* ===== CARD STYLING ===== */
        .custom-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .custom-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        /* ===== FLASH MESSAGES ===== */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 12px 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        /* ===== MOBILE STYLES ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1045;
            backdrop-filter: blur(2px);
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Loading Spinner */
        .spinner-wrapper {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .spinner-wrapper.show {
            display: flex;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--light-brown);
            border-top: 4px solid var(--primary-brown);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* ===== RESPONSIVE DESIGN - FIXED ===== */
        /* Tablet */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -280px;
                box-shadow: none;
            }
            
            .sidebar.show {
                left: 0;
                box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            }
            
            .main-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .search-box {
                width: 250px;
            }
        }
        
        /* Mobile Landscape */
        @media (max-width: 767.98px) {
            .navbar-custom {
                padding: 0 15px;
                height: 55px;
            }
            
            .page-title {
                font-size: 16px;
            }
            
            .page-title i {
                font-size: 18px;
            }
            
            .search-box {
                display: none;
            }
            
            .user-info {
                display: none;
            }
            
            .user-profile {
                padding: 5px 8px;
                background: transparent;
            }
            
            .user-profile:hover {
                background: var(--light-gray);
            }
            
            .user-avatar {
                width: 35px;
                height: 35px;
                margin-right: 0;
            }
            
            .notification-icon {
                width: 35px;
                height: 35px;
            }
            
            .content-area {
                padding: 15px;
            }
            
            .dropdown-menu-custom-user {
                right: 10px;
                width: 180px;
            }
        }
        
        /* Mobile Portrait */
        @media (max-width: 575.98px) {
            .sidebar {
                width: 260px;
            }
            
            .navbar-left {
                gap: 10px;
            }
            
            .mobile-menu-toggle {
                width: 35px;
                height: 35px;
                font-size: 18px;
            }
            
            .page-title {
                font-size: 15px;
            }
            
            .page-title i {
                display: none;
            }
            
            .navbar-right {
                gap: 10px;
            }
            
            .notification-icon {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
            
            .content-area {
                padding: 12px;
            }
            
            .alert {
                padding: 10px 12px;
                font-size: 13px;
            }
        }
        
        /* Small Mobile */
        @media (max-width: 375px) {
            .sidebar {
                width: 240px;
            }
            
            .page-title {
                font-size: 14px;
            }
            
            .navbar-right {
                gap: 5px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Loading Spinner -->
    <div class="spinner-wrapper" id="loadingSpinner">
        <div class="spinner"></div>
    </div>

    <div class="app-container">
        <!-- Mobile Menu Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            @include('layouts.sidebar')
        </aside>
        
        <!-- Main Content -->
        <main class="main-content-wrapper">
            <!-- Top Navigation -->
            <nav class="navbar-custom">
                <div class="navbar-left">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">
                        @hasSection('page-icon')
                            <i class="fas @yield('page-icon')"></i>
                        @else
                            <i class="fas fa-tachometer-alt"></i>
                        @endif
                        <span>@yield('title', 'Dashboard')</span>
                    </h1>
                </div>
                
                <div class="navbar-right">
                    <!-- Search Box -->
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari dokumen..." id="globalSearch">
                    </div>
                    
                    <!-- Notifications -->
                    <div class="notification-icon" id="notificationIcon">
                        <i class="far fa-bell"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="notification-badge">{{ $unreadNotifications }}</span>
                        @endif
                    </div>
                    
                    <!-- User Profile -->
                    @php
                        $user = auth()->user();
                        $initial = $user ? strtoupper(substr($user->name, 0, 1)) : 'U';
                        $avatarColor = 'avatar-color-' . (crc32($user->id ?? 1) % 6);
                    @endphp
                    
                    <div class="user-profile" id="userProfileDropdown">
                        <div class="user-avatar {{ $avatarColor }}">
                            {{ $initial }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ $user->name ?? 'User' }}</span>
                            <span class="user-role">{{ ucfirst($user->role ?? 'User') }}</span>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 10px; color: #6c757d;"></i>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu-custom-user" id="userDropdownMenu">
                        <a href="{{ routeExists('profile.edit') ? route('profile.edit') : '#' }}" class="dropdown-item">
                            <i class="fas fa-user"></i> Profil Saya
                        </a>
                        <a href="{{ routeExists('profile.change-password') ? route('profile.change-password') : '#' }}" class="dropdown-item">
                            <i class="fas fa-key"></i> Ubah Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </div>
            </nav>
            
            <!-- Content Area -->
            <div class="content-area">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ routeExists('logout') ? route('logout') : '#' }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const userProfile = document.getElementById('userProfileDropdown');
            const userDropdown = document.getElementById('userDropdownMenu');
            
            // Toggle sidebar mobile
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('active');
                });
            }
            
            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('active');
                });
            }
            
            // Toggle user dropdown
            if (userProfile && userDropdown) {
                userProfile.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    userDropdown.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userProfile.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.remove('show');
                    }
                });
            }
            
            // Close sidebar on window resize if open
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('active');
                }
            });
            
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            console.log('✅ Layout loaded - Mobile responsive fixed');
        });

        // Function to toggle dropdown in sidebar
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const arrow = document.getElementById(id.replace('Dropdown', 'Arrow'));
            
            if (dropdown) {
                if (dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                    if (arrow) arrow.style.transform = 'rotate(0deg)';
                } else {
                    dropdown.style.display = 'block';
                    if (arrow) arrow.style.transform = 'rotate(90deg)';
                }
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>