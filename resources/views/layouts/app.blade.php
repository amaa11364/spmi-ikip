<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - APK SPMI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        
        .navbar-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
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
        
        .dropdown-toggle::after {
            margin-left: 0.5rem;
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

        /* Logo fallback styling */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-fallback {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-brown), var(--dark-brown));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        /* Footer Styling - SOLUSI FIX */
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
        /* TAMBAHKAN DI DALAM <style> */
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
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
           <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                {{-- Logo IKIP --}}
                <img src="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}"
                    alt="Logo IKIP"
                    style="height:40px; width:auto; object-fit:contain;">

                {{-- Teks APK SPMI --}}
                <span class="fw-bold">SPMI</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Menu Home -->
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('landing.page') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <!-- Dropdown Unit Kerja -->
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
    <main style="padding-top: 76px;">
        @yield('content')
    </main>

    <!-- Footer - DIPERBAIKI -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');
            
            // Initialize dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });

            // Debug logo path
            console.log('Logo path:', "{{ asset('images/photos/LOGO-IKIP.png') }}");
        });
    </script>
</body>
</html>