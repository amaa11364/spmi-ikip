@extends('layouts.main')

@section('title', 'Dashboard Verifikator')

@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h3 mb-1 mb-md-2">Dashboard Verifikator</h1>
            <div class="text-muted">
                <small>Login sebagai: <strong>{{ auth()->user()->name ?? '' }}</strong> | Unit: <strong>{{ auth()->user()->unit_kerja->nama ?? auth()->user()->unit_kerja->name ?? 'Tidak ada unit' }}</strong></small>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('verifikator.dokumen.index', ['status' => 'pending']) }}" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-clipboard-list"></i>
                <span class="d-none d-md-inline">Lihat Antrian</span>
            </a>
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
                        <h2 class="fw-bold mb-2 display-6">{{ $statusStats['pending'] ?? 0 }}</h2>
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
                             style="width: {{ $totalDocuments > 0 ? ($statusStats['pending'] / $totalDocuments * 100) : 0 }}%"
                             aria-valuenow="{{ $statusStats['pending'] ?? 0 }}" 
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
                        <h2 class="fw-bold mb-2 display-6">{{ $statusStats['approved'] ?? 0 }}</h2>
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
                             style="width: {{ $totalDocuments > 0 ? ($statusStats['approved'] / $totalDocuments * 100) : 0 }}%"
                             aria-valuenow="{{ $statusStats['approved'] ?? 0 }}" 
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
                        <h2 class="fw-bold mb-2 display-6">{{ $statusStats['rejected'] ?? 0 }}</h2>
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
                             style="width: {{ $totalDocuments > 0 ? ($statusStats['rejected'] / $totalDocuments * 100) : 0 }}%"
                             aria-valuenow="{{ $statusStats['rejected'] ?? 0 }}" 
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
                        <h2 class="fw-bold mb-2 display-6">{{ $statusStats['revision'] ?? 0 }}</h2>
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
                             style="width: {{ $totalDocuments > 0 ? ($statusStats['revision'] / $totalDocuments * 100) : 0 }}%"
                             aria-valuenow="{{ $statusStats['revision'] ?? 0 }}" 
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
                        <a href="{{ route('verifikator.dokumen.index', ['status' => 'pending']) }}" 
                           class="btn btn-warning w-100 d-flex align-items-center p-2 p-md-3 h-100">
                            <div class="bg-white text-warning rounded-circle p-2 me-2 me-md-3 flex-shrink-0">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div class="text-start flex-grow-1">
                                <strong class="d-block fs-6">Review Dokumen</strong>
                                <small class="d-block text-truncate">
                                    {{ $statusStats['pending'] ?? 0 }} dokumen menunggu verifikasi
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
                        <a href="{{ route('verifikator.statistik.index') ?? '#' }}"
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

    {{-- Dokumen Perlu Verifikasi & Tahapan PPEPP --}}
    <div class="row g-4">
        {{-- Dokumen Perlu Verifikasi --}}
        <div class="col-12 col-lg-8">
            <div class="custom-card h-100 p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4">
                    <h5 class="mb-2 mb-md-0">Dokumen Perlu Verifikasi</h5>
                    <div class="d-flex">
                        <a href="{{ route('verifikator.dokumen.index', ['status' => 'pending']) }}" 
                           class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-list me-1"></i>
                            <span class="d-none d-md-inline">Lihat Semua</span>
                            <span class="d-inline d-md-none">Semua</span>
                        </a>
                    </div>
                </div>
                
                @if(isset($pendingDocuments) && $pendingDocuments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light d-none d-md-table-header-group">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Judul Dokumen</th>
                                    <th>Tahapan</th>
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
                                                    {{ Str::limit($dokumen->judul ?? $dokumen->nama_dokumen, 60) }}
                                                </strong>
                                                <div class="d-flex align-items-center">
                                                    @if($dokumen->tahapan)
                                                        <small class="text-muted me-2">
                                                            <i class="fas fa-tag me-1"></i>{{ ucfirst($dokumen->tahapan) }}
                                                        </small>
                                                    @endif
                                                    @if($dokumen->file_extension)
                                                    <small class="text-muted">
                                                        <i class="fas fa-file me-1"></i>{{ strtoupper($dokumen->file_extension) }}
                                                    </small>
                                                    @endif
                                                </div>
                                                {{-- Mobile View --}}
                                                <div class="d-flex d-md-none justify-content-between mt-2">
                                                    <div>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-user me-1"></i>
                                                            {{ Str::limit($dokumen->uploader->name ?? $dokumen->user->name ?? 'Tidak diketahui', 15) }}
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $dokumen->created_at ? $dokumen->created_at->format('d/m') : '-' }}
                                                        </small>
                                                    </div>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('verifikator.dokumen.show', $dokumen->id) }}" 
                                                                class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('verifikator.dokumen.download', $dokumen->id) }}" 
                                                                class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell align-middle">
                                            @if($dokumen->tahapan)
                                                <span class="badge bg-info">{{ ucfirst($dokumen->tahapan) }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="d-none d-md-table-cell align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <span class="fw-bold">{{ strtoupper(substr($dokumen->uploader->name ?? $dokumen->user->name ?? 'U', 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $dokumen->uploader->name ?? $dokumen->user->name ?? 'Tidak diketahui' }}</div>
                                                    <small class="text-muted">{{ $dokumen->uploader->unit_kerja ?? $dokumen->user->unit_kerja ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell align-middle">
                                            <div class="text-nowrap">
                                                <div>{{ $dokumen->created_at ? $dokumen->created_at->format('d/m/Y') : '-' }}</div>
                                                <small class="text-muted">{{ $dokumen->created_at ? $dokumen->created_at->format('H:i') : '' }}</small>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell align-middle">
                                            <div class="d-flex justify-content-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('verifikator.dokumen.show', $dokumen->id) }}" 
                                                            class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('verifikator.dokumen.download', $dokumen->id) }}" 
                                                            class="btn btn-outline-info" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-success dropdown-toggle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown"
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
        
        {{-- Statistik Per Tahapan --}}
        <div class="col-12 col-lg-4">
            <div class="custom-card h-100 p-3 p-md-4">
                <h5 class="mb-3 mb-md-4">Statistik Per Tahapan PPEPP</h5>
                
                @if(isset($tahapanStats) && count($tahapanStats) > 0)
                    @foreach($tahapanStats as $tahapan => $stats)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <span class="badge bg-{{ $stats['color'] ?? 'secondary' }} me-2">
                                    <i class="fas {{ $stats['icon'] ?? 'fa-file' }}"></i>
                                </span>
                                <strong>{{ $stats['label'] ?? ucfirst($tahapan) }}</strong>
                            </div>
                            <span class="badge bg-secondary">{{ $stats['total'] ?? 0 }} Total</span>
                        </div>
                        <div class="progress mb-2" style="height: 20px;">
                            @if(($stats['approved'] ?? 0) > 0)
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['approved'] / $stats['total'] * 100) : 0 }}%"
                                 title="Disetujui: {{ $stats['approved'] }}">
                                {{ $stats['approved'] }}
                            </div>
                            @endif
                            @if(($stats['pending'] ?? 0) > 0)
                            <div class="progress-bar bg-warning" 
                                 role="progressbar" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}%"
                                 title="Pending: {{ $stats['pending'] }}">
                                {{ $stats['pending'] }}
                            </div>
                            @endif
                            @if(($stats['revision'] ?? 0) > 0)
                            <div class="progress-bar bg-info" 
                                 role="progressbar" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['revision'] / $stats['total'] * 100) : 0 }}%"
                                 title="Revisi: {{ $stats['revision'] }}">
                                {{ $stats['revision'] }}
                            </div>
                            @endif
                            @if(($stats['rejected'] ?? 0) > 0)
                            <div class="progress-bar bg-danger" 
                                 role="progressbar" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['rejected'] / $stats['total'] * 100) : 0 }}%"
                                 title="Ditolak: {{ $stats['rejected'] }}">
                                {{ $stats['rejected'] }}
                            </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>✅ {{ $stats['approved'] ?? 0 }}</span>
                            <span>⏳ {{ $stats['pending'] ?? 0 }}</span>
                            <span>📝 {{ $stats['revision'] ?? 0 }}</span>
                            <span>❌ {{ $stats['rejected'] ?? 0 }}</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data statistik</p>
                    </div>
                @endif
                
                <hr class="my-4">
                
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
    // Simple verification via AJAX
    function simpleVerification(id, action) {
        let message = '';
        let title = '';
        
        switch(action) {
            case 'approved':
                title = 'Setujui Dokumen';
                message = 'Apakah Anda yakin ingin menyetujui dokumen ini?';
                break;
            case 'rejected':
                title = 'Tolak Dokumen';
                message = 'Apakah Anda yakin ingin menolak dokumen ini?';
                break;
            case 'revision':
                title = 'Minta Revisi';
                message = 'Apakah Anda yakin ingin meminta revisi dokumen ini?';
                break;
        }
        
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'approved' ? '#28a745' : (action === 'revision' ? '#ffc107' : '#dc3545'),
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to verification page with action
                window.location.href = "{{ url('verifikator/dokumen') }}/" + id + "/" + action;
            }
        });
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
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
    
    /* Progress bar styling */
    .progress {
        border-radius: 3px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 3px;
        font-size: 10px;
        line-height: 20px;
    }
</style>
@endpush
@endsection