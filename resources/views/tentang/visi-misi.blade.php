@extends('layouts.app')

@section('title', 'Visi & Misi - SPMI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card custom-card border-0">
                <div class="card-body p-5">
                    <h1 class="fw-bold mb-5 text-center" style="color: var(--primary-brown);">
                        <i class="fas fa-bullseye me-2"></i>Visi & Misi SPMI IKIP Siliwangi
                    </h1>

                    <!-- Visi IKIP Siliwangi -->
                    <div class="text-center mb-5 p-4 rounded" style="background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%); border-left: 5px solid var(--primary-brown);">
                        <h2 class="fw-bold mb-3" style="color: var(--dark-brown);">
                            <i class="fas fa-eye me-2"></i>Visi IKIP Siliwangi
                        </h2>
                        <p class="lead fw-semibold" style="color: var(--primary-brown);">
                            "Terwujudnya IKIP Siliwangi sebagai perguruan tinggi yang unggul dalam penyelenggaraan 
                            pendidikan, penelitian, dan pengabdian kepada masyarakat pada tahun 2030"
                        </p>
                    </div>

                    <!-- Misi IKIP Siliwangi -->
                    <div class="mb-5">
                        <h2 class="fw-bold mb-4 text-center" style="color: var(--primary-brown);">
                            <i class="fas fa-tasks me-2"></i>Misi IKIP Siliwangi
                        </h2>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="program-card h-100 p-4 text-center">
                                    <div class="program-icon program-1 mx-auto mb-3">
                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold mb-3">Pendidikan Bermutu</h4>
                                    <p>
                                        Menyelenggarakan pendidikan yang bermutu dan relevan dengan kebutuhan masyarakat
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="program-card h-100 p-4 text-center">
                                    <div class="program-icon program-2 mx-auto mb-3">
                                        <i class="fas fa-flask fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold mb-3">Penelitian Inovatif</h4>
                                    <p>
                                        Mengembangkan penelitian yang inovatif dan bermanfaat bagi pengembangan ilmu pengetahuan
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="program-card h-100 p-4 text-center">
                                    <div class="program-icon program-3 mx-auto mb-3">
                                        <i class="fas fa-hands-helping fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold mb-3">Pengabdian Masyarakat</h4>
                                    <p>
                                        Meningkatkan pengabdian kepada masyarakat untuk memecahkan masalah sosial
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visi SPMI -->
                    <div class="text-center mb-5 p-4 rounded" style="background: linear-gradient(135deg, #f5f0e6 0%, #ebe6d9 100%); border-left: 5px solid var(--dark-brown);">
                        <h2 class="fw-bold mb-3" style="color: var(--dark-brown);">
                            <i class="fas fa-crosshairs me-2"></i>Visi SPMI
                        </h2>
                        <p class="lead fw-semibold" style="color: var(--dark-brown);">
                            "Menjadi pusat penjaminan mutu pendidikan yang unggul, 
                            inovatif, dan berdaya saing global pada tahun 2030"
                        </p>
                    </div>

                    <!-- Misi SPMI -->
                    <div class="mb-5">
                        <h2 class="fw-bold mb-4 text-center" style="color: var(--primary-brown);">
                            <i class="fas fa-bullseye me-2"></i>Misi SPMI
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
                                        melalui standar yang berkelanjutan sesuai UU No. 49 Tahun 2014
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
                                        untuk perbaikan terus-menerus (PPEPP)
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
                                        dan unit kerja secara konsisten
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="program-card h-100 p-4">
                                    <div class="program-icon program-4 mx-auto mb-3">
                                        <i class="fas fa-globe fa-2x"></i>
                                    </div>
                                    <h4 class="fw-bold text-center mb-3">Standar Nasional</h4>
                                    <p class="text-center">
                                        Menerapkan standar nasional pendidikan tinggi 
                                        dalam penjaminan mutu pendidikan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tujuan SPMI -->
                    <div class="mt-5">
                        <h2 class="fw-bold mb-4 text-center" style="color: var(--primary-brown);">
                            <i class="fas fa-flag me-2"></i>Tujuan SPMI
                        </h2>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="program-icon-sm program-1 mb-3">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                    <h6 class="fw-bold">Akreditasi Unggul</h6>
                                    <small>Mencapai akreditasi unggul untuk semua program studi sesuai standar nasional</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="program-icon-sm program-2 mb-3">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h6 class="fw-bold">Lulusan Berkualitas</h6>
                                    <small>Meningkatkan kompetensi dan daya saing lulusan sesuai kebutuhan masyarakat</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <div class="program-icon-sm program-3 mb-3">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                    <h6 class="fw-bold">Monitoring Berkelanjutan</h6>
                                    <small>Melaksanakan monitoring dan evaluasi secara konsisten untuk menjaga mutu</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PPEPP Section -->
                    <div class="mt-5 p-4 rounded" style="background-color: #f8f9fa; border: 1px solid var(--primary-brown);">
                        <h4 class="fw-bold mb-3 text-center" style="color: var(--primary-brown);">
                            <i class="fas fa-sync-alt me-2"></i>Siklus PPEPP (Pelaksanaan, Evaluasi, Pengendalian, Peningkatan)
                        </h4>
                        <div class="row text-center mt-4">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded-circle d-inline-block" style="background-color: var(--primary-brown); color: white; width: 100px; height: 100px; line-height: 100px;">
                                    <i class="fas fa-play fa-2x"></i>
                                </div>
                                <h6 class="mt-2 fw-bold">Pelaksanaan</h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded-circle d-inline-block" style="background-color: #b37400; color: white; width: 100px; height: 100px; line-height: 100px;">
                                    <i class="fas fa-chart-bar fa-2x"></i>
                                </div>
                                <h6 class="mt-2 fw-bold">Evaluasi</h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded-circle d-inline-block" style="background-color: #cc9900; color: white; width: 100px; height: 100px; line-height: 100px;">
                                    <i class="fas fa-sliders-h fa-2x"></i>
                                </div>
                                <h6 class="mt-2 fw-bold">Pengendalian</h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded-circle d-inline-block" style="background-color: #e6b800; color: #333; width: 100px; height: 100px; line-height: 100px;">
                                    <i class="fas fa-level-up-alt fa-2x"></i>
                                </div>
                                <h6 class="mt-2 fw-bold">Peningkatan</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .program-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .program-icon.program-1 {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
    }
    
    .program-icon.program-2 {
        background: linear-gradient(135deg, #b37400 0%, #996600 100%);
    }
    
    .program-icon.program-3 {
        background: linear-gradient(135deg, #cc9900 0%, #b37400 100%);
    }
    
    .program-icon.program-4 {
        background: linear-gradient(135deg, #e6b800 0%, #cc9900 100%);
    }
    
    .program-icon-sm {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .program-card {
        transition: transform 0.3s ease;
        border: 1px solid rgba(140, 100, 60, 0.2);
        border-radius: 10px;
    }
    
    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(140, 100, 60, 0.1);
    }
</style>
@endsection