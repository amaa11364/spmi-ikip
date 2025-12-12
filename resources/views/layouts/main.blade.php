<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Q-TRACK SPMI</title>
    
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
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            min-height: 100vh;
            padding: 0;
            position: fixed;
            width: 250px;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 1.5rem;
            margin: 4px 0;
            border-radius: 0;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid white;
        }
        
        .sidebar-menu .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
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

        /* Content specific styles */
        .content-area {
            padding: 2rem;
        }
        
        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .stats-badge {
            background: rgba(255,255,255,0.2);
            border-radius: 25px;
            padding: 8px 20px;
            font-size: 0.9rem;
        }
        
        /* Mobile Styles */
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
        
        @media (max-width: 991.98px) {
            .sidebar {
                left: -100%;
                top: 0;
                z-index: 1050;
                width: 280px;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .mobile-menu-btn {
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
            
            .welcome-card {
                padding: 1.5rem;
            }
            
            .search-box input {
                width: 150px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-custom {
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
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navigation -->
                @include('layouts.topnav')

                <!-- Content Area -->
                <div class="content-area">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Pastikan Bootstrap tersedia
        if (typeof bootstrap === 'undefined') {
            console.error('❌ Bootstrap tidak terload di admin layout!');
        } else {
            console.log('✅ Bootstrap loaded in admin layout');
        }
        
        // Mobile sidebar toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Auto-close sidebar on link click in mobile
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    document.querySelector('.sidebar').classList.remove('show');
                }
            });
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            
            if (window.innerWidth < 992 && 
                !sidebar.contains(event.target) && 
                !mobileBtn.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
        
        // Inisialisasi Bootstrap components
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🏢 Admin layout loaded');
            
            // Inisialisasi dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>