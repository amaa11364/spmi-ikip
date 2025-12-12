@extends('layouts.landing')

@section('title', 'Home')

@section('content')
<!-- Main Content -->
<!-- Hero Section -->
<section id="home" class="hero-section">
    <div class="container">
        <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 position-relative text-center text-lg-start">
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
                    </div>
                <div class="col-lg-6 col-md-12 text-center position-relative d-none d-lg-block">
                    <div class="hero-visual">
                        <i class="fas fa-chart-network fa-10x text-light opacity-20"></i>
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

        <div class="container">
        <div class="row">
            <!-- Berita Section (Left Column) -->
            <div class="col-lg-8">
                <div class="berita-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-newspaper"></i>Berita Terbaru
                        </h2>
                        <p class="text-muted mb-0">Informasi dan kegiatan terbaru dari SPMI</p>
                    </div>
                    
                    @if($beritas->count() > 0)
                        <div class="row g-4">
                            @foreach($beritas as $berita)
                            <div class="col-md-6">
                                <a href="{{ route('berita.show', $berita->slug) }}" class="text-decoration-none">
                                    <div class="berita-card">
                                        <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="berita-image">
                                        <div class="berita-content">
                                            <h3 class="berita-title">{{ Str::limit($berita->judul, 60) }}</h3>
                                            <p class="berita-excerpt">{{ $berita->excerpt }}</p>
                                            <div class="berita-meta">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>{{ $berita->created_at->format('d M Y') }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-eye"></i>
                                                <span>{{ $berita->views }} views</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="view-more">
                            <a href="{{ route('berita.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-list me-2"></i>Lihat Semua Berita
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada berita</h5>
                            <p class="text-muted">Berita akan ditampilkan di sini</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Jadwal Sidebar (Right Column) -->
            <div class="col-lg-4">
                <div class="jadwal-sidebar">
                    <div class="jadwal-header">
                        <h2 class="section-title mb-0">
                            <i class="fas fa-calendar-alt"></i>Jadwal Kegiatan
                        </h2>
                        <p class="text-muted mb-0 mt-1">Jadwal mendatang</p>
                    </div>
                    
                    @if($jadwals->count() > 0)
                        <div class="jadwal-list">
                            @foreach($jadwals as $jadwal)
                            <div class="jadwal-item">
                                <div class="jadwal-date">
                                    <div class="jadwal-day">{{ $jadwal->hari }}</div>
                                    <div class="jadwal-month">{{ $jadwal->bulanSingkat }}</div>
                                </div>
                                <div class="jadwal-info">
                                    <div class="jadwal-title">{{ $jadwal->kegiatan }}</div>
                                    <div class="jadwal-details">
                                        @if($jadwal->waktu)
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $jadwal->waktu->format('H:i') }}</span>
                                            <span class="mx-1">•</span>
                                        @endif
                                        @if($jadwal->tempat)
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $jadwal->tempat }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="jadwal-empty">
                            <i class="fas fa-calendar-times"></i>
                            <h5 class="text-muted">Tidak ada jadwal</h5>
                            <p class="text-muted">Jadwal akan ditampilkan di sini</p>
                        </div>
                    @endif
                    
                    @auth
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-cog me-1"></i>Kelola Jadwal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Publik Section -->
    <section class="dokumen-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-3">
                        <i class="fas fa-globe me-2 text-warning"></i>Dokumen Publik SPMI
                    </h3>
                    <p class="text-muted mb-4">
                        Akses dokumen SPMI yang tersedia untuk umum. Lihat berbagai dokumen, 
                        laporan, dan informasi sistem penjaminan mutu internal kami.
                    </p>
                    <a href="{{ route('dokumen-publik.index') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-external-link-alt me-2"></i>Lihat Dokumen Publik
                    </a>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-folder-open fa-6x text-muted opacity-50"></i>
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
                <div class="col-lg-4 col-md-6">
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
                <div class="col-lg-4 col-md-6">
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
                <div class="col-lg-4 col-md-6">
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
                <div class="col-lg-4 col-md-6">
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
                <div class="col-lg-4 col-md-6">
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
                <div class="col-lg-4 col-md-6">
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
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="text-center">
                        <div class="program-icon-sm program-1">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <small class="fw-semibold">Ilmu Pendidikan</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="text-center">
                        <div class="program-icon-sm program-2">
                            <i class="fas fa-language"></i>
                        </div>
                        <small class="fw-semibold">Pendidikan Bahasa</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="text-center">
                        <div class="program-icon-sm program-3">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <small class="fw-semibold">Matematika & Sains</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="text-center">
                        <div class="program-icon-sm program-4">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <small class="fw-semibold">Program Khusus</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="text-center">
                        <div class="program-icon-sm program-5">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <small class="fw-semibold">Pascasarjana</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
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
                <div class="col-md-3 col-6 mb-4">
                    <div class="p-3">
                        <h3 class="fw-bold text-primary">12</h3>
                        <p class="text-muted mb-0">Standar Mutu</p>
                        <small class="text-primary">Terintegrasi</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="p-3">
                        <h3 class="fw-bold text-success">8</h3>
                        <p class="text-muted mb-0">Audit Internal</p>
                        <small class="text-success">Terselesaikan</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="p-3">
                        <h3 class="fw-bold text-warning">24</h3>
                        <p class="text-muted mb-0">Dokumen</p>
                        <small class="text-warning">Terkelola</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
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
@endsection
