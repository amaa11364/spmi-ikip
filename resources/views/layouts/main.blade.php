<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Q-TRACK SPMI</title>

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
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* ===== LAYOUT CONTAINER ===== */
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar-wrapper {
            width: 250px;
            min-width: 250px;
            background: linear-gradient(180deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        /* ===== MAIN CONTENT ===== */
        .main-content-wrapper {
            flex: 1;
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }
        
        /* ===== CONTENT AREA ===== */
        .content-area {
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }
        
        /* ===== SIDEBAR INNER STYLING ===== */
        .sidebar-inner {
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }
        
        .sidebar-menu {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }
        
        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 1.5rem;
            margin: 2px 0;
            border-radius: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid white;
        }
        
        .sidebar-menu .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        .sidebar-menu .text-muted {
            color: rgba(255,255,255,0.6) !important;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .sidebar-menu .nav-link.text-danger:hover {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b !important;
            border-left: 3px solid #ff6b6b;
        }
        
        /* ===== TOP NAVBAR ===== */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .search-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            padding: 8px 20px;
        }
        
        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            width: 250px;
        }
        
        .user-profile {
            background: var(--light-brown);
            border-radius: 50px;
            padding: 8px 15px 8px 8px;
            cursor: pointer;
            position: relative;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .avatar-color-0 { background: #996600; }
        .avatar-color-1 { background: #aa7700; }
        .avatar-color-2 { background: #bb8800; }
        .avatar-color-3 { background: #cc9900; }
        .avatar-color-4 { background: #ddaa00; }
        .avatar-color-5 { background: #eebb00; }

        /* ===== SCROLLBAR STYLING ===== */
        .sidebar-wrapper::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-wrapper::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }
        
        .sidebar-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
        
        /* Untuk menu scroll */
        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
        }
        
        /* ===== MOBILE STYLES ===== */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--primary-brown);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            color: white;
        }
        
        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        
        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                left: -100%;
                top: 0;
                z-index: 1050;
                width: 280px;
                box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            }
            
            .sidebar-wrapper.show {
                left: 0;
            }
            
            .main-content-wrapper {
                margin-left: 0 !important;
                width: 100%;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .search-box input {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }
            
            .navbar-custom {
                padding: 1rem;
            }
            
            .search-box input {
                width: 150px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-custom {
                flex-direction: column;
                height: auto;
                padding: 1rem;
            }
            
            .search-box {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .user-profile {
                width: 100%;
                justify-content: center;
            }
            
            .mobile-menu-btn {
                top: 10px;
                left: 10px;
            }
        }
        
        /* ===== CARD STYLING ===== */
        .custom-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .custom-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        
        /* ===== FLASH MESSAGES ===== */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar-wrapper" id="sidebar">
            @include('layouts.sidebar')
        </aside>
        
        <!-- Main Content -->
        <main class="main-content-wrapper">
            <!-- Top Navigation -->
            @include('layouts.topnav')
            
            <!-- Content Area -->
            <div class="content-area">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar
            if (mobileBtn) {
                mobileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.toggle('active');
                    }
                });
            }
            
            // Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('active');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992 && 
                    !sidebar.contains(event.target) && 
                    mobileBtn && !mobileBtn.contains(event.target) &&
                    sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }
                }
            });
            
            // Auto-close sidebar on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('show');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }
                }
            });
            
            // Inisialisasi Bootstrap components
            if (typeof bootstrap !== 'undefined') {
                // Inisialisasi dropdowns
                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
            }
            
            console.log('✅ Layout loaded');
        });
    </script>
    
    @stack('scripts')
</body>
</html>