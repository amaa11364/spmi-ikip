<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Q-TRACK SPMI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            min-height: 100vh;
            padding: 0;
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
            padding: 0;
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
        
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .program-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e9ecef;
        }
        
        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .program-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            color: white;
        }
        
        .program-1 { background: linear-gradient(135deg, #996600 0%, #b37400 100%); }
        .program-2 { background: linear-gradient(135deg, #aa7700 0%, #cc8800 100%); }
        .program-3 { background: linear-gradient(135deg, #bb8800 0%, #dd9900 100%); }
        .program-4 { background: linear-gradient(135deg, #cc9900 0%, #eeaa00 100%); }
        .program-5 { background: linear-gradient(135deg, #ddaa00 0%, #ffbb00 100%); }
        .program-6 { background: linear-gradient(135deg, #eebb00 0%, #ffcc00 100%); color: #333 !important; }
        
        .stats-badge {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        /* Avatar color based on name */
        .avatar-color-0 { background: #996600; }
        .avatar-color-1 { background: #aa7700; }
        .avatar-color-2 { background: #bb8800; }
        .avatar-color-3 { background: #cc9900; }
        .avatar-color-4 { background: #ddaa00; }
        .avatar-color-5 { background: #eebb00; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="sidebar-header">
                    <h3 class="fw-bold mb-0">Q-TRACK</h3>
                    <small class="opacity-75">SPMI Digital</small>
                </div>
                
                <nav class="sidebar-menu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i>Home page
                            </a>
                       <li class="nav-item">
                            <a class="nav-link" href="{{ route('upload-dokumen') }}">
                               <i class="fas fa-upload"></i>Dokumen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog"></i>Pengaturan
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navigation -->
                <nav class="navbar-custom">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center w-100">
                            <div class="search-box me-auto">
                                <i class="fas fa-search text-muted me-2"></i>
                                <input type="text" placeholder="Search...">
                            </div>
                            
                            <div class="user-profile">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar avatar-color-{{ auth()->user()->id % 6 }}">
                                        @php
                                            $name = auth()->user()->name;
                                            $words = explode(' ', $name);
                                            $initials = '';
                                            foreach($words as $word) {
                                                $initials .= strtoupper(substr($word, 0, 1));
                                            }
                                            echo substr($initials, 0, 2);
                                        @endphp
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">
                                            {{ auth()->user()->role ?? 'Administrator' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content Area -->
                <div class="container-fluid mt-4">
                    <!-- Welcome Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="welcome-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h4 class="fw-bold mb-2">LPM Smart Sistem</h4>
                                        <h2 class="fw-bold mb-3">Selamat datang, {{ auth()->user()->name }}</h2>
                                        <p class="mb-0 opacity-90">
                                            Kamu dapat melakukan pemberkasan dengan lebih mudah dan untuk saat ini terdapat 
                                            <span class="fw-bold">6 Program Studi</span> yang terdaftar pada sistem.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="stats-badge d-inline-block">
                                            <i class="fas fa-university me-1"></i> 6 Program Studi Aktif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Studi Section -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="fw-bold mb-4">Program Studi</h4>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- ILMU PENDIDIKAN -->
                        <div class="col-md-6 col-lg-4">
                            <div class="program-card">
                                <div class="program-icon program-1">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h5 class="fw-bold">ILMU PENDIDIKAN</h5>
                                <p class="text-muted mb-3">Program studi bidang pendidikan dan pengajaran</p>
                                <div class="badge bg-primary">Aktif</div>
                            </div>
                        </div>

                        <!-- PENDIDIKAN BAHASA -->
                        <div class="col-md-6 col-lg-4">
                            <div class="program-card">
                                <div class="program-icon program-2">
                                    <i class="fas fa-language"></i>
                                </div>
                                <h5 class="fw-bold">PENDIDIKAN BAHASA</h5>
                                <p class="text-muted mb-3">Program studi linguistik dan pendidikan bahasa</p>
                                <div class="badge bg-primary">Aktif</div>
                            </div>
                        </div>

                        <!-- MATEMATIKA DAN SAINS -->
                        <div class="col-md-6 col-lg-4">
                            <div class="program-card">
                                <div class="program-icon program-3">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <h5 class="fw-bold">MATEMATIKA DAN SAINS</h5>
                                <p class="text-muted mb-3">Program studi matematika dan ilmu pengetahuan</p>
                                <div class="badge bg-primary">Aktif</div>
                            </div>
                        </div>

                        <!-- PROGRAM STUDI KHUSUS -->
                        <div class="col-md-6 col-lg-4">
                            <div class="program-card">
                                <div class="program-icon program-4">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <h5 class="fw-bold">PROGRAM STUDI KHUSUS</h5>
                                <p class="text-muted mb-3">Program studi khusus dengan kurikulum terpadu</p>
                                <div class="badge bg-primary">Aktif</div>
                            </div>
                        </div>

                        <!-- PASCASARJANA -->
                        <div class="col-md-6 col-lg-4">
                            <div class="program-card">
                                <div class="program-icon program-5">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h5 class="fw-bold">PASCASARJANA</h5>
                                <p class="text-muted mb-3">Program studi tingkat magister dan doktoral</p>
                                <div class="badge bg-primary">Aktif</div>
                            </div>
                        </div>

                        <!-- LPM SMART SISTEM -->
                        <div class="col-md-6 col-lg-4">
                            <div class="program-card">
                                <div class="program-icon program-6">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                                <h5 class="fw-bold">LPM SMART SISTEM</h5>
                                <p class="text-muted mb-3">Sistem penjaminan mutu terintegrasi</p>
                                <div class="badge bg-primary">Aktif</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card border-0">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <div class="p-3">
                                                <h3 class="fw-bold text-primary">12</h3>
                                                <p class="text-muted mb-0">Total Standar</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="p-3">
                                                <h3 class="fw-bold text-success">8</h3>
                                                <p class="text-muted mb-0">Audit Selesai</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="p-3">
                                                <h3 class="fw-bold text-warning">24</h3>
                                                <p class="text-muted mb-0">Dokumen Mutu</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="p-3">
                                                <h3 class="fw-bold text-info">6</h3>
                                                <p class="text-muted mb-0">Program Studi</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>