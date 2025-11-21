<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Studi - APK SPMI</title>
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
        
        .prodi-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .prodi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .prodi-icon {
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
        
        .akreditasi-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .akreditasi-A { background: #d1fae5; color: #065f46; }
        .akreditasi-B { background: #fef3c7; color: #92400e; }
        .akreditasi-C { background: #fee2e2; color: #dc2626; }
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
                        <h1 class="display-5 fw-bold mb-3">Program Studi</h1>
                        <p class="lead mb-0">Daftar program studi yang tersedia di IKIP Siliwangi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Program Studi Cards Section -->
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="fw-bold">Daftar Program Studi</h3>
                </div>
            </div>

            <div class="row g-4">
                <!-- Prodi 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="prodi-card">
                        <div class="card-body text-center">
                            <div class="prodi-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h5 class="card-title">Program Studi 1</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prodi 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="prodi-card">
                        <div class="card-body text-center">
                            <div class="prodi-icon" style="background: var(--secondary-brown);">
                                <i class="fas fa-language"></i>
                            </div>
                            <h5 class="card-title">Program Studi 2</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prodi 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="prodi-card">
                        <div class="card-body text-center">
                            <div class="prodi-icon" style="background: var(--accent-brown);">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <h5 class="card-title">Program Studi 3</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prodi 4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="prodi-card">
                        <div class="card-body text-center">
                            <div class="prodi-icon" style="background: var(--dark-brown);">
                                <i class="fas fa-flask"></i>
                            </div>
                            <h5 class="card-title">Program Studi 4</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prodi 5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="prodi-card">
                        <div class="card-body text-center">
                            <div class="prodi-icon" style="background: #667eea;">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h5 class="card-title">Program Studi 5</h5>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prodi 6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="prodi-card">
                        <div class="card-body text-center">
                            <div class="prodi-icon" style="background: #f093fb;">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <h5 class="card-title">Program Studi 6</h5>
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