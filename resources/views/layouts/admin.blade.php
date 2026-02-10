<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin SPMI</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-brown: #996600;
            --secondary-brown: #b37400;
            --accent-brown: #cc9900;
            --dark-brown: #7a5200;
            --light-brown: #fff9e6;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        
        .admin-container {
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .admin-content {
            padding: 2rem 0;
        }
        
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }
        
        .admin-card-header {
            background: linear-gradient(135deg, var(--light-brown) 0%, #fff5e6 100%);
            border-bottom: 2px solid var(--primary-brown);
            border-radius: 12px 12px 0 0;
            padding: 1.25rem 1.5rem;
        }
        
        .admin-card-body {
            padding: 1.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown));
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-brown), var(--dark-brown));
        }
        
        .badge-admin {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-cog me-2 text-primary"></i>
                        Admin Panel
                        <span class="badge badge-admin ms-2">ADMIN</span>
                    </h4>
                    <small class="text-muted">Manajemen Konten SPMI</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <small class="text-muted d-block">Login sebagai:</small>
                        <strong>{{ auth()->user()->name }}</strong>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-home me-2"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="admin-container">
        <div class="container admin-content">
            @yield('content')
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>