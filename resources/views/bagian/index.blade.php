<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Kerja - APK SPMI</title>
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
            --blue: #1976D2;
            --orange: #F57C00;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 800;
            color: var(--primary) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--dark) !important;
            font-weight: 500;
            margin: 0 10px;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 100px 0 60px;
            margin-bottom: 40px;
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
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.1rem;
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

        .stats-wrapper {
            margin-top: -40px;
            position: relative;
            z-index: 10;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
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
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(139, 94, 60, 0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .section-title {
            margin-bottom: 1.5rem;
        }

        .section-title h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.3rem;
        }

        .section-title p {
            color: #666;
            font-size: 0.95rem;
        }

        .unit-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .unit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px rgba(139, 94, 60, 0.2);
        }

        .unit-card-header {
            padding: 15px 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .unit-icon-wrapper {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 8px 15px rgba(139, 94, 60, 0.2);
        }

        .unit-badge {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .unit-card-body {
            padding: 12px 15px;
            flex-grow: 1;
        }

        .unit-card-body h4 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.3rem;
            font-size: 1.1rem;
        }

        .unit-card-body p {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 0.8rem;
        }

        .unit-stats {
            display: flex;
            gap: 12px;
            margin: 10px 0;
        }

        .unit-stat-item {
            text-align: center;
        }

        .unit-stat-value {
            font-weight: 700;
            color: var(--primary);
            font-size: 1rem;
        }

        .unit-stat-label {
            font-size: 0.65rem;
            color: #999;
        }

        .unit-card-footer {
            padding: 12px 15px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafafa;
            font-size: 0.85rem;
        }

        .btn-detail {
            padding: 6px 15px;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            border: none;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-detail:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 12px rgba(139, 94, 60, 0.3);
        }

        .subunit-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #eee;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .subunit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }

        .subunit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(139, 94, 60, 0.15);
            border-color: var(--primary);
        }

        .subunit-card:hover::before {
            transform: translateY(0);
        }

        .subunit-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .subunit-card h6 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .subunit-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 0.8rem;
        }

        .subunit-staff-count {
            background: #f0f0f0;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            color: #666;
            font-weight: 600;
        }

        .subunit-staff-count i {
            color: var(--primary);
            margin-right: 3px;
        }

        .subunit-badge {
            font-size: 0.65rem;
            padding: 3px 8px;
            border-radius: 50px;
            background: #e8f5e9;
            color: #2e7d32;
            font-weight: 600;
        }

        .subunit-head {
            font-size: 0.75rem;
            color: #666;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .subunit-head i {
            color: var(--primary);
            width: 16px;
        }

        .subunit-desc {
            font-size: 0.7rem;
            color: #999;
            margin-bottom: 0.8rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .subunit-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .subunit-ext {
            font-size: 0.65rem;
            color: #999;
        }

        .btn-subunit-detail {
            padding: 4px 10px;
            border-radius: 50px;
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary-light);
            font-weight: 600;
            font-size: 0.7rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-subunit-detail:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
            display: inline-block;
            font-size: 1.1rem;
        }

        .staff-list {
            list-style: none;
            padding: 0;
        }

        .staff-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 0.3rem;
        }

        .staff-item i {
            width: 30px;
            color: var(--primary);
        }

        .staff-info {
            flex-grow: 1;
        }

        .staff-name {
            font-weight: 600;
            color: var(--dark);
        }

        .staff-position {
            font-size: 0.8rem;
            color: #666;
        }

        .facility-tag {
            background: var(--light);
            color: var(--primary-dark);
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            margin: 0.2rem;
            display: inline-block;
        }

        .program-item {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .program-item h6 {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.2rem;
        }

        .program-item p {
            font-size: 0.8rem;
            color: #666;
            margin: 0;
        }

        .contact-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .contact-item i {
            width: 30px;
            height: 30px;
            background: var(--light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('title', 'Unit Kerja')

    @section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="hero-title">Unit Kerja</h1>
                    <p class="hero-subtitle">Struktur organisasi yang solid dengan SDM profesional untuk mendukung layanan terbaik</p>
                </div>
                <div class="col-lg-4 text-end">
                    <i class="fas fa-sitemap fa-5x opacity-25"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="container stats-wrapper">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="stat-number">6</div>
                    <div class="stat-label">Bagian Utama</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div class="stat-number">12</div>
                    <div class="stat-label">Sub Bagian</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">85+</div>
                    <div class="stat-label">Staff Aktif</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Sertifikasi SDM</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Utama Section -->
    <div class="container mt-4">
        <div class="section-title">
            <h2>Bagian Utama</h2>
            <p>Unit kerja utama yang mendukung operasional dan layanan akademik</p>
        </div>

        <div class="row g-3">
            <!-- Bagian Akademik -->
            <div class="col-lg-4 col-md-6">
                <div class="unit-card">
                    <div class="unit-card-header">
                        <div class="unit-icon-wrapper">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span class="unit-badge">Aktif</span>
                    </div>
                    <div class="unit-card-body">
                        <h4>Bagian Akademik</h4>
                        <p>Mengelola kurikulum, registrasi mahasiswa, penjadwalan, dan nilai</p>
                        <div class="unit-stats">
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">3</div>
                                <div class="unit-stat-label">Sub Bagian</div>
                            </div>
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">18</div>
                                <div class="unit-stat-label">Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="unit-card-footer">
                        <span><i class="fas fa-user-tie text-primary me-1"></i> Dr. Rina Wijaya, M.Pd.</span>
                        <button class="btn-detail" onclick="showUnitDetail('akademik')">Detail</button>
                    </div>
                </div>
            </div>

            <!-- Bagian Keuangan -->
            <div class="col-lg-4 col-md-6">
                <div class="unit-card">
                    <div class="unit-card-header">
                        <div class="unit-icon-wrapper" style="background: linear-gradient(135deg, #F57C00, #E65100);">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <span class="unit-badge">Aktif</span>
                    </div>
                    <div class="unit-card-body">
                        <h4>Bagian Keuangan</h4>
                        <p>Perencanaan anggaran, akuntansi, dan pelaporan keuangan</p>
                        <div class="unit-stats">
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">3</div>
                                <div class="unit-stat-label">Sub Bagian</div>
                            </div>
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">15</div>
                                <div class="unit-stat-label">Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="unit-card-footer">
                        <span><i class="fas fa-user-tie text-primary me-1"></i> H. Dadang Kosasih, S.E.</span>
                        <button class="btn-detail" onclick="showUnitDetail('keuangan')">Detail</button>
                    </div>
                </div>
            </div>

            <!-- Bagian Administrasi Umum -->
            <div class="col-lg-4 col-md-6">
                <div class="unit-card">
                    <div class="unit-card-header">
                        <div class="unit-icon-wrapper" style="background: linear-gradient(135deg, #1976D2, #0D47A1);">
                            <i class="fas fa-archive"></i>
                        </div>
                        <span class="unit-badge">Aktif</span>
                    </div>
                    <div class="unit-card-body">
                        <h4>Bagian Administrasi Umum</h4>
                        <p>Tata usaha, rumah tangga, dan perlengkapan</p>
                        <div class="unit-stats">
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">4</div>
                                <div class="unit-stat-label">Sub Bagian</div>
                            </div>
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">22</div>
                                <div class="unit-stat-label">Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="unit-card-footer">
                        <span><i class="fas fa-user-tie text-primary me-1"></i> Drs. H. Eko Prasetyo</span>
                        <button class="btn-detail" onclick="showUnitDetail('administrasi-umum')">Detail</button>
                    </div>
                </div>
            </div>

            <!-- Bagian Kemahasiswaan -->
            <div class="col-lg-4 col-md-6">
                <div class="unit-card">
                    <div class="unit-card-header">
                        <div class="unit-icon-wrapper" style="background: linear-gradient(135deg, #7B1FA2, #4A148C);">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span class="unit-badge">Aktif</span>
                    </div>
                    <div class="unit-card-body">
                        <h4>Bagian Kemahasiswaan</h4>
                        <p>Minat bakat, organisasi mahasiswa, dan beasiswa</p>
                        <div class="unit-stats">
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">3</div>
                                <div class="unit-stat-label">Sub Bagian</div>
                            </div>
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">14</div>
                                <div class="unit-stat-label">Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="unit-card-footer">
                        <span><i class="fas fa-user-tie text-primary me-1"></i> Dra. Yuniarti, M.Si.</span>
                        <button class="btn-detail" onclick="showUnitDetail('kemahasiswaan')">Detail</button>
                    </div>
                </div>
            </div>

            <!-- Bagian Sumber Daya -->
            <div class="col-lg-4 col-md-6">
                <div class="unit-card">
                    <div class="unit-card-header">
                        <div class="unit-icon-wrapper" style="background: linear-gradient(135deg, #009688, #00695C);">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <span class="unit-badge">Aktif</span>
                    </div>
                    <div class="unit-card-body">
                        <h4>Bagian Sumber Daya</h4>
                        <p>SDM, pengembangan kompetensi, dan karir pegawai</p>
                        <div class="unit-stats">
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">2</div>
                                <div class="unit-stat-label">Sub Bagian</div>
                            </div>
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">10</div>
                                <div class="unit-stat-label">Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="unit-card-footer">
                        <span><i class="fas fa-user-tie text-primary me-1"></i> Dr. H. Ahmad Fauzi</span>
                        <button class="btn-detail" onclick="showUnitDetail('sumber-daya')">Detail</button>
                    </div>
                </div>
            </div>

            <!-- Bagian Kerjasama -->
            <div class="col-lg-4 col-md-6">
                <div class="unit-card">
                    <div class="unit-card-header">
                        <div class="unit-icon-wrapper" style="background: linear-gradient(135deg, #E91E63, #C2185B);">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <span class="unit-badge">Aktif</span>
                    </div>
                    <div class="unit-card-body">
                        <h4>Bagian Kerjasama</h4>
                        <p>Humas, kerjasama dalam dan luar negeri</p>
                        <div class="unit-stats">
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">2</div>
                                <div class="unit-stat-label">Sub Bagian</div>
                            </div>
                            <div class="unit-stat-item">
                                <div class="unit-stat-value">8</div>
                                <div class="unit-stat-label">Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="unit-card-footer">
                        <span><i class="fas fa-user-tie text-primary me-1"></i> Dr. Nina Herlina</span>
                        <button class="btn-detail" onclick="showUnitDetail('kerjasama')">Detail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub Bagian Section -->
    <div class="container mt-4">
        <div class="section-title">
            <h2>Sub Bagian</h2>
            <p>Unit kerja pendukung dengan layanan spesifik dan profesional</p>
        </div>

        <div class="row g-3">
            <!-- Sub Bagian Tata Usaha -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('tata-usaha')">
                    <div class="subunit-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h6>Sub Bagian Tata Usaha</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 6 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Drs. Bambang Susilo
                    </div>
                    <div class="subunit-desc">
                        Mengelola administrasi umum, surat menyurat, dan kearsipan
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1101</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('tata-usaha')">Lihat Detail</span>
                    </div>
                </div>
            </div>

            <!-- Sub Bagian Keuangan -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('keuangan-sub')">
                    <div class="subunit-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h6>Sub Bagian Keuangan</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 5 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Sri Wahyuni, S.E.
                    </div>
                    <div class="subunit-desc">
                        Mengelola akuntansi, pembayaran, dan pelaporan keuangan
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1102</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('keuangan-sub')">Lihat Detail</span>
                    </div>
                </div>
            </div>

            <!-- Sub Bagian Perlengkapan -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('perlengkapan')">
                    <div class="subunit-icon">
                        <i class="fas fa-print"></i>
                    </div>
                    <h6>Sub Bagian Perlengkapan</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 4 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Joko Widodo
                    </div>
                    <div class="subunit-desc">
                        Pengadaan barang, inventaris, dan pemeliharaan fasilitas
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1103</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('perlengkapan')">Lihat Detail</span>
                    </div>
                </div>
            </div>

            <!-- Sub Bagian Kepegawaian -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('kepegawaian')">
                    <div class="subunit-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h6>Sub Bagian Kepegawaian</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 5 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Dra. Hj. Siti Khodijah
                    </div>
                    <div class="subunit-desc">
                        Administrasi kepegawaian, kenaikan pangkat, dan pensiun
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1104</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('kepegawaian')">Lihat Detail</span>
                    </div>
                </div>
            </div>

            <!-- Sub Bagian Akademik -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('akademik-sub')">
                    <div class="subunit-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h6>Sub Bagian Akademik</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 6 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Dra. Siti Aminah
                    </div>
                    <div class="subunit-desc">
                        Penjadwalan perkuliahan, KRS, dan administrasi akademik
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1105</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('akademik-sub')">Lihat Detail</span>
                    </div>
                </div>
            </div>

            <!-- Sub Bagian Kemahasiswaan -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('kemahasiswaan-sub')">
                    <div class="subunit-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h6>Sub Bagian Kemahasiswaan</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 5 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Agus Setiawan, S.Or.
                    </div>
                    <div class="subunit-desc">
                        Minat bakat, UKM, organisasi mahasiswa, dan beasiswa
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1106</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('kemahasiswaan-sub')">Lihat Detail</span>
                    </div>
                </div>
            </div>

            <!-- Sub Bagian Kerjasama -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('kerjasama-sub')">
                    <div class="subunit-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h6>Sub Bagian Kerjasama</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 4 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Indra Gunawan, S.I.Kom.
                    </div>
                    <div class="subunit-desc">
                        Hubungan masyarakat, kerjasama dalam dan luar negeri
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1107</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('kerjasama-sub')">Lihat Detail</span>
                    </div>
                </div>
            </div>

            <!-- Sub Bagian Perencanaan -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="subunit-card" onclick="showSubUnitDetail('perencanaan')">
                    <div class="subunit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h6>Sub Bagian Perencanaan</h6>
                    <div class="subunit-meta">
                        <span class="subunit-staff-count"><i class="fas fa-users"></i> 4 Staff</span>
                        <span class="subunit-badge">Aktif</span>
                    </div>
                    <div class="subunit-head">
                        <i class="fas fa-user-tie"></i> Eko Prasetyo, S.E.
                    </div>
                    <div class="subunit-desc">
                        Perencanaan program, anggaran, dan evaluasi kinerja
                    </div>
                    <div class="subunit-footer">
                        <span class="subunit-ext"><i class="fas fa-phone"></i> Ext. 1108</span>
                        <span class="btn-subunit-detail" onclick="event.stopPropagation(); showSubUnitDetail('perencanaan')">Lihat Detail</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Unit -->
    <div class="modal fade" id="unitDetailModal" tabindex="-1" aria-labelledby="unitDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unitDetailModalLabel">
                        <i class="fas fa-building me-2"></i>Detail Unit Kerja
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="unitModalDetailContent">
                    <!-- Content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Sub Unit -->
    <div class="modal fade" id="subUnitDetailModal" tabindex="-1" aria-labelledby="subUnitDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary));">
                    <h5 class="modal-title" id="subUnitDetailModalLabel">
                        <i class="fas fa-code-branch me-2"></i>Detail Sub Bagian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="subUnitModalDetailContent">
                    <!-- Content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data detail untuk setiap unit kerja
        const unitDetails = {
            'akademik': {
                name: 'Bagian Akademik',
                icon: 'book-open',
                description: 'Mengelola seluruh aspek akademik termasuk kurikulum, registrasi mahasiswa, penjadwalan perkuliahan, dan pengelolaan nilai.',
                kepala: 'Dr. Rina Wijaya, M.Pd.',
                nidn: '197805152005012001',
                email: 'akademik@university.ac.id',
                phone: '021-12345678',
                ext: '101',
                ruangan: 'Gedung Rektorat Lantai 2',
                jamKerja: 'Senin - Jumat, 08.00 - 16.00 WIB',
                subbagian: [
                    {name: 'Sub Bagian Kurikulum', staff: 5, kepala: 'Dra. Siti Aminah', ext: '1105'},
                    {name: 'Sub Bagian Registrasi', staff: 6, kepala: 'Budi Santoso, S.Si.', ext: '1106'},
                    {name: 'Sub Bagian Nilai & Yudisium', staff: 7, kepala: 'Dewi Lestari, M.Kom.', ext: '1107'}
                ],
                staff: [
                    {name: 'Dr. Rina Wijaya, M.Pd.', position: 'Kepala Bagian Akademik'},
                    {name: 'Dra. Siti Aminah', position: 'Kepala Sub Bagian Kurikulum'},
                    {name: 'Budi Santoso, S.Si.', position: 'Kepala Sub Bagian Registrasi'},
                    {name: 'Dewi Lestari, M.Kom.', position: 'Kepala Sub Bagian Nilai'},
                    {name: 'Ahmad Hidayat, S.Pd.', position: 'Staff Kurikulum'},
                    {name: 'Rina Marlina, A.Md.', position: 'Staff Registrasi'},
                    {name: 'Dian Purnama, S.Kom.', position: 'Staff Nilai'},
                    {name: 'Hendra Gunawan', position: 'Staff Administrasi'}
                ],
                facilities: [
                    'Ruang Administrasi', 'Ruang Rapat', 'Ruang Arsip', 'Laboratorium Komputer', 'Ruang Server'
                ],
                programs: [
                    {name: 'Program Percepatan Studi', description: 'Program akselerasi untuk mahasiswa berprestasi'},
                    {name: 'Bimbingan Akademik', description: 'Pendampingan akademik untuk mahasiswa'},
                    {name: 'Workshop Kurikulum', description: 'Pengembangan kurikulum berbasis KKNI'},
                    {name: 'Sistem Informasi Akademik', description: 'Pengembangan SIAKAD terintegrasi'}
                ],
                layanan: [
                    'Pembuatan KRS Online',
                    'Pengajuan Cuti Akademik',
                    'Cetak Transkrip Nilai',
                    'Verifikasi Ijazah',
                    'Konsultasi Akademik'
                ]
            },
            // ... (detail untuk unit lainnya tetap sama)
            'keuangan': {
                name: 'Bagian Keuangan',
                icon: 'money-bill-wave',
                description: 'Mengelola perencanaan anggaran, akuntansi, dan pelaporan keuangan untuk mendukung operasional institusi.',
                kepala: 'H. Dadang Kosasih, S.E.',
                nidn: '197003152005011003',
                email: 'keuangan@university.ac.id',
                phone: '021-12345679',
                ext: '102',
                ruangan: 'Gedung Rektorat Lantai 1',
                jamKerja: 'Senin - Jumat, 08.00 - 16.00 WIB',
                subbagian: [
                    {name: 'Sub Bagian Perencanaan Anggaran', staff: 5, kepala: 'Eko Prasetyo, S.E.', ext: '1108'},
                    {name: 'Sub Bagian Akuntansi', staff: 5, kepala: 'Sri Wahyuni, S.E.', ext: '1102'},
                    {name: 'Sub Bagian Pelaporan', staff: 5, kepala: 'Agus Supriyanto, S.E.', ext: '1109'}
                ],
                staff: [
                    {name: 'H. Dadang Kosasih, S.E.', position: 'Kepala Bagian Keuangan'},
                    {name: 'Eko Prasetyo, S.E.', position: 'Kepala Sub Bagian Perencanaan'},
                    {name: 'Sri Wahyuni, S.E.', position: 'Kepala Sub Bagian Akuntansi'},
                    {name: 'Agus Supriyanto, S.E.', position: 'Kepala Sub Bagian Pelaporan'},
                    {name: 'Dian Purnama, A.Md.', position: 'Staff Akuntansi'},
                    {name: 'Rina Anggraeni, S.E.', position: 'Staff Perencanaan'}
                ],
                facilities: [
                    'Ruang Kasir', 'Ruang Administrasi', 'Ruang Arsip Keuangan', 'Ruang Rapat'
                ],
                programs: [
                    {name: 'Program Anggaran Berbasis Kinerja', description: 'Perencanaan anggaran berbasis output'},
                    {name: 'Sistem Informasi Keuangan', description: 'Pengembangan sistem pelaporan keuangan'},
                    {name: 'Audit Internal', description: 'Program audit keuangan berkala'}
                ],
                layanan: [
                    'Pembayaran UKT',
                    'Pengajuan Anggaran',
                    'Pelaporan Keuangan',
                    'Konsultasi Keuangan'
                ]
            }
        };

        // Data detail untuk sub bagian
        const subUnitDetails = {
            'tata-usaha': {
                name: 'Sub Bagian Tata Usaha',
                icon: 'file-alt',
                parentUnit: 'Bagian Administrasi Umum',
                description: 'Mengelola administrasi umum, surat menyurat, kearsipan, dan layanan administratif lainnya untuk mendukung kelancaran operasional kampus.',
                kepala: 'Drs. Bambang Susilo',
                nidn: '196512152005011004',
                email: 'tatausaha@university.ac.id',
                phone: '021-12345680',
                ext: '1101',
                ruangan: 'Gedung Rektorat Lantai 1, Ruang 101',
                jamKerja: 'Senin - Jumat, 08.00 - 16.00 WIB',
                staff: [
                    {name: 'Drs. Bambang Susilo', position: 'Kepala Sub Bagian Tata Usaha'},
                    {name: 'Siti Mardiyah', position: 'Staff Administrasi'},
                    {name: 'Ahmad Zaini', position: 'Staff Surat Menyurat'},
                    {name: 'Dewi Sartika', position: 'Staff Kearsipan'},
                    {name: 'Rudi Hartono', position: 'Staff Pengadaan'},
                    {name: 'Maya Sari', position: 'Staff Rumah Tangga'}
                ],
                tupoksi: [
                    'Mengelola surat masuk dan keluar',
                    'Mengelola kearsipan dokumen',
                    'Mengadministrasikan kepegawaian',
                    'Mengelola perlengkapan kantor',
                    'Mengkoordinasikan rumah tangga kampus'
                ],
                facilities: [
                    'Ruang Administrasi', 'Ruang Arsip', 'Ruang Rapat', 'Ruang Staff'
                ],
                programs: [
                    {name: 'Program Tertib Arsip', description: 'Digitalisasi dan penataan arsip secara sistematis'},
                    {name: 'Layanan Terpadu', description: 'Pengembangan sistem layanan administrasi satu pintu'},
                    {name: 'Pelatihan Administrasi', description: 'Peningkatan kompetensi staff administrasi'}
                ],
                layanan: [
                    'Penerimaan Surat',
                    'Pengiriman Surat',
                    'Pengarsipan Dokumen',
                    'Fotokopi & Scanning',
                    'Pengadaan ATK'
                ],
                statistik: {
                    suratMasuk: 1250,
                    suratKeluar: 980,
                    dokumenTersimpan: 15000,
                    layananPerBulan: 450
                }
            },
            'keuangan-sub': {
                name: 'Sub Bagian Keuangan',
                icon: 'calculator',
                parentUnit: 'Bagian Keuangan',
                description: 'Mengelola akuntansi, pembayaran, dan pelaporan keuangan untuk memastikan transparansi dan akuntabilitas keuangan.',
                kepala: 'Sri Wahyuni, S.E.',
                nidn: '197003152005011003',
                email: 'keuangan.sub@university.ac.id',
                phone: '021-12345681',
                ext: '1102',
                ruangan: 'Gedung Rektorat Lantai 1, Ruang 102',
                jamKerja: 'Senin - Jumat, 08.00 - 16.00 WIB',
                staff: [
                    {name: 'Sri Wahyuni, S.E.', position: 'Kepala Sub Bagian Keuangan'},
                    {name: 'Dian Purnama, A.Md.', position: 'Staff Akuntansi'},
                    {name: 'Rina Anggraeni, S.E.', position: 'Staff Perencanaan'},
                    {name: 'Agus Supriyanto, S.E.', position: 'Staff Pelaporan'},
                    {name: 'Hendra Gunawan', position: 'Staff Kasir'}
                ],
                tupoksi: [
                    'Mengelola pembayaran UKT mahasiswa',
                    'Menyusun laporan keuangan',
                    'Mengelola anggaran operasional',
                    'Melakukan verifikasi keuangan',
                    'Menyiapkan dokumen pertanggungjawaban'
                ],
                facilities: [
                    'Ruang Kasir', 'Ruang Administrasi', 'Ruang Arsip', 'Ruang Rapat'
                ],
                programs: [
                    {name: 'Sistem Pembayaran Online', description: 'Pengembangan sistem pembayaran terintegrasi'},
                    {name: 'Pelaporan Real-time', description: 'Sistem pelaporan keuangan berbasis web'},
                    {name: 'Audit Keuangan', description: 'Program audit internal berkala'}
                ],
                layanan: [
                    'Pembayaran UKT',
                    'Pengajuan SPPD',
                    'Konsultasi Keuangan',
                    'Cetak Slip Gaji',
                    'Verifikasi Tagihan'
                ],
                statistik: {
                    transaksiPerHari: 150,
                    laporanKeuangan: 45,
                    anggaranTerserap: '75%',
                    kepuasanLayanan: '92%'
                }
            }
        };

        function showUnitDetail(unitId) {
            const detail = unitDetails[unitId];
            if (!detail) return;

            const modalContent = document.getElementById('unitModalDetailContent');
            const modalTitle = document.getElementById('unitDetailModalLabel');
            
            modalTitle.innerHTML = `<i class="fas fa-${detail.icon} me-2"></i>${detail.name}`;
            
            // Build staff list HTML
            let staffListHtml = '';
            detail.staff.slice(0, 6).forEach(person => {
                staffListHtml += `
                    <div class="staff-item">
                        <i class="fas fa-user-circle"></i>
                        <div class="staff-info">
                            <div class="staff-name">${person.name}</div>
                            <div class="staff-position">${person.position}</div>
                        </div>
                    </div>
                `;
            });

            // Build facilities HTML
            let facilitiesHtml = '';
            detail.facilities.forEach(facility => {
                facilitiesHtml += `<span class="facility-tag"><i class="fas fa-check-circle me-1"></i>${facility}</span>`;
            });

            // Build programs HTML
            let programsHtml = '';
            detail.programs.forEach(program => {
                programsHtml += `
                    <div class="program-item">
                        <h6><i class="fas fa-star text-warning me-1"></i>${program.name}</h6>
                        <p>${program.description}</p>
                    </div>
                `;
            });

            // Build layanan HTML
            let layananHtml = '';
            detail.layanan.forEach(layanan => {
                layananHtml += `<span class="facility-tag"><i class="fas fa-check-circle me-1"></i>${layanan}</span>`;
            });

            modalContent.innerHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-section">
                            <p class="text-muted">${detail.description}</p>
                        </div>
                        
                        <div class="contact-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-user-tie"></i>
                                        <div><strong>Kepala Bagian:</strong><br>${detail.kepala}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <div><strong>Email:</strong><br>${detail.email}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <div><strong>Telepon:</strong><br>${detail.phone} (Ext. ${detail.ext})</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div><strong>Ruangan:</strong><br>${detail.ruangan}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-clock"></i>
                                        <div><strong>Jam Kerja:</strong><br>${detail.jamKerja}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-qrcode"></i>
                                        <div><strong>NIDN Kepala:</strong><br>${detail.nidn}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-section">
                            <h6 class="detail-title"><i class="fas fa-code-branch me-2"></i>Sub Bagian</h6>
                            <div class="row">
                                ${detail.subbagian.map(sb => `
                                    <div class="col-md-4 mb-2">
                                        <div class="staff-item" style="background: white; border: 1px solid #eee;">
                                            <i class="fas fa-sitemap" style="color: var(--primary);"></i>
                                            <div class="staff-info">
                                                <div class="staff-name">${sb.name}</div>
                                                <div class="staff-position">
                                                    <span class="badge bg-primary me-1">${sb.staff} Staff</span>
                                                    <span><i class="fas fa-phone"></i> Ext. ${sb.ext}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-users me-2"></i>Staff (${detail.staff.length})</h6>
                                    ${staffListHtml}
                                    ${detail.staff.length > 6 ? '<p class="text-center mt-2"><small>... dan ' + (detail.staff.length - 6) + ' staff lainnya</small></p>' : ''}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-cogs me-2"></i>Layanan</h6>
                                    <div>${layananHtml}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-building me-2"></i>Fasilitas</h6>
                                    <div>${facilitiesHtml}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-calendar-alt me-2"></i>Program Kerja</h6>
                                    ${programsHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('unitDetailModal'));
            modal.show();
        }

        function showSubUnitDetail(subUnitId) {
            const detail = subUnitDetails[subUnitId];
            if (!detail) {
                // Jika data tidak ditemukan, tampilkan pesan default
                alert('Data detail untuk sub bagian ini sedang dalam pengembangan');
                return;
            }

            const modalContent = document.getElementById('subUnitModalDetailContent');
            const modalTitle = document.getElementById('subUnitDetailModalLabel');
            
            modalTitle.innerHTML = `<i class="fas fa-${detail.icon} me-2"></i>${detail.name}`;
            
            // Build staff list HTML
            let staffListHtml = '';
            detail.staff.forEach(person => {
                staffListHtml += `
                    <div class="staff-item">
                        <i class="fas fa-user-circle"></i>
                        <div class="staff-info">
                            <div class="staff-name">${person.name}</div>
                            <div class="staff-position">${person.position}</div>
                        </div>
                    </div>
                `;
            });

            // Build facilities HTML
            let facilitiesHtml = '';
            detail.facilities.forEach(facility => {
                facilitiesHtml += `<span class="facility-tag"><i class="fas fa-check-circle me-1"></i>${facility}</span>`;
            });

            // Build programs HTML
            let programsHtml = '';
            detail.programs.forEach(program => {
                programsHtml += `
                    <div class="program-item">
                        <h6><i class="fas fa-star text-warning me-1"></i>${program.name}</h6>
                        <p>${program.description}</p>
                    </div>
                `;
            });

            // Build tupoksi HTML
            let tupoksiHtml = '';
            detail.tupoksi.forEach(item => {
                tupoksiHtml += `<li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>${item}</li>`;
            });

            // Build layanan HTML
            let layananHtml = '';
            detail.layanan.forEach(layanan => {
                layananHtml += `<span class="facility-tag"><i class="fas fa-check-circle me-1"></i>${layanan}</span>`;
            });

            modalContent.innerHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-section">
                            <span class="badge bg-secondary mb-2"><i class="fas fa-sitemap me-1"></i>${detail.parentUnit}</span>
                            <p class="text-muted">${detail.description}</p>
                        </div>
                        
                        <div class="contact-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-user-tie"></i>
                                        <div><strong>Kepala Sub Bagian:</strong><br>${detail.kepala}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <div><strong>Email:</strong><br>${detail.email}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <div><strong>Telepon:</strong><br>${detail.phone} (Ext. ${detail.ext})</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div><strong>Ruangan:</strong><br>${detail.ruangan}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-clock"></i>
                                        <div><strong>Jam Kerja:</strong><br>${detail.jamKerja}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <i class="fas fa-qrcode"></i>
                                        <div><strong>NIDN:</strong><br>${detail.nidn}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-tasks me-2"></i>Tupoksi</h6>
                                    <ul class="list-group list-group-flush">
                                        ${tupoksiHtml}
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-cogs me-2"></i>Layanan</h6>
                                    <div>${layananHtml}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-users me-2"></i>Staff</h6>
                                    ${staffListHtml}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-building me-2"></i>Fasilitas</h6>
                                    <div>${facilitiesHtml}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-chart-bar me-2"></i>Statistik</h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="staff-item" style="background: white; border: 1px solid #eee;">
                                                <i class="fas fa-envelope"></i>
                                                <div class="staff-info">
                                                    <div class="staff-name">${detail.statistik.suratMasuk}</div>
                                                    <div class="staff-position">Surat Masuk</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="staff-item" style="background: white; border: 1px solid #eee;">
                                                <i class="fas fa-paper-plane"></i>
                                                <div class="staff-info">
                                                    <div class="staff-name">${detail.statistik.suratKeluar}</div>
                                                    <div class="staff-position">Surat Keluar</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="staff-item" style="background: white; border: 1px solid #eee;">
                                                <i class="fas fa-file-alt"></i>
                                                <div class="staff-info">
                                                    <div class="staff-name">${detail.statistik.dokumenTersimpan}</div>
                                                    <div class="staff-position">Dokumen</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="staff-item" style="background: white; border: 1px solid #eee;">
                                                <i class="fas fa-smile"></i>
                                                <div class="staff-info">
                                                    <div class="staff-name">${detail.statistik.kepuasanLayanan}</div>
                                                    <div class="staff-position">Kepuasan</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <h6 class="detail-title"><i class="fas fa-calendar-alt me-2"></i>Program Kerja</h6>
                                    ${programsHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('subUnitDetailModal'));
            modal.show();
        }
    </script>
</body>
</html>