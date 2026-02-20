@extends('layouts.main')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    @include('components.breadcrumb')
    
    <!-- Header dengan Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-users me-2"></i>Kelola Pengguna
            </h4>
            <small class="text-muted">Manajemen akun pengguna SPMI</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Daftar Pengguna
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Filter Role</h6></li>
                                <li><a class="dropdown-item filter-role" href="#" data-role="all">Semua Role</a></li>
                                <li><a class="dropdown-item filter-role" href="#" data-role="admin">Administrator</a></li>
                                <li><a class="dropdown-item filter-role" href="#" data-role="verifikator">Verifikator</a></li>
                                <li><a class="dropdown-item filter-role" href="#" data-role="user">Pengguna Biasa</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Filter Status</h6></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="all">Semua Status</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="active">Aktif</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="inactive">Tidak Aktif</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshTable" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="exportData" title="Export">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th width="50">#</th>
                            <th>Nama & Kontak</th>
                            <th>Role</th>
                            <th>Unit Kerja</th>
                            <th>Status</th>
                            <th width="120">Terdaftar</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                </div>
                            </td>
                            <td>{{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 bg-{{ $user->role_class }} text-white" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                        {{ $user->getInitials() }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope fa-xs"></i> {{ $user->email }}
                                            @if($user->phone)
                                                <br><i class="fas fa-phone fa-xs"></i> {{ $user->phone }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role_class }} text-{{ $user->role === 'verifikator' ? 'dark' : 'white' }}">
                                    <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : ($user->role === 'verifikator' ? 'fa-check-circle' : 'fa-user') }} me-1"></i>
                                    {{ $user->role_label }}
                                </span>
                            </td>
                            <td>
                                @if($user->unitKerja)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                        {{ $user->unitKerja->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->status_class }}">
                                    <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                    {{ $user->status_label }}
                                </span>
                            </td>
                            <td>
                                <small>
                                    <i class="fas fa-calendar-alt me-1"></i>{{ $user->created_at->format('d/m/Y') }}
                                    <br>
                                    <i class="fas fa-clock me-1"></i>{{ $user->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="btn btn-outline-info" 
                                       title="Lihat Detail"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$user->isAdmin() || ($user->isAdmin() && auth()->id() !== $user->id))
                                        <button type="button" 
                                                class="btn btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}" 
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                data-bs-toggle="tooltip"
                                                onclick="{{ $user->is_active ? 'deactivateUser' : 'activateUser' }}({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    @endif
                                    @if(!$user->isAdmin() && $user->id !== auth()->id())
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="Hapus"
                                                data-bs-toggle="tooltip"
                                                onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p class="mb-0">Belum ada data pengguna</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i>Tambah Pengguna
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages() || method_exists($users, 'total') && $users->total() > 0)
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} 
                        dari {{ $users->total() }} data
                    </small>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        text-transform: uppercase;
    }
    .table > :not(caption) > * > * {
        vertical-align: middle;
    }
    .btn-group-sm > .btn, .btn-sm {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Select All checkbox
    $('#selectAll').on('change', function() {
        $('.user-checkbox:not(:disabled)').prop('checked', $(this).prop('checked'));
    });

    // Individual checkbox
    $('.user-checkbox').on('change', function() {
        if ($('.user-checkbox:checked').length === $('.user-checkbox').length) {
            $('#selectAll').prop('checked', true).prop('indeterminate', false);
        } else if ($('.user-checkbox:checked').length === 0) {
            $('#selectAll').prop('checked', false).prop('indeterminate', false);
        } else {
            $('#selectAll').prop('indeterminate', true);
        }
    });

    // Filter by role
    $('.filter-role').on('click', function(e) {
        e.preventDefault();
        const role = $(this).data('role');
        filterUsers(role, null);
    });

    // Filter by status
    $('.filter-status').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        filterUsers(null, status);
    });

    function filterUsers(role, status) {
        let url = new URL(window.location.href);
        if (role && role !== 'all') {
            url.searchParams.set('role', role);
        } else {
            url.searchParams.delete('role');
        }
        if (status && status !== 'all') {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }

    // Refresh table
    $('#refreshTable').on('click', function() {
        window.location.reload();
    });

    // Export data
    $('#exportData').on('click', function() {
        window.location.href = '{{ route("admin.users.index") }}?export=excel';
    });
});

// Fungsi untuk aktivasi user
function activateUser(userId, userName) {
    Swal.fire({
        title: 'Aktifkan Pengguna?',
        text: `Apakah Anda yakin ingin mengaktifkan akun "${userName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Aktifkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/users/' + userId + '/activate',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Akun pengguna telah diaktifkan.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Gagal!',
                        xhr.responseJSON?.error || 'Terjadi kesalahan saat mengaktifkan akun.',
                        'error'
                    );
                }
            });
        }
    });
}

// Fungsi untuk nonaktifkan user
function deactivateUser(userId, userName) {
    Swal.fire({
        title: 'Nonaktifkan Pengguna?',
        text: `Apakah Anda yakin ingin menonaktifkan akun "${userName}"? Pengguna tidak akan bisa login.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Nonaktifkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/users/' + userId + '/deactivate',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Akun pengguna telah dinonaktifkan.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Gagal!',
                        xhr.responseJSON?.error || 'Terjadi kesalahan saat menonaktifkan akun.',
                        'error'
                    );
                }
            });
        }
    });
}

// Fungsi untuk hapus user
function deleteUser(userId, userName) {
    Swal.fire({
        title: 'Hapus Pengguna?',
        text: `Apakah Anda yakin ingin menghapus akun "${userName}"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/users/' + userId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Akun pengguna telah dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Gagal!',
                        xhr.responseJSON?.error || 'Terjadi kesalahan saat menghapus akun.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
@endpush