@extends('layouts.main')

@section('title', $berita->judul)

@section('meta')
<meta property="og:title" content="{{ $berita->judul }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($berita->isi), 200) }}">
@if($berita->gambar)
<meta property="og:image" content="{{ asset('storage/' . $berita->gambar) }}">
@endif
@endsection

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('landing.page') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('berita.publik.index') }}">Berita</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($berita->judul, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm mb-4">
                <!-- Featured Image -->
                @if($berita->gambar)
                <img src="{{ asset('storage/' . $berita->gambar) }}" 
                     class="card-img-top" 
                     alt="{{ $berita->judul }}"
                     style="max-height: 450px; width: 100%; object-fit: cover; border-radius: 12px 12px 0 0;">
                @endif
                
                <div class="card-body p-4">
                    <!-- Title -->
                    <h1 class="card-title h2 fw-bold text-dark mb-3">{{ $berita->judul }}</h1>
                    
                    <!-- Meta Information -->
                    <div class="d-flex flex-wrap align-items-center text-muted mb-4 pb-3 border-bottom">
                        <div class="me-4 mb-2">
                            <i class="fas fa-calendar-alt me-2" style="color: #996600;"></i>
                            <span>{{ $berita->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="me-4 mb-2">
                            <i class="fas fa-eye me-2" style="color: #996600;"></i>
                            <span>{{ number_format($berita->views) }} x dilihat</span>
                        </div>
                        @if($berita->user)
                        <div class="mb-2">
                            <i class="fas fa-user me-2" style="color: #996600;"></i>
                            <span>{{ $berita->user->name }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- CEK APAKAH ADA LINK EKSTERNAL -->
                    @if($berita->link)
                        <!-- TAMPILKAN POPUP CARD UNTUK LINK EKSTERNAL -->
                        <div class="text-center py-4">
                            <div class="external-link-card p-5 mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 16px; border: 2px dashed #996600;">
                                <div class="mb-4">
                                    <i class="fas fa-external-link-alt fa-4x" style="color: #996600;"></i>
                                </div>
                                <h4 class="fw-bold mb-3" style="color: #333;">Berita Eksternal</h4>
                                <p class="text-muted mb-4">
                                    Berita ini berasal dari sumber eksternal. Klik tombol di bawah untuk membaca artikel lengkap.
                                </p>
                                
                                <!-- Cuplikan isi berita -->
                                @if($berita->isi)
                                <div class="bg-white p-4 rounded-3 mb-4 text-start">
                                    <p class="mb-0 fst-italic text-secondary">
                                        "{{ Str::limit(strip_tags($berita->isi), 200) }}"
                                    </p>
                                </div>
                                @endif
                                
                                <div class="d-grid gap-3">
                                    <a href="{{ $berita->link }}" 
                                       target="_blank" 
                                       class="btn btn-primary btn-lg">
                                        <i class="fas fa-external-link-alt me-2"></i>
                                        Baca Selengkapnya di Sumber Eksternal
                                    </a>
                                    <a href="{{ route('berita.publik.index') }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Kembali ke Daftar Berita
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Peringatan Keamanan -->
                            <div class="alert alert-warning mt-3 text-start" style="background-color: #fff3cd; border-color: #ffc107;">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-shield-alt fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">⚠️ Perhatian</h6>
                                        <p class="mb-0 small">
                                            Anda akan diarahkan ke situs eksternal. Kami tidak bertanggung jawab atas konten di luar website ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- TIDAK ADA LINK - TAMPILKAN KONTEN BIASA -->
                        <div class="berita-content">
                            {!! nl2br(e($berita->isi)) !!}
                        </div>
                        
                        <!-- Share Buttons (hanya tampil jika tidak ada link eksternal) -->
                        <div class="mt-5 pt-3 border-top">
                            <h6 class="fw-semibold mb-3">Bagikan Artikel:</h6>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                   style="border-color: #3b5998; color: #3b5998;">
                                    <i class="fab fa-facebook-f me-1"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($berita->judul) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-info btn-sm rounded-pill px-3"
                                   style="border-color: #1da1f2; color: #1da1f2;">
                                    <i class="fab fa-twitter me-1"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . request()->fullUrl()) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-success btn-sm rounded-pill px-3"
                                   style="border-color: #25d366; color: #25d366;">
                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </article>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Berita Terkait -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-newspaper me-2" style="color: #996600;"></i>
                        Berita Lainnya
                    </h5>
                </div>
                <div class="card-body p-4 pt-2">
                    @forelse($beritaLainnya as $item)
                    <div class="d-flex align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <!-- Thumbnail kecil -->
                        <div class="flex-shrink-0 me-3">
                            @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 alt="thumb" 
                                 width="60" 
                                 height="60" 
                                 style="object-fit: cover; border-radius: 8px;">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; border-radius: 8px;">
                                <i class="fas fa-newspaper text-muted opacity-50"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <a href="{{ route('berita.publik.show', $item->id) }}" 
                               class="text-decoration-none">
                                <h6 class="text-dark fw-semibold mb-1" style="font-size: 0.95rem;">
                                    {{ Str::limit($item->judul, 45) }}
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1" style="font-size: 0.75rem;"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </small>
                                @if($item->link)
                                <span class="badge bg-warning text-dark ms-2" style="font-size: 0.65rem;">
                                    <i class="fas fa-external-link-alt"></i> Eksternal
                                </span>
                                @endif
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">Tidak ada berita lainnya</p>
                    @endforelse
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('berita.publik.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-1"></i>Lihat Semua Berita
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #996600;"></i>
                        Informasi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clock me-1" style="color: #996600;"></i>
                        Terakhir diperbarui: {{ $berita->updated_at->format('d F Y H:i') }}
                    </p>
                    @if($berita->views > 0)
                    <p class="text-muted small mb-0 mt-2">
                        <i class="fas fa-eye me-1" style="color: #996600;"></i>
                        Telah dilihat {{ number_format($berita->views) }} kali
                    </p>
                    @endif
                    @if($berita->link)
                    <p class="text-muted small mb-0 mt-2">
                        <i class="fas fa-external-link-alt me-1" style="color: #996600;"></i>
                        <span class="badge bg-warning text-dark">Berita Eksternal</span>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.berita-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.berita-content p {
    margin-bottom: 1.5rem;
}

/* Styling untuk breadcrumb */
.breadcrumb {
    background-color: #f8f9fa;
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

.breadcrumb a {
    color: #996600;
    text-decoration: none;
}

.breadcrumb a:hover {
    color: #b37400;
    text-decoration: underline;
}

/* Card hover effect untuk berita terkait */
.flex-grow-1 a:hover h6 {
    color: #996600 !important;
    transition: color 0.2s ease;
}

/* Animasi untuk external link card */
.external-link-card {
    transition: all 0.3s ease;
    animation: fadeInUp 0.5s ease;
}

.external-link-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(153, 102, 0, 0.2) !important;
    border-style: solid !important;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection