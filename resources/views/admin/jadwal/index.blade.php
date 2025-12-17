{{-- resources/views/admin/jadwal/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Kelola Jadwal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-calendar-alt me-2"></i>Kelola Jadwal
        </h4>
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="100">Tanggal</th>
                            <th>Kegiatan</th>
                            <th width="100">Waktu</th>
                            <th width="120">Tempat</th>
                            <th width="100">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                        @forelse($jadwals as $jadwal)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $jadwal->tanggal->format('d/m/Y') }}</td>
    <td>
        <strong>{{ $jadwal->kegiatan }}</strong>
    </td>
    <td>{{ $jadwal->waktu ? $jadwal->waktu->format('H:i') : '-' }}</td>
    <td>
        <span class="badge bg-success">Active</span>
    </td>
    <td>
        <!-- Hanya lihat di landing -->
        <a href="{{ route('landing.page') }}" 
           target="_blank" 
           class="btn btn-sm btn-outline-info" 
           title="Lihat di Landing">
            <i class="fas fa-eye"></i>
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-muted py-4">
        <i class="fas fa-calendar-times fa-2x mb-3"></i>
        <p>Belum ada jadwal</p>
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-primary">Tambah Jadwal</a>
    </td>
</tr>

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jadwal->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $jadwal->kegiatan }}</strong>
                                @if($jadwal->deskripsi)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($jadwal->deskripsi, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $jadwal->waktu->format('H:i') }}</td>
                            <td>{{ $jadwal->tempat ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $jadwal->status_class }}">
                                    {{ $jadwal->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.jadwal.edit', $jadwal) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Hapus jadwal ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                <p>Belum ada jadwal</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($jadwals->hasPages())
            <div class="mt-3">
                {{ $jadwals->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection