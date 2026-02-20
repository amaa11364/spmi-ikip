@extends('layouts.main')

@section('title', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profil Pengguna
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/avatars/' . $user->avatar) }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle mb-3"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="avatar-circle-large bg-{{ $user->role_class }} text-white mx-auto mb-3" 
                             style="width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: bold;">
                            {{ $user->getInitials() }}
                        </div>
                    @endif
                    
                    <h5 class="fw-bold">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        <span class="badge bg-{{ $user->role_class }} text-{{ $user->role === 'verifikator' ? 'dark' : 'white' }} p-2">
                            <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : ($user->role === 'verifikator' ? 'fa-check-circle' : 'fa-user') }} me-1"></i>
                            {{ $user->role_label }}
                        </span>
                        <span class="badge bg-{{ $user->status_class }} p-2 ms-1">
                            <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                            {{ $user->status_label }}
                        </span>
                    </div>
                    
                    @if($user->id !== auth()->id())
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Pengguna
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Detail
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Nama Lengkap</strong></td>
                            <td>: {{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: {{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nomor Telepon</strong></td>
                            <td>: {{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role</strong></td>
                            <td>: 
                                <span class="badge bg-{{ $user->role_class }}">
                                    {{ $user->role_label }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Unit Kerja</strong></td>
                            <td>: {{ $user->unitKerja->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Program Studi</strong></td>
                            <td>: {{ $user->programStudi->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status Akun</strong></td>
                            <td>: 
                                <span class="badge bg-{{ $user->status_class }}">
                                    {{ $user->status_label }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Registrasi</strong></td>
                            <td>: {{ $user->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Diupdate</strong></td>
                            <td>: {{ $user->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Permissions
                    </h5>
                </div>
                <div class="card-body">
                    @php $permissions = $user->permissions ?? []; @endphp
                    
                    @if(empty($permissions) && !$user->isAdmin())
                        <p class="text-muted mb-0">Tidak ada permission khusus</p>
                    @elseif($user->isAdmin())
                        <p class="text-success mb-0">
                            <i class="fas fa-check-circle me-1"></i>Admin memiliki akses ke semua fitur
                        </p>
                    @else
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-success p-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ ucwords(str_replace('_', ' ', $permission)) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Dokumen Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recentDocuments ?? [] as $document)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <a href="#" class="text-decoration-none">{{ $document->judul }}</a>
                            </div>
                            <small class="text-muted">{{ $document->created_at->format('d/m/Y') }}</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada dokumen yang diupload</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        text-transform: uppercase;
    }
    .table-borderless td {
        padding: 0.75rem 0;
    }
</style>
@endpush