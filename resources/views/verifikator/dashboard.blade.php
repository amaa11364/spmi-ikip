@extends('layouts.main')

@section('title', 'Dashboard Verifikator')

@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h3 mb-1 mb-md-2">Dashboard Verifikator</h1>
            <div class="text-muted">
                <small>Login sebagai: <strong>{{ auth()->user()->name }}</strong> | Unit: <strong>{{ auth()->user()->unit_kerja->nama ?? 'Tidak ada unit' }}</strong></small>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-primary btn-sm me-2" onclick="loadPendingCount()">
                <i class="fas fa-sync-alt"></i>
                <span class="d-none d-md-inline">Refresh</span>
            </button>
            <span class="badge bg-primary">Hari ini: {{ now()->format('d M Y') }}</span>
        </div>
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="row g-3 g-md-4 mb-4">
        {{-- Menunggu Verifikasi --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="custom-card h-100 p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="text-muted mb-2 fs-6">Menunggu Verifikasi</h5>
                        <h2 class="fw-bold mb-2 display-6">{{ $pendingCount ?? 0 }}</h2>
                        <div class="d-flex align-items-center">
                            <small class="text-warning">
                                <i class="fas fa-clock me-1"></i>
                                <span class="d-none d-sm-inline">Butuh tindakan</span>
                                <span class="d-inline d-sm-none">Tindakan</span>
                            </small>
                        </div>
                    </div>
                    <div class="bg-warning text-white rounded-circle p-2 p-md-3 ms-3 flex-shrink-0">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3 mt-md-4">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" 
                             role="progressbar" 
                             style="width: {{ $pendingPercent ?? 0 }}%"
                             aria-valuenow="{{ $pendingPercent ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Disetujui --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="custom-card h-100 p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="text-muted mb-2 fs-6">Disetujui</h5>
                        <h2 class="fw-bold mb-2 display-6">{{ $approvedCount ?? 0 }}</h2>
                        <div class="d-flex align-items-center">
                            <small class="text-success">
                                <i class="fas fa-check me-1"></i>
                                <span class="d-none d-sm-inline">Dokumen selesai</span>
                                <span class="d-inline d-sm-none">Selesai</span>
                            </small>
                        </div>
                    </div>
                    <div class="bg-success text-white rounded-circle p-2 p-md-3 ms-3 flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3 mt-md-4">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $approvedPercent ?? 0 }}%"
                             aria-valuenow="{{ $approvedPercent ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Ditolak --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="custom-card h-100 p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="text-muted mb-2 fs-6">Ditolak</h5>
                        <h2 class="fw-bold mb-2 display-6">{{ $rejectedCount ?? 0 }}</h2>
                        <div class="d-flex align-items-center">
                            <small class="text-danger">
                                <i class="fas fa-times me-1"></i>
                                <span class="d-none d-sm-inline">Dokumen ditolak</span>
                                <span class="d-inline d-sm-none">Ditolak</span>
                            </small>
                        </div>
                    </div>
                    <div class="bg-danger text-white rounded-circle p-2 p-md-3 ms-3 flex-shrink-0">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3 mt-md-4">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" 
                             role="progressbar" 
                             style="width: {{ $rejectedPercent ?? 0 }}%"
                             aria-valuenow="{{ $rejectedPercent ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Revisi --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="custom-card h-100 p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="text-muted mb-2 fs-6">Revisi</h5>
                        <h2 class="fw-bold mb-2 display-6">{{ $revisionCount ?? 0 }}</h2>
                        <div class="d-flex align-items-center">
                            <small class="text-info">
                                <i class="fas fa-edit me-1"></i>
                                <span class="d-none d-sm-inline">Perlu perbaikan</span>
                                <span class="d-inline d-sm-none">Perbaikan</span>
                            </small>
                        </div>
                    </div>
                    <div class="bg-info text-white rounded-circle p-2 p-md-3 ms-3 flex-shrink-0">
                        <i class="fas fa-edit fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3 mt-md-4">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" 
                             role="progressbar" 
                             style="width: {{ $revisionPercent ?? 0 }}%"
                             aria-valuenow="{{ $revisionPercent ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="custom-card p-3 p-md-4">
                <h5 class="mb-3 mb-md-4">Aksi Cepat</h5>
                <div class="row g-3">
                    {{-- Review Dokumen --}}
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('verifikator.dokumen.pending') }}" 
                           class="btn btn-warning w-100 d-flex align-items-center p-2 p-md-3 h-100">
                            <div class="bg-white text-warning rounded-circle p-2 me-2 me-md-3 flex-shrink-0">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div class="text-start flex-grow-1">
                                <strong class="d-block fs-6">Review Dokumen</strong>
                                <small class="d-block text-truncate">
                                    {{ $pendingCount ?? 0 }} dokumen menunggu verifikasi
                                </small>
                                <small class="text-muted d-none d-md-block">Verifikasi dokumen terbaru</small>
                            </div>
                            <i class="fas fa-chevron-right ms-2 d-none d-md-block"></i>
                        </a>
                    </div>
                    
                    {{-- Semua Dokumen --}}
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('verifikator.dokumen.index') }}" 
                           class="btn btn-primary w-100 d-flex align-items-center p-2 p-md-3 h-100">
                            <div class="bg-white text-primary rounded-circle p-2 me-2 me-md-3 flex-shrink-0">
                                <i class="fas fa-list fa-2x"></i>
                            </div>
                            <div class="text-start flex-grow-1">
                                <strong class="d-block fs-6">Semua Dokumen</strong>
                                <small class="d-block text-truncate">
                                    {{ $totalDocuments ?? 0 }} total dokumen
                                </small>
                                <small class="text-muted d-none d-md-block">Lihat dan kelola semua dokumen</small>
                            </div>
                            <i class="fas fa-chevron-right ms-2 d-none d-md-block"></i>
                        </a>
                    </div>
                    
                    {{-- Statistik --}}
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('verifikator.statistik.index') }}"
                           class="btn btn-success w-100 d-flex align-items-center p-2 p-md-3 h-100">
                            <div class="bg-white text-success rounded-circle p-2 me-2 me-md-3 flex-shrink-0">
                                <i class="fas fa-chart-bar fa-2x"></i>
                            </div>
                            <div class="text-start flex-grow-1">
                                <strong class="d-block fs-6">Statistik</strong>
                                <small class="d-block text-truncate">
                                    Analisis performa verifikasi
                                </small>
                                <small class="text-muted d-none d-md-block">Analisis dan laporan verifikasi</small>
                            </div>
                            <i class="fas fa-chevron-right ms-2 d-none d-md-block"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dokumen Perlu Verifikasi & Panduan --}}
    <div class="row g-4">
        {{-- Dokumen Perlu Verifikasi --}}
        <div class="col-12 col-lg-8">
            <div class="custom-card h-100 p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4">
                    <h5 class="mb-2 mb-md-0">Dokumen Perlu Verifikasi</h5>
                    <div class="d-flex">
                        <a href="{{ route('verifikator.dokumen.pending') }}" 
                           class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-list me-1"></i>
                            <span class="d-none d-md-inline">Lihat Semua</span>
                            <span class="d-inline d-md-none">Semua</span>
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshTable()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                
                @if($pendingDocuments && $pendingDocuments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light d-none d-md-table-header-group">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Judul Dokumen</th>
                                    <th width="150">Pengunggah</th>
                                    <th width="120">Tanggal</th>
                                    <th width="120" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingDocuments as $index => $dokumen)
                                    <tr class="border-bottom">
                                        <td class="d-none d-md-table-cell align-middle">
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <strong class="mb-1 text-break">
                                                    {{ Str::limit($dokumen->judul, 60) }}
                                                </strong>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted me-2">
                                                        <i class="fas fa-tag me-1"></i>{{ $dokumen->kategori ?? 'Umum' }}
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-file me-1"></i>{{ strtoupper($dokumen->file_extension) }}
                                                    </small>
                                                </div>
                                                {{-- Mobile View --}}
                                                <div class="d-flex d-md-none justify-content-between mt-2">
                                                    <div>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-user me-1"></i>
                                                            {{ Str::limit($dokumen->uploader->name ?? 'Tidak diketahui', 15) }}
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $dokumen->created_at->format('d/m') }}
                                                        </small>
                                                    </div>
                                                    <div class="btn-group" role="group">
                                                        <button onclick="viewDokumen('{{ $dokumen->id }}')" 
                                                                class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button onclick="downloadDokumen('{{ $dokumen->id }}')" 
                                                                class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <span class="fw-bold">{{ strtoupper(substr($dokumen->uploader->name ?? 'U', 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $dokumen->uploader->name ?? 'Tidak diketahui' }}</div>
                                                    <small class="text-muted">{{ $dokumen->uploader->unit_kerja ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell align-middle">
                                            <div class="text-nowrap">
                                                <div>{{ $dokumen->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $dokumen->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell align-middle">
                                            <div class="d-flex justify-content-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button onclick="viewDokumen('{{ $dokumen->id }}')" 
                                                            class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button onclick="downloadDokumen('{{ $dokumen->id }}')" 
                                                            class="btn btn-outline-info" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-success dropdown-toggle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown"
                                                                data-bs-toggle="tooltip"
                                                                title="Verifikasi">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item text-success" 
                                                                   href="#" 
                                                                   onclick="simpleVerification('{{ $dokumen->id }}', 'approved')">
                                                                    <i class="fas fa-check me-2"></i>Setujui
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-warning" 
                                                                   href="#" 
                                                                   onclick="simpleVerification('{{ $dokumen->id }}', 'revision')">
                                                                    <i class="fas fa-edit me-2"></i>Minta Revisi
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" 
                                                                   href="#" 
                                                                   onclick="simpleVerification('{{ $dokumen->id }}', 'rejected')">
                                                                    <i class="fas fa-times me-2"></i>Tolak
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Mobile Only: Quick Actions for Pending --}}
                    <div class="d-block d-md-none mt-3">
                        <div class="alert alert-info py-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Geser ke kiri untuk melihat semua aksi
                            </small>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-4x text-success opacity-50"></i>
                        </div>
                        <h5 class="text-muted mb-2">Tidak ada dokumen menunggu</h5>
                        <p class="text-muted mb-4">Semua dokumen sudah diverifikasi.</p>
                        <a href="{{ route('verifikator.dokumen.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-1"></i> Lihat Semua Dokumen
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- Panel Panduan & Statistik --}}
        <div class="col-12 col-lg-4">
            <div class="custom-card h-100 p-3 p-md-4">
                <h5 class="mb-3 mb-md-4">Panduan Verifikasi</h5>
                <div class="list-group list-group-flush mb-4">
                    {{-- Setujui --}}
                    <div class="list-group-item px-0 py-2 border-0">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-success text-white rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <strong class="d-block">Setujui Dokumen</strong>
                            </div>
                        </div>
                        <small class="text-muted ps-5">
                            Dokumen sudah sesuai dengan standar dan persyaratan yang ditetapkan.
                        </small>
                    </div>
                    
                    {{-- Tolak --}}
                    <div class="list-group-item px-0 py-2 border-0">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-danger text-white rounded-circle p-2 me-3">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <strong class="d-block">Tolak Dokumen</strong>
                            </div>
                        </div>
                        <small class="text-muted ps-5">
                            Dokumen tidak memenuhi persyaratan dengan alasan yang jelas dan spesifik.
                        </small>
                    </div>
                    
                    {{-- Revisi --}}
                    <div class="list-group-item px-0 py-2 border-0">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-warning text-white rounded-circle p-2 me-3">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <strong class="d-block">Minta Revisi</strong>
                            </div>
                        </div>
                        <small class="text-muted ps-5">
                            Dokumen perlu perbaikan atau tambahan informasi sebelum dapat disetujui.
                        </small>
                    </div>
                </div>
                
                <hr class="my-4">
                
                {{-- Statistik Cepat --}}
                <h6 class="mb-3">Statistik Mingguan</h6>
                <div class="row g-2 text-center mb-4">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fw-bold text-primary display-6">{{ $weeklyApproved ?? 0 }}</div>
                            <small class="text-muted">Disetujui</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fw-bold text-warning display-6">{{ $weeklyPending ?? 0 }}</div>
                            <small class="text-muted">Menunggu</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fw-bold text-danger display-6">{{ $weeklyRejected ?? 0 }}</div>
                            <small class="text-muted">Ditolak</small>
                        </div>
                    </div>
                </div>
                
                {{-- Info Tambahan --}}
                <div class="alert alert-light border">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-lightbulb text-warning fa-2x"></i>
                        </div>
                        <div>
                            <strong>Tips Verifikasi Cepat</strong>
                            <small class="d-block text-muted mt-1">
                                Periksa kelengkapan dokumen, format file, dan kesesuaian dengan standar sebelum memutuskan.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    // Refresh table data
    function refreshTable() {
        $.ajax({
            url: '{{ route("verifikator.dashboard") }}',
            type: 'GET',
            beforeSend: function() {
                // Show loading indicator
                Swal.fire({
                    title: 'Memuat ulang...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.close();
                // You can update specific parts of the page here
                location.reload(); // Simple reload for now
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memuat',
                    text: 'Terjadi kesalahan saat memuat data'
                });
            }
        });
    }
    
    // Load pending count via AJAX
    function loadPendingCount() {
        $.ajax({
            url: '{{ route("verifikator.dokumen.pending-count") }}',
            type: 'GET',
            success: function(response) {
                if (response.count > 0) {
                    // Update badge in sidebar
                    const badge = document.querySelector('.sidebar-link .badge');
                    if (badge) {
                        badge.textContent = response.count;
                        badge.classList.remove('d-none');
                    }
                    
                    // Show notification
                    if (response.count > {{ $pendingCount ?? 0 }}) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Update',
                            text: 'Ada ' + response.count + ' dokumen menunggu verifikasi',
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
                        });
                    }
                }
            }
        });
    }
    
    // Auto refresh every 2 minutes
    setInterval(loadPendingCount, 120000);
</script>

<style>
    /* Additional custom styles for dashboard */
    .avatar-sm {
        width: 36px;
        height: 36px;
        font-size: 0.875rem;
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .display-6 {
        font-size: 1.75rem;
    }
    
    @media (max-width: 768px) {
        .display-6 {
            font-size: 1.5rem;
        }
        
        .custom-card {
            border-radius: 10px;
        }
        
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .display-6 {
            font-size: 1.25rem;
        }
        
        .fs-6 {
            font-size: 0.875rem !important;
        }
        
        .custom-card {
            padding: 1rem !important;
        }
    }
    
    /* Hover effect for table rows */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    /* Border radius for progress bars */
    .progress {
        border-radius: 3px;
    }
    
    .progress-bar {
        border-radius: 3px;
    }
</style>
@endpush
@endsection