<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bagian - APK SPMI</title>
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
            background-color: #f8f9fa;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            padding: 100px 0 50px;
            margin-bottom: 40px;
        }
        
        .bagian-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .bagian-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .bagian-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto 1rem;
            background: var(--primary-brown);
        }
        
        .btn-primary {
            background: var(--primary-brown);
            border: none;
            padding: 6px 15px;
            font-weight: 500;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .btn-primary:hover {
            background: var(--dark-brown);
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            border-left: 4px solid var(--primary-brown);
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('title', 'Home')

    @section('content')

    <!-- Main Content -->
    <main style="padding-top: 76px;">
        <!-- Page Header -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="display-5 fw-bold mb-3">Bagian</h1>
                        <p class="lead mb-0">Struktur organisasi dan divisi di lingkungan IKIP Siliwangi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="container mb-5">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold text-primary mb-0">6</h3>
                                <p class="text-muted mb-0">Total Bagian</p>
                            </div>
                            <i class="fas fa-sitemap fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold text-success mb-0">25+</h3>
                                <p class="text-muted mb-0">Staff Aktif</p>
                            </div>
                            <i class="fas fa-users fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold text-info mb-0">5</h3>
                                <p class="text-muted mb-0">Divisi</p>
                            </div>
                            <i class="fas fa-network-wired fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold text-warning mb-0">100%</h3>
                                <p class="text-muted mb-0">Aktif</p>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Cards Section -->
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="fw-bold">Daftar Bagian</h3>
                </div>
            </div>

            <div class="row g-4">
                <!-- Bagian 1 -->
              <div class="col-6 col-md-6 col-lg-4">
                    <div class="bagian-card">
                        <div class="card-body text-center">
                            <div class="bagian-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h5 class="card-title">Bagian 1</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2 -->
               <div class="col-6 col-md-6 col-lg-4">
                    <div class="bagian-card">
                        <div class="card-body text-center">
                            <div class="bagian-icon" style="background: var(--secondary-brown);">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h5 class="card-title">Bagian 2</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3 -->
               <div class="col-6 col-md-6 col-lg-4">
                    <div class="bagian-card">
                        <div class="card-body text-center">
                            <div class="bagian-icon" style="background: var(--accent-brown);">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h5 class="card-title">Bagian 3</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 4 -->
              <div class="col-6 col-md-6 col-lg-4">
                    <div class="bagian-card">
                        <div class="card-body text-center">
                            <div class="bagian-icon" style="background: var(--dark-brown);">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h5 class="card-title">Bagian 4</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 5 -->
               <div class="col-6 col-md-6 col-lg-4">
                    <div class="bagian-card">
                        <div class="card-body text-center">
                            <div class="bagian-icon" style="background: #667eea;">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h5 class="card-title">Bagian 5</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 6 -->
               <div class="col-6 col-md-6 col-lg-4">
                    <div class="bagian-card">
                        <div class="card-body text-center">
                            <div class="bagian-icon" style="background: #f093fb;">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">Bagian 6</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>