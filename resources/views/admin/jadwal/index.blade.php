@extends('layouts.main')

@section('title', 'Kelola Jadwal')

@section('content')
<div class="container-fluid px-4">
    {{-- Header dengan tombol tambah --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h4 class="mb-0">
            <i class="fas fa-calendar-alt me-2 text-primary"></i>
            Kelola Jadwal
        </h4>
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Jadwal
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Kegiatan</th>
                            <th width="120">Tanggal</th>
                            <th width="100">Waktu</th>
                            <th width="150">Tempat</th>
                            <th width="120">Penanggung Jawab</th>
                            <th width="100">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge me-2" style="background-color: {{ $jadwal->warna ?? '#0d6efd' }}; width: 8px; height: 8px; padding: 0;">&nbsp;</span>
                                    <div>
                                        <strong>{{ $jadwal->kegiatan }}</strong>
                                        @if($jadwal->deskripsi)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($jadwal->deskripsi, 40) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $jadwal->tanggal->format('d/m/Y') }}</span>
                                <br>
                                <small class="text-muted">{{ $jadwal->hari }}</small>
                            </td>
                            <td>{{ $jadwal->waktu_formatted }}</td>
                            <td>{{ $jadwal->tempat ?? '-' }}</td>
                            <td>{{ $jadwal->penanggung_jawab ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $jadwal->status_class }} px-3 py-2">
                                    {{ $jadwal->status_label }}
                                </span>
                                @if(!$jadwal->is_active)
                                    <br>
                                    <small class="text-danger">(Tidak Aktif)</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.jadwal.edit', $jadwal) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger" 
                                                title="Hapus"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('landing.page') }}#jadwal" 
                                       target="_blank" 
                                       class="btn btn-outline-info" 
                                       title="Lihat di Landing Page"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                                <p class="mb-2">Belum ada jadwal</p>
                                <a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah Jadwal Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($jadwals->hasPages())
            <div class="d-flex justify-content-end p-3 border-top">
                {{ $jadwals->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endpush
@endsection