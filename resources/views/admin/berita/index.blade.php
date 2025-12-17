{{-- resources/views/admin/berita/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Kelola Berita')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    @include('components.breadcrumb')
    
    <!-- Header dengan Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-newspaper me-2"></i>Kelola Berita
            </h4>
            <small class="text-muted">Manajemen berita dan artikel SPMI</small>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Berita
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
                            <th>Judul</th>
                            <th width="100">Status</th>
                            <th width="120">Tanggal</th>
                            <th width="80">Views</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beritas as $berita)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $berita->judul }}</strong>
                                <br>
                                <small class="text-muted">Slug: {{ $berita->slug }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $berita->status_class }}">
                                    {{ $berita->status }}
                                </span>
                            </td>
                            <td>{{ $berita->created_at->format('d/m/Y') }}</td>
                            <td>{{ $berita->views }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('berita.show', $berita->slug) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-info" 
                                       title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.berita.edit', $berita) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.berita.destroy', $berita) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Hapus berita ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-newspaper fa-2x mb-3"></i>
                                <p>Belum ada berita</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($beritas->hasPages())
            <div class="mt-3">
                {{ $beritas->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection