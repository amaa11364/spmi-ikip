<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPT - APK SPMI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #8B5E3C;
            --primary-light: #A67B5B;
            --primary-dark: #6B4A2E;
            --secondary: #C49A6C;
            --accent: #E3B78C;
            --dark: #2C1810;
            --light: #FDF5E6;
            --success: #4CAF50;
            --warning: #FF9800;
            --danger: #F44336;
            --info: #2196F3;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
            min-height: 100vh;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 120px 0 80px;
            margin-bottom: 60px;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            animation: fadeInUp 1s ease 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats Cards */
        .stats-wrapper {
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(139, 94, 60, 0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(139, 94, 60, 0.2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
            font-size: 0.95rem;
        }

        /* Section Title */
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .section-title p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* UPT Cards */
        .upt-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .upt-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 50px rgba(139, 94, 60, 0.3);
        }

        .upt-card-header {
            height: 120px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upt-card-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: white;
            border-radius: 30px 30px 0 0;
        }

        .upt-icon-wrapper {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--primary);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
            transform: translateY(30px);
        }

        .upt-card-body {
            padding: 50px 20px 25px;
            text-align: center;
        }

        .upt-card-body h5 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .upt-card-body p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .badge-custom {
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-active {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .upt-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .upt-meta {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 0.85rem;
        }

        .upt-meta i {
            color: var(--primary);
        }

        .btn-detail {
            padding: 8px 20px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn-detail:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(139, 94, 60, 0.4);
            color: white;
        }

        /* Featured Section */
        .featured-section {
            background: white;
            padding: 60px 0;
            margin: 60px 0;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 20px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(139, 94, 60, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 1.5rem;
        }

        .feature-card h4 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 60px 0;
            border-radius: 30px;
            margin: 60px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.1;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .btn-cta {
            background: white;
            color: var(--primary);
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: 1px solid #eee;
            padding: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 15px;
            margin-bottom: 15px;
        }

        .detail-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .detail-content h6 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .detail-content p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .info-text {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: #2e7d32;
            padding: 15px 20px;
            border-radius: 15px;
            margin-top: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-text i {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('title', 'unit Pelaksana Teknis')

    @section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="hero-title">Unit Pelaksana Teknis</h1>
                    <p class="hero-subtitle">Mendukung pencapaian visi dan misi IKIP Siliwangi melalui layanan prima dan inovatif</p>
                </div>
                <div class="col-lg-4 text-end">
                    <i class="fas fa-building fa-6x opacity-25"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="container stats-wrapper">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-number">8</div>
                    <div class="stat-label">Total Unit Pelaksana Teknis</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">125+</div>
                    <div class="stat-label">Tenaga Ahli & Staf</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Program Layanan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- UPT Grid Section -->
    <div class="container mt-5" id="upt">
        <div class="section-title">
            <h2>Jelajahi UPT Kami</h2>
            <p>Temukan berbagai layanan dan fasilitas yang tersedia di setiap unit pelaksana teknis</p>
        </div>

        <div class="row g-4">
            <!-- UPT Perpustakaan -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT Perpustakaan</h5>
                        <p>Pusat sumber belajar dan informasi ilmiah</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-book-open"></i> 15k+ Koleksi</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('perpustakaan')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- UPT TIK -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-laptop"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT TIK</h5>
                        <p>Teknologi Informasi & Komunikasi</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-wifi"></i> 24/7 Layanan</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('tik')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- UPT Bahasa -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-language"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT Bahasa</h5>
                        <p>Pengembangan dan layanan bahasa</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-globe"></i> 5 Bahasa</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('bahasa')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- UPT Laboratorium -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-flask"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT Laboratorium</h5>
                        <p>Laboratorium terpadu dan penelitian</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-microscope"></i> 12 Lab</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('laboratorium')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- UPT Pengembangan Karir -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT Pengembangan Karir</h5>
                        <p>Career center dan pengembangan profesional</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-handshake"></i> 100+ Mitra</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('karir')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- UPT Publikasi -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT Publikasi</h5>
                        <p>Jurnal dan publikasi ilmiah</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-file-alt"></i> 15 Jurnal</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('publikasi')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- UPT Penjaminan Mutu -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT Penjaminan Mutu</h5>
                        <p>Sistem penjaminan mutu internal</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-clipboard-check"></i> ISO 9001</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('mutu')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- UPT Kerjasama -->
            <div class="col-lg-3 col-md-6">
                <div class="upt-card">
                    <div class="upt-card-header">
                        <div class="upt-icon-wrapper">
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                    <div class="upt-card-body">
                        <h5>UPT Kerjasama</h5>
                        <p>Hubungan masyarakat dan kerjasama</p>
                        <span class="badge-custom badge-active">
                            <i class="fas fa-circle fa-2xs"></i> Aktif
                        </span>
                    </div>
                    <div class="upt-footer">
                        <div class="upt-meta">
                            <span><i class="fas fa-globe"></i> 50+ Mitra</span>
                        </div>
                        <button class="btn-detail" onclick="showDetail('kerjasama')">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Services -->
    <div class="featured-section" id="layanan">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Unggulan</h2>
                <p>Tiga layanan unggulan yang tersedia di seluruh UPT untuk mendukung kegiatan akademik dan non-akademik</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon float-animation">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h4>Digital Library</h4>
                        <p>Akses ke ribuan e-book dan jurnal internasional 24/7 dari mana saja. Tersedia di UPT Perpustakaan dan UPT TIK.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon float-animation" style="animation-delay: 0.5s">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h4>Smart Campus</h4>
                        <p>Sistem terintegrasi untuk memudahkan akses layanan akademik. Dikelola oleh UPT TIK dan UPT Penjaminan Mutu.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon float-animation" style="animation-delay: 1s">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Research Support</h4>
                        <p>Pendukung penelitian dengan laboratorium dan publikasi terakreditasi. Tersedia di UPT Laboratorium dan UPT Publikasi.</p>
                    </div>
                </div>
            </div>
            <div class="info-text">
                <i class="fas fa-info-circle"></i>
                <span>Ketiga layanan unggulan ini merupakan kolaborasi antar UPT untuk memberikan pelayanan terbaik bagi sivitas akademika.</span>
            </div>
        </div>
    </div>

    <!-- Modal Detail UPT -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Detail UPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Content will be filled by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none;">
                        <i class="fas fa-external-link-alt me-2"></i>Kunjungi Website
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data detail untuk setiap UPT
        const uptDetails = {
            perpustakaan: {
                title: 'UPT Perpustakaan',
                icon: 'fa-book',
                details: [
                    { icon: 'fa-book-open', label: 'Koleksi', value: '15.000+ buku dan e-book' },
                    { icon: 'fa-newspaper', label: 'Jurnal', value: '50+ jurnal nasional & internasional' },
                    { icon: 'fa-clock', label: 'Jam Operasional', value: 'Senin - Jumat, 08.00 - 20.00 WIB' },
                    { icon: 'fa-users', label: 'Staf', value: '12 pustakawan profesional' },
                    { icon: 'fa-laptop', label: 'Fasilitas', value: 'Ruang baca, WiFi, OPAC, Digital Library' }
                ],
                description: 'Perpustakaan pusat IKIP Siliwangi menyediakan berbagai koleksi buku, jurnal, dan sumber belajar digital untuk mendukung kegiatan akademik dan penelitian.'
            },
            tik: {
                title: 'UPT TIK',
                icon: 'fa-laptop',
                details: [
                    { icon: 'fa-wifi', label: 'Jaringan', value: 'WiFi 24/7 di seluruh kampus' },
                    { icon: 'fa-server', label: 'Infrastruktur', value: 'Data center, cloud storage' },
                    { icon: 'fa-graduation-cap', label: 'Layanan', value: 'SIAKAD, e-learning, CMS' },
                    { icon: 'fa-headset', label: 'Helpdesk', value: '24/7 support' },
                    { icon: 'fa-shield-alt', label: 'Keamanan', value: 'Sistem keamanan terintegrasi' }
                ],
                description: 'UPT Teknologi Informasi dan Komunikasi mengelola seluruh infrastruktur IT kampus dan menyediakan layanan digital untuk sivitas akademika.'
            },
            bahasa: {
                title: 'UPT Bahasa',
                icon: 'fa-language',
                details: [
                    { icon: 'fa-globe', label: 'Bahasa', value: 'Inggris, Jepang, Mandarin, Arab, Korea' },
                    { icon: 'fa-certificate', label: 'Sertifikasi', value: 'TOEFL, IELTS, TOEIC, JLPT' },
                    { icon: 'fa-chalkboard-teacher', label: 'Program', value: 'Regular class, intensive program' },
                    { icon: 'fa-users', label: 'Pengajar', value: '15 pengajar berpengalaman' },
                    { icon: 'fa-book', label: 'Materi', value: 'Kurikulum standar internasional' }
                ],
                description: 'Pusat pengembangan bahasa menyediakan kursus bahasa asing, layanan penerjemahan, dan tes sertifikasi bahasa.'
            },
            laboratorium: {
                title: 'UPT Laboratorium',
                icon: 'fa-flask',
                details: [
                    { icon: 'fa-microscope', label: 'Lab IPA', value: '4 lab biologi, kimia, fisika' },
                    { icon: 'fa-laptop', label: 'Lab Komputer', value: '3 lab dengan 150 unit PC' },
                    { icon: 'fa-robot', label: 'Lab Robotik', value: 'Peralatan robotik modern' },
                    { icon: 'fa-flask', label: 'Lab Bahasa', value: '2 lab bahasa multimedia' },
                    { icon: 'fa-chart-line', label: 'Lab Psikologi', value: 'Fasilitas riset psikologi' }
                ],
                description: 'Laboratorium terpadu untuk mendukung praktikum dan penelitian mahasiswa dan dosen dengan peralatan modern.'
            },
            karir: {
                title: 'UPT Pengembangan Karir',
                icon: 'fa-briefcase',
                details: [
                    { icon: 'fa-handshake', label: 'Mitra', value: '100+ perusahaan mitra' },
                    { icon: 'fa-briefcase', label: 'Job Fair', value: '2x job fair per tahun' },
                    { icon: 'fa-file-alt', label: 'Pelatihan', value: 'CV writing, interview skills' },
                    { icon: 'fa-chart-line', label: 'Konseling', value: 'Konseling karir personal' },
                    { icon: 'fa-building', label: 'Magang', value: 'Program magang bersertifikat' }
                ],
                description: 'Career center yang memfasilitasi pengembangan karir mahasiswa dan alumni melalui pelatihan, job fair, dan networking.'
            },
            publikasi: {
                title: 'UPT Publikasi',
                icon: 'fa-newspaper',
                details: [
                    { icon: 'fa-file-alt', label: 'Jurnal', value: '15 jurnal terakreditasi' },
                    { icon: 'fa-book', label: 'Prosiding', value: 'Publikasi seminar nasional' },
                    { icon: 'fa-edit', label: 'Proofreading', value: 'Layanan edit naskah' },
                    { icon: 'fa-qrcode', label: 'DOI', value: 'ISSN/ISBN, DOI' },
                    { icon: 'fa-search', label: 'Indeksasi', value: 'Sinta, Google Scholar, Garuda' }
                ],
                description: 'Pengelolaan jurnal ilmiah dan publikasi akademik dengan sistem open journal system (OJS) dan layanan proofreading.'
            },
            mutu: {
                title: 'UPT Penjaminan Mutu',
                icon: 'fa-check-circle',
                details: [
                    { icon: 'fa-clipboard-check', label: 'Sertifikasi', value: 'ISO 9001:2015' },
                    { icon: 'fa-chart-bar', label: 'Audit', value: 'Audit internal rutin' },
                    { icon: 'fa-file-signature', label: 'Akreditasi', value: 'Akreditasi program studi' },
                    { icon: 'fa-tasks', label: 'SPMI', value: 'Siklus PPEPP' },
                    { icon: 'fa-chart-pie', label: 'Tracer Study', value: 'Survei kepuasan' }
                ],
                description: 'Unit yang bertanggung jawab dalam implementasi Sistem Penjaminan Mutu Internal (SPMI) di seluruh unit kerja.'
            },
            kerjasama: {
                title: 'UPT Kerjasama',
                icon: 'fa-handshake',
                details: [
                    { icon: 'fa-globe', label: 'Internasional', value: '20+ mitra luar negeri' },
                    { icon: 'fa-building', label: 'Nasional', value: '50+ mitra nasional' },
                    { icon: 'fa-exchange-alt', label: 'Program', value: 'Pertukaran mahasiswa, riset' },
                    { icon: 'fa-hand-holding-heart', label: 'CSR', value: 'Program pengabdian' },
                    { icon: 'fa-handshake', label: 'MoU', value: '100+ MoU aktif' }
                ],
                description: 'Mengelola kerjasama dengan berbagai institusi pendidikan, industri, dan pemerintah baik dalam maupun luar negeri.'
            }
        };

        function showDetail(uptKey) {
            const upt = uptDetails[uptKey];
            if (!upt) return;

            // Set modal title
            document.getElementById('modalTitle').innerHTML = `<i class="fas ${upt.icon} me-2"></i>${upt.title}`;

            // Build modal body content
            let detailsHtml = `
                <p style="color: #666; margin-bottom: 2rem;">${upt.description}</p>
                <h6 style="color: var(--primary); font-weight: 700; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle me-2"></i>Informasi Detail
                </h6>
            `;

            upt.details.forEach(detail => {
                detailsHtml += `
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas ${detail.icon}"></i>
                        </div>
                        <div class="detail-content">
                            <h6>${detail.label}</h6>
                            <p>${detail.value}</p>
                        </div>
                    </div>
                `;
            });

            // Add contact information
            detailsHtml += `
                <div style="margin-top: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: 15px;">
                    <h6 style="color: var(--primary); font-weight: 700; margin-bottom: 1rem;">
                        <i class="fas fa-address-card me-2"></i>Kontak & Lokasi
                    </h6>
                    <div class="row">
                        <div class="col-6">
                            <p><i class="fas fa-map-marker-alt me-2" style="color: var(--primary);"></i>Gedung Rektorat Lantai 2</p>
                            <p><i class="fas fa-phone me-2" style="color: var(--primary);"></i>(022) 1234-5678</p>
                        </div>
                        <div class="col-6">
                            <p><i class="fas fa-envelope me-2" style="color: var(--primary);"></i>${uptKey}@ikipsiliwangi.ac.id</p>
                            <p><i class="fas fa-globe me-2" style="color: var(--primary);"></i>upt.ikipsiliwangi.ac.id</p>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('modalBody').innerHTML = detailsHtml;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }

        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>