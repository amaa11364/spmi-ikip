
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q-TRACK - SPMI Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-brown: #996600;
            --secondary-brown: #b37400;
            --accent-brown: #cc9900;
            --dark-brown: #7a5200;
            --light-brown: #fff9e6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-brown) !important;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            color: #374151 !important;
            margin: 0 10px;
        }
        
        .nav-link:hover {
            color: var(--primary-brown) !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown));
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-brown), var(--dark-brown));
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(153, 102, 0, 0.3);
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
            font-size: 2rem ;
            color: white;
        }
        
        .program-icon-sm {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0 auto 10px;
        }
        
        /* Update program colors to brown theme */
        .program-1 { background: linear-gradient(135deg, #996600 0%, #b37400 100%); }
        .program-2 { background: linear-gradient(135deg, #aa7700 0%, #cc8800 100%); }
        .program-3 { background: linear-gradient(135deg, #bb8800 0%, #dd9900 100%); }
        .program-4 { background: linear-gradient(135deg, #cc9900 0%, #eeaa00 100%); }
        .program-5 { background: linear-gradient(135deg, #ddaa00 0%, #ffbb00 100%); }
        .program-6 { background: linear-gradient(135deg, #eebb00 0%, #ffcc00 100%); color: #333 !important; }
        
        .section-title {
            color: var(--dark-brown);
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .about-section {
            background: #f8fafc;
            padding: 100px 0;
        }
        
        .stats-badge {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        
        /* Update text colors in stats section */
        .text-primary { color: var(--primary-brown) !important; }
        .text-success { color: #996600 !important; }
        .text-warning { color: #b37400 !important; }
        .text-info { color: #cc9900 !important; }
        
        footer {
            background: #7a5200;
            color: white;
        }
        
        /* Update icon colors */
        .fa-3x.text-primary { color: var(--primary-brown) !important; }
        .fa-2x.text-primary { color: var(--primary-brown) !important; }

        /* Search Section Styles */
        .search-section {
            background: #f8f9fa;
            padding: 80px 0;
        }
        
        .search-card {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        
        .document-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }
        
        .document-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .login-prompt {
            background: linear-gradient(135deg, var(--light-brown) 0%, #fff5e6 100%);
            border: 2px dashed var(--primary-brown);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('title', 'Home')

    @section('content')
    <!-- Main Content -->
    <main style="padding-top: 76px;">
        <!-- Hero Section -->
        <section id="home" class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 position-relative">
                        <h1 class="display-4 fw-bold mb-3">SPMI</h1>
                        <h3 class="h4 mb-4 opacity-90">SPMI Digital</h3>
                        <h2 class="h3 mb-4 fw-semibold opacity-90">
                            TRANSFORMASI DIGITAL SPMI PERGURUAN TINGGI
                        </h2>
                        <p class="lead mb-4 opacity-90 fw-medium">
                            "Kelola Mutu Pendidikan Lebih Efisien & Efektif"
                        </p>
                            <a href="#about" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                            </a>
                              <a href="#search" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-search me-2"></i>Cari Dokumen
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center position-relative">
                        <div class="hero-visual">
                            <i class="fas fa-chart-network fa-10x text-light opacity-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search Section -->
        <section id="search" class="search-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="search-card">
                            <div class="text-center mb-5">
                                <h2 class="section-title display-5 fw-bold mb-3">Cari Dokumen SPMI</h2>
                                <p class="lead text-muted">Temukan dokumen-dokumen SPMI yang tersedia secara publik</p>
                            </div>
                            
                            <!-- Search Form -->
                            <form action="{{ route('public.search') }}" method="GET" class="mb-5">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" name="q" 
                                           placeholder="Cari dokumen berdasarkan nama, deskripsi, atau unit kerja..." 
                                           value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search me-2"></i>Cari
                                    </button>
                                </div>
                                <div class="form-text text-muted mt-2">
                                    Gunakan kata kunci spesifik untuk hasil pencarian yang lebih akurat
                                </div>
                            </form>

                            <!-- Search Results -->
                           <!-- Di dalam section search, bagian search results -->
@if(request()->has('q'))
    <div class="search-results">
        <h4 class="mb-4">
            <i class="fas fa-search me-2"></i>
            Hasil Pencarian untuk "{{ request('q') }}"
            <span class="badge bg-primary ms-2">{{ $publicDokumens->count() }} dokumen ditemukan</span>
        </h4>

        @if($publicDokumens->count() > 0)
            <div class="row">
                @foreach($publicDokumens as $dokumen)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="document-card text-center h-100">
                            <div class="document-icon text-primary">
                                <i class="{{ $dokumen->file_icon }}"></i>
                            </div>
                            <h6 class="fw-bold mb-2">{{ $dokumen->nama_dokumen }}</h6>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-folder me-1"></i>
                                {{ $dokumen->unitKerja->nama }}
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-file me-1"></i>
                                {{ $dokumen->file_size_formatted }}
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                @if($dokumen->is_pdf)
                                    <a href="{{ route('public.dokumen.preview', $dokumen->id) }}" 
                                       class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="fas fa-eye me-1"></i>Preview
                                    </a>
                                @endif
                                <a href="{{ route('public.dokumen.download', $dokumen->id) }}" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada dokumen yang ditemukan</h5>
                <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
            </div>
        @endif
    </div>
@else
    <!-- Tampilkan pesan default ketika belum mencari -->
    <div class="text-center py-5">
        <i class="fas fa-search fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Cari Dokumen SPMI</h5>
        <p class="text-muted">Gunakan form di atas untuk mencari dokumen SPMI yang tersedia untuk umum</p>
    </div>
@endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="section-title display-5 fw-bold">Tentang Aplikasi SPMI</h2>
                        <p class="lead text-dark mb-4">
                            Aplikasi SPMI adalah solusi digital inovatif yang dirancang khusus 
                            untuk membantu perguruan tinggi dan institusi pendidikan dalam 
                            mengelola Sistem Penjaminan Mutu Internal secara efektif dan efisien.
                        </p>
                        <p class="text-dark mb-4 fs-5">
                            Transformasi proses manual menjadi sistem digital yang terintegrasi.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="#features" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-eye me-2"></i>Lihat Fitur
                            </a>
                            <a href="#search" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Cari Dokumen
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="about-visual mt-5 mt-lg-0">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="program-card p-4 text-center">
                                        <i class="fas fa-bolt fa-3x text-primary mb-3"></i>
                                        <h5 class="fw-semibold">Efisien</h5>
                                        <p class="text-muted mb-0">Proses lebih cepat</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="program-card p-4 text-center">
                                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                                        <h5 class="fw-semibold">Akurat</h5>
                                        <p class="text-muted mb-0">Data real-time</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="program-card p-4 text-center">
                                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                        <h5 class="fw-semibold">Aman</h5>
                                        <p class="text-muted mb-0">Data terproteksi</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="program-card p-4 text-center">
                                        <i class="fas fa-sync fa-3x text-primary mb-3"></i>
                                        <h5 class="fw-semibold">Terintegrasi</h5>
                                        <p class="text-muted mb-0">Sistem menyeluruh</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        

    

        <!-- Features Section -->
        <section id="features" class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title display-5 fw-bold">Fitur Unggulan SPMI</h2>
                    <p class="lead text-muted">Solusi lengkap untuk manajemen mutu pendidikan</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="program-card p-4 h-100 text-center">
                            <div class="program-icon program-1 mx-auto mb-3">
                                <i class="fas fa-tasks fa-2x"></i>
                            </div>
                            <h4 class="fw-semibold mb-3">Manajemen Standar</h4>
                            <p class="text-muted">
                                Kelola 12 standar mutu dengan sistem terstruktur untuk penjaminan kualitas pendidikan
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="program-card p-4 h-100 text-center">
                            <div class="program-icon program-2 mx-auto mb-3">
                                <i class="fas fa-clipboard-check fa-2x"></i>
                            </div>
                            <h4 class="fw-semibold mb-3">Audit Digital</h4>
                            <p class="text-muted">
                                Lakukan 8 audit internal dengan tools lengkap dan pelaporan otomatis
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="program-card p-4 h-100 text-center">
                            <div class="program-icon program-3 mx-auto mb-3">
                                <i class="fas fa-chart-pie fa-2x"></i>
                            </div>
                            <h4 class="fw-semibold mb-3">Analisis Data</h4>
                            <p class="text-muted">
                                Dashboard analitik untuk monitoring 6 program studi secara real-time
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mt-2">
                    <div class="col-md-4">
                        <div class="program-card p-4 h-100 text-center">
                            <div class="program-icon program-4 mx-auto mb-3">
                                <i class="fas fa-file-contract fa-2x"></i>
                            </div>
                            <h4 class="fw-semibold mb-3">Dokumen Mutu</h4>
                            <p class="text-muted">
                                Kelola 24 dokumen mutu secara terpusat dengan version control yang aman
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="program-card p-4 h-100 text-center">
                            <div class="program-icon program-5 mx-auto mb-3">
                                <i class="fas fa-university fa-2x"></i>
                            </div>
                            <h4 class="fw-semibold mb-3">Program Studi</h4>
                            <p class="text-muted">
                                Monitor 6 program studi termasuk Pascasarjana dan pendidikan khusus
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="program-card p-4 h-100 text-center">
                            <div class="program-icon program-6 mx-auto mb-3">
                                <i class="fas fa-laptop-code fa-2x"></i>
                            </div>
                            <h4 class="fw-semibold mb-3">LPM Smart Sistem</h4>
                            <p class="text-muted">
                                Sistem terintegrasi untuk Lembaga Penjaminan Mutu yang efisien
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Program Studi Preview Section -->
        <section id="programs" class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title display-5 fw-bold">Program Studi Tersedia</h2>
                    <p class="lead text-muted">Kelola berbagai program studi dalam satu sistem terpadu</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-2 col-6">
                        <div class="text-center">
                            <div class="program-icon-sm program-1">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <small class="fw-semibold">Ilmu Pendidikan</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="text-center">
                            <div class="program-icon-sm program-2">
                                <i class="fas fa-language"></i>
                            </div>
                            <small class="fw-semibold">Pendidikan Bahasa</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="text-center">
                            <div class="program-icon-sm program-3">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <small class="fw-semibold">Matematika & Sains</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="text-center">
                            <div class="program-icon-sm program-4">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <small class="fw-semibold">Program Khusus</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="text-center">
                            <div class="program-icon-sm program-5">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <small class="fw-semibold">Pascasarjana</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="text-center">
                            <div class="program-icon-sm program-6">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <small class="fw-semibold">LPM Smart Sistem</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Preview Section -->
        <section class="py-5">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-3 mb-4">
                        <div class="p-3">
                            <h3 class="fw-bold text-primary">12</h3>
                            <p class="text-muted mb-0">Standar Mutu</p>
                            <small class="text-primary">Terintegrasi</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="p-3">
                            <h3 class="fw-bold text-success">8</h3>
                            <p class="text-muted mb-0">Audit Internal</p>
                            <small class="text-success">Terselesaikan</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="p-3">
                            <h3 class="fw-bold text-warning">24</h3>
                            <p class="text-muted mb-0">Dokumen</p>
                            <small class="text-warning">Terkelola</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="p-3">
                            <h3 class="fw-bold text-info">6</h3>
                            <p class="text-muted mb-0">Program Studi</p>
                            <small class="text-info">Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-5" style="background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%); color: white;">
            <div class="container text-center">
                <h2 class="display-6 fw-bold mb-4">Siap Transformasi Digital SPMI?</h2>
                <p class="lead mb-4 opacity-90">
                    Bergabung dengan institusi pendidikan yang telah mempercayai SPMI 
                    untuk transformasi digital sistem penjaminan mutu mereka.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="#contact" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title display-5 fw-bold">Hubungi Kami</h2>
                    <p class="lead text-muted">Butuh informasi lebih lanjut? Tim kami siap membantu</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="program-card p-5">
                            <div class="row text-center">
                                <div class="col-md-4 mb-4">
                                    <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                    <h5>Email</h5>
                                    <p class="text-muted">info@qtrack-spmi.ac.id</p>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                    <h5>Telepon</h5>
                                    <p class="text-muted">+62 21 1234 5678</p>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                                    <h5>Jam Operasional</h5>
                                    <p class="text-muted">Senin - Jumat, 08:00 - 17:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
