@extends('layouts.app')

@section('title', 'Struktur Organisasi - SPMI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom-card border-0">
                <div class="card-body p-5">
                    <h1 class="fw-bold mb-5 text-center" style="color: var(--primary-brown);">
                        <i class="fas fa-sitemap me-2"></i>Struktur Organisasi SPMI IKIP Siliwangi
                    </h1>

                    <!-- Organizational Chart -->
                    <div class="text-center mb-5">
                        <div class="org-chart">
                            <!-- Level 1: Rektor -->
                            <div class="org-level-1 mx-auto mb-4">
                                <div class="org-node p-4 rounded shadow" style="background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%); color: white;">
                                    <h5 class="fw-bold mb-1">Rektor IKIP Siliwangi</h5>
                                    <p class="mb-0 small opacity-90">Penanggung Jawab Mutu Pendidikan</p>
                                </div>
                            </div>

                            <!-- Level 2: Wakil Rektor -->
                            <div class="org-level-2 d-flex justify-content-center mb-4">
                                <div class="org-node p-3 mx-3 rounded shadow" style="background: linear-gradient(135deg, #b37400 0%, #996600 100%); color: white;">
                                    <h6 class="fw-bold mb-1">Wakil Rektor I</h6>
                                    <p class="mb-0 small opacity-90">Bidang Akademik dan Mutu</p>
                                </div>
                            </div>

                            <!-- Level 3: LPMI -->
                            <div class="org-level-3 d-flex justify-content-center mb-4">
                                <div class="org-node p-3 mx-2 rounded shadow" style="background: linear-gradient(135deg, #cc9900 0%, #b37400 100%); color: white;">
                                    <h6 class="fw-bold mb-1">Lembaga Penjaminan Mutu Internal (LPMI)</h6>
                                    <p class="mb-0 small opacity-90">Unit Pelaksana Monitoring & Evaluasi</p>
                                </div>
                            </div>

                            <!-- Level 4: SPMI -->
                            <div class="org-level-4 d-flex justify-content-center mb-4">
                                <div class="org-node p-3 mx-2 rounded shadow" style="background: linear-gradient(135deg, #e6b800 0%, #cc9900 100%); color: #333;">
                                    <h6 class="fw-bold mb-1">Sistem Penjaminan Mutu Internal (SPMI)</h6>
                                    <p class="mb-0 small opacity-90">Pelaksana PPEPP Berkelanjutan</p>
                                </div>
                            </div>

                            <!-- Level 5: Divisions -->
                            <div class="org-level-5 row justify-content-center g-3">
                                <div class="col-lg-3 col-md-6">
                                    <div class="org-node p-3 rounded shadow h-100" style="background: white; border: 2px solid var(--primary-brown);">
                                        <h6 class="fw-bold mb-2" style="color: var(--primary-brown);">
                                            <i class="fas fa-tasks me-1"></i>Divisi Standar
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Pengembangan dan pemeliharaan standar mutu sesuai UU No. 49/2014
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="org-node p-3 rounded shadow h-100" style="background: white; border: 2px solid var(--primary-brown);">
                                        <h6 class="fw-bold mb-2" style="color: var(--primary-brown);">
                                            <i class="fas fa-clipboard-check me-1"></i>Divisi Audit
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Pelaksanaan audit internal dan monitoring akademik/non-akademik
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="org-node p-3 rounded shadow h-100" style="background: white; border: 2px solid var(--primary-brown);">
                                        <h6 class="fw-bold mb-2" style="color: var(--primary-brown);">
                                            <i class="fas fa-chart-pie me-1"></i>Divisi Data & Evaluasi
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Pengolahan data dan analisis mutu untuk perbaikan berkelanjutan
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="org-node p-3 rounded shadow h-100" style="background: white; border: 2px solid var(--primary-brown);">
                                        <h6 class="fw-bold mb-2" style="color: var(--primary-brown);">
                                            <i class="fas fa-file-contract me-1"></i>Divisi Dokumen
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Manajemen dokumen mutu dan pelaporan sesuai standar nasional
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Struktur -->
                    <div class="row mt-5">
                        <div class="col-md-6 mb-4">
                            <div class="program-card h-100 p-4">
                                <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                                    <i class="fas fa-users me-2"></i>Tim Manajemen SPMI
                                </h4>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <strong>Ketua SPMI:</strong> Dr. H. Ahmad Supriyadi, M.Pd.
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <strong>Sekretaris:</strong> Dra. Siti Aisyah, M.Si.
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <strong>Koordinator Divisi:</strong> 4 orang
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <strong>Anggota (Auditor Internal):</strong> 12 orang
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <strong>Staf Administrasi:</strong> 3 orang
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="program-card h-100 p-4">
                                <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                                    <i class="fas fa-calendar-alt me-2"></i>Periode Kerja & Legalitas
                                </h4>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="far fa-calendar-alt text-primary me-2"></i>
                                        <strong>Masa Bakti:</strong> 2024 - 2028
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-gavel text-primary me-2"></i>
                                        <strong>SK Pengangkatan:</strong> 001/SPMI/IKIP/2024
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-file-signature text-primary me-2"></i>
                                        <strong>Dasar Hukum:</strong> Peraturan Rektor No. 15 Tahun 2024
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-balance-scale text-primary me-2"></i>
                                        <strong>Landasan Hukum:</strong> UU No. 49 Tahun 2014 tentang Sisdiknas Tinggi
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                                        <strong>Sistem Kerja:</strong> PPEPP (Pelaksanaan, Evaluasi, Pengendalian, Peningkatan)
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Fungsi & Tugas -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="program-card p-4">
                                <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                                    <i class="fas fa-cogs me-2"></i>Fungsi & Tugas SPMI
                                </h4>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-chart-line fa-2x" style="color: var(--primary-brown);"></i>
                                            </div>
                                            <h6 class="fw-bold">Monitoring & Evaluasi</h6>
                                            <small>Melakukan monitoring kegiatan akademik dan non-akademik secara berkala</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-clipboard-check fa-2x" style="color: var(--primary-brown);"></i>
                                            </div>
                                            <h6 class="fw-bold">Audit Internal</h6>
                                            <small>Melaksanakan audit mutu internal untuk semua unit kerja</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-sync-alt fa-2x" style="color: var(--primary-brown);"></i>
                                            </div>
                                            <h6 class="fw-bold">Perbaikan Berkelanjutan</h6>
                                            <small>Menerapkan siklus PPEPP untuk peningkatan mutu terus-menerus</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .org-chart {
        position: relative;
    }
    
    .org-level-1::before,
    .org-level-2::before,
    .org-level-3::before,
    .org-level-4::before {
        content: '';
        position: absolute;
        width: 2px;
        background: var(--primary-brown);
        left: 50%;
        transform: translateX(-50%);
    }
    
    .org-level-1::before {
        height: 40px;
        top: 100%;
    }
    
    .org-level-2::before {
        height: 40px;
        top: 100%;
    }
    
    .org-level-3::before {
        height: 40px;
        top: 100%;
    }
    
    .org-level-4::before {
        height: 40px;
        top: 100%;
    }
    
    .org-node {
        position: relative;
        transition: transform 0.3s ease;
    }
    
    .org-node:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(140, 100, 60, 0.2) !important;
    }
    
    .program-card {
        border: 1px solid rgba(140, 100, 60, 0.2);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .program-card:hover {
        box-shadow: 0 10px 20px rgba(140, 100, 60, 0.1);
    }
    
    @media (max-width: 768px) {
        .org-level-2,
        .org-level-3,
        .org-level-4 {
            flex-direction: column;
            align-items: center;
        }
        
        .org-node {
            margin: 10px 0;
            width: 90%;
        }
        
        .org-level-5 .col-lg-3 {
            margin-bottom: 15px;
        }
    }
</style>
@endsection