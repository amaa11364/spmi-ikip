@extends('layouts.app')

@section('title', $berita->judul . ' - Berita SPMI')

@push('styles')
<style>
    .berita-detail-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .berita-content {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }
    
    .berita-cover {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    
    .berita-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #495057;
    }
    
    .berita-meta-detail {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-brown);
    }
    
    .related-berita {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        margin-top: 3rem;
    }
    
    .related-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .related-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .related-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .related-content {
        padding: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Berita
        </a>
    </div>
    
    <!-- Berita Detail -->
    <div class="berita-content">
        <!-- Cover Image -->
        @if($berita->gambar)
        <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="berita-cover">
        @endif
        
        <!-- Title -->
        <h1 class="fw-bold mb-3">{{ $berita->judul }}</h1>
        
        <!-- Meta Information -->
        <div class="berita-meta-detail">
            <div class="row">
                <div class="col-md-4 mb-2 mb-md-0">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    <strong>Tanggal:</strong> {{ $berita->created_at->format('d F Y') }}
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <i class="fas fa-eye text-primary me-2"></i>
                    <strong>Dilihat:</strong> {{ $berita->views }} kali
                </div>
                <div class="col-md-4">
                    <i class="fas fa-user text-primary me-2"></i>
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $berita->is_published ? 'success' : 'warning' }}">
                        {{ $berita->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="berita-body">
            {!! nl2br(e($berita->konten)) !!}
        </div>
    </div>
    
    <!-- Related Berita -->
    @if($related->count() > 0)
    <div class="related-berita">
        <h4 class="fw-bold mb-3">
            <i class="fas fa-link me-2"></i>Berita Terkait
        </h4>
        <div class="row g-4">
            @foreach($related as $relatedBerita)
            <div class="col-md-4">
                <a href="{{ route('berita.show', $relatedBerita->slug) }}" class="text-decoration-none">
                    <div class="related-card">
                        <img src="{{ $relatedBerita->gambar_url }}" alt="{{ $relatedBerita->judul }}" class="related-image">
                        <div class="related-content">
                            <h6 class="fw-semibold">{{ Str::limit($relatedBerita->judul, 50) }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $relatedBerita->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Back to Top -->
    <div class="text-center mt-4">
        <a href="{{ route('landing.page') }}" class="btn btn-primary">
            <i class="fas fa-home me-2"></i>Kembali ke Beranda
        </a>
    </div>
</div>
@endsection