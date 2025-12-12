@extends('layouts.app')

@section('title', 'Berita - SPMI Digital')

@push('styles')
<style>
    .berita-page-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 3rem;
    }
    
    .berita-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    @media (max-width: 768px) {
        .berita-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="berita-page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-2">
                    <i class="fas fa-newspaper me-2"></i>Berita SPMI
                </h1>
                <p class="mb-0">Informasi dan kegiatan terbaru dari Sistem Penjaminan Mutu Internal</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('landing.page') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    
    <!-- Berita Grid -->
    @if($beritas->count() > 0)
        <div class="berita-grid mb-4">
            @foreach($beritas as $berita)
            <a href="{{ route('berita.show', $berita->slug) }}" class="text-decoration-none">
                <div class="berita-card">
                    <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="berita-image">
                    <div class="berita-content">
                        <h3 class="berita-title">{{ Str::limit($berita->judul, 70) }}</h3>
                        <p class="berita-excerpt">{{ $berita->excerpt }}</p>
                        <div class="berita-meta">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $berita->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $beritas->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-5x text-muted mb-3"></i>
            <h3 class="text-muted">Belum ada berita</h3>
            <p class="text-muted">Berita akan segera ditambahkan</p>
            <a href="{{ route('landing.page') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
            </a>
        </div>
    @endif
</div>
@endsection