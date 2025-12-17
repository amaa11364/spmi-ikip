<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPT - APK SPMI</title>
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
        
        .upt-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .upt-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .upt-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto 1rem;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-inactive {
            background: #fee2e2;
            color: #dc2626;
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
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
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
                        <h1 class="display-5 fw-bold mb-3">Unit Pelaksana Teknis (UPT)</h1>
                        <p class="lead mb-0">Daftar semua Unit Pelaksana Teknis</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- UPT Cards Section -->
        <div class="container">
            <div class="row g-4">
                <!-- UPT 1 -->
               <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: var(--primary-brown);">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 1</h5>
                        </div>
                    </div>
                </div>

                <!-- UPT 2 -->
               <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: var(--secondary-brown);">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 2</h5>
                        </div>
                    </div>
                </div>

                <!-- UPT 3 -->
               <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: var(--accent-brown);">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 3</h5>
                        </div>
                    </div>
                </div>

                <!-- UPT 4 -->
                <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: var(--dark-brown);">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 4</h5>
                        </div>
                    </div>
                </div>

                <!-- UPT 5 -->
                <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: #667eea;">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 5</h5>
                        </div>
                    </div>
                </div>

                <!-- UPT 6 -->
               <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: #f093fb;">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 6</h5>
                        </div>
                    </div>
                </div>

                <!-- UPT 7 -->
               <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: #4facfe;">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 7</h5>
                        </div>
                    </div>
                </div>

                <!-- UPT 8 -->
               <div class="col-6 col-md-6 col-lg-3">
                    <div class="upt-card">
                        <div class="card-body text-center">
                            <div class="upt-icon" style="background: #43e97b;">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="card-title">UPT 8</h5> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>