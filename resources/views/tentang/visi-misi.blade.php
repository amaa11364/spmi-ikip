@extends('layouts.app')

@section('title', 'Visi & Misi - SPMI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card custom-card border-0">
                <div class="card-body p-5">
                    <h1 class="fw-bold mb-5 text-center" style="color: var(--primary-brown);">
                        <i class="fas fa-bullseye me-2"></i>Visi & Misi SPMI
                    </h1>

                    <!-- Visi Section -->
                    <div class="text-center mb-5 p-4 rounded" style="background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%); border-left: 5px solid var(--primary-brown);">
                        <h2 class="fw-bold mb-3" style="color: var(--dark-brown);">
                            <i class="fas fa-eye me-2"></i>Visi
                        </h2>
                        <p class="lead fw-semibold" style="color: var(--primary-brown);">
                            "Menjadi pusat penjaminan mutu pendidikan yang unggul, 
                            inovatif, dan berdaya saing global pada tahun 2030"
                        </p>
                    </div>

                    <!-- Misi Section -->
                    <div class="mb-5">
                        <h2 class="fw-bold mb-4 text-center" style="color: var(--primary-brown);">
                            <i class="fas fa-tasks me-2"></i>Misi
                        </h2>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="program-card h-100 p-4">
                                    <div class="program-icon program-1 mx-auto mb-3">
                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold text-center mb-3">Mutu Pendidikan</h4>
                                    <p class="text-center">
                                        Menjamin dan meningkatkan mutu penyelenggaraan pendidikan 
                                        melalui standar yang berkelanjutan
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="program-card h-100 p-4">
                                    <div class="program-icon program-2 mx-auto mb-3">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold text-center mb-3">Evaluasi Berkelanjutan</h4>
                                    <p class="text-center">
                                        Mengembangkan sistem evaluasi diri dan audit internal 
                                        untuk perbaikan terus-menerus
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="program-card h-100 p-4">
                                    <div class="program-icon program-3 mx-auto mb-3">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold text-center mb-3">Budaya Mutu</h4>
                                    <p class="text-center">
                                        Membangun budaya mutu di seluruh sivitas akademika 
                                        dan unit kerja
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="program-card h-100 p-4">
                                    <div class="program-icon program-4 mx-auto mb-3">
                                        <i class="fas fa-globe fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold text-center mb-3">Standar Global</h4>
                                    <p class="text-center">
                                        Menerapkan standar nasional dan internasional 
                                        dalam penjaminan mutu pendidikan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tujuan Section -->
                    <div class="mt-5">
                        <h2 class="fw-bold mb-4 text-center" style="color: var(--primary-brown);">
                            <i class="fas fa-flag me-2"></i>Tujuan
                        </h2>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="program-icon-sm program-1 mb-3">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                    <h6 class="fw-bold">Akreditasi Unggul</h6>
                                    <small>Mencapai akreditasi unggul untuk semua program studi</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="program-icon-sm program-2 mb-3">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h6 class="fw-bold">Lulusan Berkualitas</h6>
                                    <small>Meningkatkan kompetensi dan daya saing lulusan</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="program-icon-sm program-3 mb-3">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <h6 class="fw-bold">Kerjasama Internasional</h6>
                                    <small>Memperluas jaringan kerjasama tingkat internasional</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection