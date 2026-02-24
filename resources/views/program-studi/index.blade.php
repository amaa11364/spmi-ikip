<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Studi - APK SPMI</title>
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
            --purple: #9C27B0;
            --teal: #009688;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
            min-height: 100vh;
        }
        
        /* Navbar */
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

        /* Category Badge */
        .category-badge {
            display: inline-block;
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .badge-s1 {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .badge-profesi {
            background: linear-gradient(135deg, var(--purple), #7B1FA2);
        }

        .badge-s2 {
            background: linear-gradient(135deg, var(--teal), #00695C);
        }

        /* Program Studi Cards */
        .prodi-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            cursor: pointer;
        }

        .prodi-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 50px rgba(139, 94, 60, 0.3);
        }

        .prodi-card-header {
            height: 100px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .prodi-card-header.s1 {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .prodi-card-header.profesi {
            background: linear-gradient(135deg, var(--purple), #7B1FA2);
        }

        .prodi-card-header.s2 {
            background: linear-gradient(135deg, var(--teal), #00695C);
        }

        .prodi-card-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: white;
            border-radius: 30px 30px 0 0;
        }

        .prodi-icon-wrapper {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
            transform: translateY(30px);
        }

        .prodi-icon-wrapper.s1 {
            color: var(--primary);
        }

        .prodi-icon-wrapper.profesi {
            color: var(--purple);
        }

        .prodi-icon-wrapper.s2 {
            color: var(--teal);
        }

        .prodi-card-body {
            padding: 45px 20px 20px;
            text-align: center;
        }

        .prodi-card-body h5 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            min-height: 50px;
        }

        .akreditasi-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 10px 0;
        }

        .akreditasi-unggul {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .akreditasi-baik {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .prodi-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .prodi-meta {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 0.8rem;
        }

        .prodi-meta i {
            color: var(--primary);
        }

        .btn-detail {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(139, 94, 60, 0.4);
        }

        /* Quick Facts */
        .quick-facts {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin: 3rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .fact-item {
            text-align: center;
        }

        .fact-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .fact-label {
            color: #666;
            font-weight: 500;
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

        /* Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('title', 'Program Studi')

    @section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="hero-title">Program Studi</h1>
                    <p class="hero-subtitle">Program studi terakreditasi dengan kurikulum berbasis kompetensi dan kebutuhan industri</p>
                </div>
                <div class="col-lg-4 text-end">
                    <i class="fas fa-graduation-cap fa-6x opacity-25"></i>
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
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="stat-number">14</div>
                    <div class="stat-label">Total Program Studi</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-number">10</div>
                    <div class="stat-label">Akreditasi Unggul</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">2500+</div>
                    <div class="stat-label">Mahasiswa Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Facts -->
    <div class="container">
        <div class="quick-facts">
            <div class="row">
                <div class="col-md-4">
                    <div class="fact-item">
                        <div class="fact-number">7</div>
                        <div class="fact-label">Program Sarjana (S1)</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fact-item">
                        <div class="fact-number">1</div>
                        <div class="fact-label">Program Profesi</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fact-item">
                        <div class="fact-number">5</div>
                        <div class="fact-label">Program Magister (S2)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROGRAM STUDI PRASARJANA (S1) -->
    <div class="container mt-5">
        <div class="text-center">
            <div class="category-badge badge-s1">
                <i class="fas fa-graduation-cap me-2"></i>PROGRAM STUDI PRASARJANA (S1)
            </div>
        </div>

        <div class="row g-4">
            <!-- S1 Bimbingan dan Konseling -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s1">
                        <div class="prodi-icon-wrapper s1">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>S1 Bimbingan dan Konseling</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 240 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S1 Pendidikan Masyarakat -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s1">
                        <div class="prodi-icon-wrapper s1">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>S1 Pendidikan Masyarakat</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 185 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S1 PGSD -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s1">
                        <div class="prodi-icon-wrapper s1">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>S1 PGSD</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 420 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S1 PG-PAUD -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s1">
                        <div class="prodi-icon-wrapper s1">
                            <i class="fas fa-child"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>S1 PG-PAUD</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 210 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S1 Pendidikan Bahasa dan Sastra Indonesia -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s1">
                        <div class="prodi-icon-wrapper s1">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>S1 Pendidikan Bahasa dan Sastra Indonesia</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 195 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S1 Pendidikan Bahasa Inggris -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s1">
                        <div class="prodi-icon-wrapper s1">
                            <i class="fas fa-language"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>S1 Pendidikan Bahasa Inggris</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 230 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S1 Pendidikan Matematika -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s1">
                        <div class="prodi-icon-wrapper s1">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>S1 Pendidikan Matematika</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 175 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROGRAM STUDI PROFESI -->
    <div class="container mt-5">
        <div class="text-center">
            <div class="category-badge badge-profesi">
                <i class="fas fa-certificate me-2"></i>PROGRAM STUDI PROFESI
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Pendidikan Profesi Guru -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header profesi">
                        <div class="prodi-icon-wrapper profesi">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>Pendidikan Profesi Guru</h5>
                        <span class="akreditasi-badge akreditasi-unggul">
                            <i class="fas fa-certificate"></i> Unggul
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 320 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROGRAM STUDI PASCASARJANA (S2) -->
    <div class="container mt-5">
        <div class="text-center">
            <div class="category-badge badge-s2">
                <i class="fas fa-user-graduate me-2"></i>PROGRAM STUDI PASCASARJANA (S2)
            </div>
        </div>

        <div class="row g-4">
            <!-- S2 Pendidikan Masyarakat -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s2">
                        <div class="prodi-icon-wrapper s2">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>Magister S2 Pendidikan Masyarakat</h5>
                        <span class="akreditasi-badge akreditasi-baik">
                            <i class="fas fa-certificate"></i> Baik Sekali
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 65 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S2 Pendidikan Matematika -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s2">
                        <div class="prodi-icon-wrapper s2">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>Magister S2 Pendidikan Matematika</h5>
                        <span class="akreditasi-badge akreditasi-baik">
                            <i class="fas fa-certificate"></i> Baik Sekali
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 45 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S2 Pendidikan Bahasa Indonesia -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s2">
                        <div class="prodi-icon-wrapper s2">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>Magister S2 Pendidikan Bahasa Indonesia</h5>
                        <span class="akreditasi-badge akreditasi-baik">
                            <i class="fas fa-certificate"></i> Baik Sekali
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 38 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S2 Pendidikan Dasar -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s2">
                        <div class="prodi-icon-wrapper s2">
                            <i class="fas fa-school"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>Magister S2 Pendidikan Dasar</h5>
                        <span class="akreditasi-badge akreditasi-baik">
                            <i class="fas fa-certificate"></i> Baik Sekali
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 52 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S2 Pendidikan Bahasa Inggris -->
            <div class="col-lg-3 col-md-6">
                <div class="prodi-card">
                    <div class="prodi-card-header s2">
                        <div class="prodi-icon-wrapper s2">
                            <i class="fas fa-language"></i>
                        </div>
                    </div>
                    <div class="prodi-card-body">
                        <h5>Magister S2 Pendidikan Bahasa Inggris</h5>
                        <span class="akreditasi-badge akreditasi-baik">
                            <i class="fas fa-certificate"></i> Baik Sekali
                        </span>
                    </div>
                    <div class="prodi-footer">
                        <div class="prodi-meta">
                            <span><i class="fas fa-users"></i> 42 Mhs</span>
                        </div>
                        <button class="btn-detail">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="container">
        <div class="cta-section text-center">
            <div class="container">
                <h2 class="cta-title">Tertarik dengan Program Studi Kami?</h2>
                <p class="mb-4">Dapatkan informasi lengkap tentang pendaftaran, beasiswa, dan kurikulum</p>
                <button class="btn btn-cta">Info Pendaftaran</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>