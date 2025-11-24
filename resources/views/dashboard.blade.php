@extends('layouts.main')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* Hanya tambahan responsive */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 1rem;
        }
        
        .welcome-card h2 {
            font-size: 1.5rem;
        }
        
        .stats-badge {
            margin-top: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .welcome-card .row {
            text-align: center;
        }
        
        .quick-stats .col-md-3 {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Welcome Section -->
<div class="row">
    <div class="col-12">
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8 col-12">
                    <h4 class="fw-bold mb-2">LPM Smart Sistem</h4>
                    <h2 class="fw-bold mb-3">Selamat datang, {{ auth()->user()->name }}</h2>
                    <p class="mb-0 opacity-90">
                        Kamu dapat melakukan pemberkasan dengan lebih mudah dan untuk saat ini terdapat 
                        <span class="fw-bold">6 Program Studi</span> yang terdaftar pada sistem.
                    </p>
                </div>
                <div class="col-md-4 col-12 text-center text-md-end">
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
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="p-3">
                            <h3 class="fw-bold text-success">8</h3>
                            <p class="text-muted mb-0">Audit Selesai</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="p-3">
                            <h3 class="fw-bold text-warning">24</h3>
                            <p class="text-muted mb-0">Dokumen Mutu</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
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
@endsection