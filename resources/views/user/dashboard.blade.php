@extends('layouts.main')

@section('title', 'Dashboard User')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Dashboard User</h1>
                    <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="text-end me-3">
                        <div class="text-xs text-muted">Role</div>
                        <div class="fw-bold">{{ Auth::user()->getRoleLabelAttribute() }}</div>
                    </div>
                    <div class="text-end">
                        <div class="text-xs text-muted">Unit Kerja</div>
                        <div class="fw-bold">{{ Auth::user()->unitKerja->nama_unit_kerja ?? 'Tidak ada' }}</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.upload-dokumen.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Upload Dokumen
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Dokumen Saya
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['my_documents'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Penyimpanan Digunakan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['storage_used'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hdd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Verifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending_approvals'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Upload Terbaru
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['recent_uploads']->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-upload fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Uploads -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Upload Terbaru</h6>
                    <a href="{{ route('dokumen-saya.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($statistics['recent_uploads']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Nama Dokumen</th>
                                        <th>Jenis Upload</th>
                                        <th>Status</th>
                                        <th>Tanggal Upload</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statistics['recent_uploads'] as $dokumen)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                               <i class="fas fa-file me-2 text-primary"></i>
                                                <div>
                                                    <div class="fw-bold">{{ $dokumen->nama_dokumen }}</div>
                                                    <small class="text-muted">{{ $dokumen->deskripsi ?? 'Tidak ada deskripsi' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $dokumen->jenis_upload }}</span>
                                        </td>
                                        <td>
                                            @if($dokumen->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($dokumen->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif($dokumen->status == 'revision')
                                                <span class="badge bg-warning">Revisi</span>
                                            @else
                                                <span class="badge bg-warning">Menunggu</span>
                                            @endif
                                        </td>
                                        <td>{{ $dokumen->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" class="btn btn-sm btn-outline-info" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" class="btn btn-sm btn-outline-success" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada dokumen</h5>
                            <p class="text-gray-400 mb-3">Mulai upload dokumen SPMI Anda</p>
                            <a href="{{ route('user.upload-dokumen.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Upload Dokumen Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('upload-dokumen.create') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-upload fa-2x mb-2"></i>
                                <span>Upload Dokumen</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dokumen-saya.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <span>Dokumen Saya</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-user-edit fa-2x mb-2"></i>
                                <span>Edit Profil</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('search.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <span>Cari Dokumen</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    border-radius: 0.75rem 0.75rem 0 0 !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.btn-group .btn {
    border-radius: 0.375rem !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush
