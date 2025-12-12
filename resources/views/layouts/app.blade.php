<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - APK SPMI</title>
    
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-brown: #996600;
            --secondary-brown: #b37400;
            --accent-brown: #cc9900;
            --dark-brown: #7a5200;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            padding: 140px 0 100px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff" fill-opacity="0.03" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 0.5rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-brown) !important;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-link {
            color: #374151 !important;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem !important;
        }
        
        .nav-link:hover, .nav-link:focus {
            color: var(--primary-brown) !important;
            background-color: rgba(153, 102, 0, 0.05);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            color: #374151;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-brown);
            color: white;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown));
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-brown), var(--dark-brown));
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(153, 102, 0, 0.3);
        }

        /* Footer Styling */
        .custom-footer {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            color: #374151 !important;
            border-top: 1px solid #e5e7eb;
        }

        .custom-footer h4,
        .custom-footer h6 {
            color: var(--primary-brown) !important;
            font-weight: 600;
        }

        .custom-footer p {
            color: #374151 !important;
            margin-bottom: 0.5rem;
        }

        .custom-footer .opacity-75 {
            opacity: 0.8 !important;
        }

        .custom-footer i {
            color: var(--primary-brown) !important;
        }

        /* Fix dropdown hover */
        .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }

        /* Main content styling */
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }

        /* Card styling */
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

        /* Table styling */
        .table-custom {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-custom thead th {
            background: var(--primary-brown);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        
        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .table-custom tbody tr:hover {
            background-color: rgba(153, 102, 0, 0.05);
        }

        /* Badge styling */
        .iku-badge {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 100px 0 60px;
            }
            
            .navbar {
                padding: 0.5rem;
            }
            
            .navbar-nav {
                text-align: center;
                margin-top: 1rem;
            }

            .main-content {
                padding: 1rem 0;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 80px 0 40px;
            }
            
            .hero-section h1.display-4 {
                font-size: 2rem;
            }
            
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .navbar-brand img {
                height: 35px !important;
            }
            
            .custom-footer .text-lg-end {
                text-align: center !important;
                margin-top: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
           <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}"
                    alt="Logo IKIP"
                    style="height:40px; width:auto; object-fit:contain;">
                <span class="fw-bold">SPMI</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('landing.page') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    @auth
                    <!-- Menu untuk user yang sudah login -->
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dokumen-publik.index') }}">
                            <i class="fas fa-globe me-1"></i>Dokumen Publik
                        </a>
                    </li>
                    @endauth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-building me-1"></i>Unit Kerja
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('upt.index') }}">
                                    <i class="fas fa-university me-2"></i>UPT
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('bagian.index') }}">
                                    <i class="fas fa-sitemap me-2"></i>Bagian
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('program-studi.index') }}">
                                    <i class="fas fa-graduation-cap me-2"></i>Program Studi
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" style="padding-top: 76px;">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="custom-footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <h4 class="fw-bold mb-3">SPMI</h4>
                    <p class="mb-4">Sistem Penjaminan Mutu Internal - IKIP Siliwangi</p>
                    <p class="opacity-75">&copy; 2024 SPMI IKIP Siliwangi. All rights reserved.</p>
                </div>
               <div class="col-lg-6 col-12 text-lg-end text-center mt-3 mt-lg-0">
                    <h6 class="fw-semibold mb-3">Kontak Kami</h6>
                    <p class="opacity-75 mb-2">
                        <i class="fas fa-envelope me-2"></i>spmi@ikipsiliwangi.ac.id
                    </p>
                    <p class="opacity-75 mb-2">
                        <i class="fas fa-phone me-2"></i>+62 22 1234 5678
                    </p>
                    <p class="opacity-75 mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>IKIP Siliwangi, Bandung
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Debug: Pastikan Bootstrap tersedia
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🌐 App layout loaded');
            console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');
            
            if (typeof bootstrap === 'undefined') {
                console.error('❌ Bootstrap tidak terload!');
            } else {
                console.log('✅ Bootstrap components available:');
                console.log('  - Modal:', typeof bootstrap.Modal !== 'undefined');
                console.log('  - Dropdown:', typeof bootstrap.Dropdown !== 'undefined');
                console.log('  - Tooltip:', typeof bootstrap.Tooltip !== 'undefined');
                console.log('  - Popover:', typeof bootstrap.Popover !== 'undefined');
            }
            
            // Export bootstrap ke window object untuk akses global
            window.bootstrap = bootstrap;
            
            // Inisialisasi dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            // Inisialisasi tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>