@extends('layouts.app')

@section('title', 'Struktur Organisasi - SPMI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom-card border-0">
                <div class="card-body p-5">
                    <h1 class="fw-bold mb-5 text-center" style="color: var(--primary-brown);">
                        <i class="fas fa-sitemap me-2"></i>Struktur Organisasi SPMI
                    </h1>

                    <!-- Organizational Chart -->
                    <div class="text-center mb-5">
                        <div class="org-chart">
                            <!-- Level 1: Rektor -->
                            <div class="org-level-1 mx-auto mb-4">
                                <div class="org-node p-4 rounded shadow" style="background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%); color: white;">
                                    <h5 class="fw-bold mb-1">Rektor</h5>
                                    <p class="mb-0 small opacity-90">Pimpinan Universitas</p>
                                </div>
                            </div>

                            <!-- Level 2: Wakil Rektor -->
                            <div class="org-level-2 d-flex justify-content-center mb-4">
                                <div class="org-node p-3 mx-3 rounded shadow" style="background: linear-gradient(135deg, #b37400 0%, #996600 100%); color: white;">
                                    <h6 class="fw-bold mb-1">Wakil Rektor I</h6>
                                    <p class="mb-0 small opacity-90">Bidang Akademik</p>
                                </div>
                            </div>

                            <!-- Level 3: LPM & Unit -->
                            <div class="org-level-3 d-flex justify-content-center mb-4">
                                <div class="org-node p-3 mx-2 rounded shadow" style="background: linear-gradient(135deg, #cc9900 0%, #b37400 100%); color: white;">
                                    <h6 class="fw-bold mb-1">LPM</h6>
                                    <p class="mb-0 small opacity-90">Lembaga Penjaminan Mutu</p>
                                </div>
                            </div>

                            <!-- Level 4: SPMI -->
                            <div class="org-level-4 d-flex justify-content-center mb-4">
                                <div class="org-node p-3 mx-2 rounded shadow" style="background: linear-gradient(135deg, #e6b800 0%, #cc9900 100%); color: #333;">
                                    <h6 class="fw-bold mb-1">SPMI</h6>
                                    <p class="mb-0 small opacity-90">Sistem Penjaminan Mutu Internal</p>
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
                                            Pengembangan dan pemeliharaan standar mutu
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="org-node p-3 rounded shadow h-100" style="background: white; border: 2px solid var(--primary-brown);">
                                        <h6 class="fw-bold mb-2" style="color: var(--primary-brown);">
                                            <i class="fas fa-clipboard-check me-1"></i>Divisi Audit
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Pelaksanaan audit internal dan monitoring
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="org-node p-3 rounded shadow h-100" style="background: white; border: 2px solid var(--primary-brown);">
                                        <h6 class="fw-bold mb-2" style="color: var(--primary-brown);">
                                            <i class="fas fa-chart-pie me-1"></i>Divisi Data
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Pengolahan data dan analisis mutu
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="org-node p-3 rounded shadow h-100" style="background: white; border: 2px solid var(--primary-brown);">
                                        <h6 class="fw-bold mb-2" style="color: var(--primary-brown);">
                                            <i class="fas fa-file-contract me-1"></i>Divisi Dokumen
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Manajemen dokumen mutu dan pelaporan
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
                                    <i class="fas fa-users me-2"></i>Tim Manajemen
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
                                        <strong>Anggota:</strong> 12 orang
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="program-card h-100 p-4">
                                <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                                    <i class="fas fa-calendar-alt me-2"></i>Periode Kerja
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
                                </ul>
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
        }
    }
</style>
@endsection