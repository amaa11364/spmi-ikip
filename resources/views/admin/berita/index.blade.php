@extends('layouts.main')

@section('title', 'Berita Terbaru')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('landing.page') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Berita</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-dark mb-3">Berita Terbaru</h1>
            <p class="lead text-muted">Informasi dan kegiatan terbaru seputar SPMI</p>
            <div class="divider mx-auto" style="width: 80px; height: 4px; background: linear-gradient(135deg, #996600, #cc9900);"></div>
        </div>
    </div>

    <!-- Berita Grid -->
    @if($beritas->count() > 0)
        <div class="row g-4">
            @foreach($beritas as $berita)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('berita.publik.show', $berita->id) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm berita-card">
                        <!-- Gambar -->
                        @if($berita->gambar)
                        <img src="{{ asset('storage/' . $berita->gambar) }}" 
                             class="card-img-top berita-image" 
                             alt="{{ $berita->judul }}"
                             style="height: 200px; object-fit: cover;">
                        @else
                        <div class="berita-image bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-newspaper fa-4x text-muted opacity-50"></i>
                        </div>
                        @endif

                        <!-- Content -->
                        <div class="card-body">
                            <h5 class="card-title text-dark fw-semibold mb-2" style="line-height: 1.4;">
                                {{ Str::limit($berita->judul, 60) }}
                            </h5>
                            
                            <!-- Cuplikan isi berita (limit karakter) -->
                            <p class="card-text text-muted mb-3" style="font-size: 0.95rem;">
                                {{ Str::limit(strip_tags($berita->isi), 120) }}
                            </p>
                            
                            <!-- Meta info -->
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <span>{{ $berita->created_at->format('d M Y') }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-eye me-1"></i>
                                <span>{{ $berita->views }} dilihat</span>
                            </div>
                            
                            <!-- Tombol Baca Selengkapnya -->
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-medium" style="color: #996600 !important;">
                                    Baca Selengkapnya 
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($beritas->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $beritas->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-5x text-muted mb-4 opacity-50"></i>
            <h4 class="text-muted">Belum ada berita</h4>
            <p class="text-muted">Berita akan segera hadir. Silakan kunjungi kembali nanti.</p>
        </div>
    @endif
</div>

<style>
.berita-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
}

.berita-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
}

.berita-card:hover .text-primary {
    color: #b37400 !important;
}

.berita-image {
    transition: transform 0.5s ease;
}

.berita-card:hover .berita-image {
    transform: scale(1.05);
}

.divider {
    margin-top: 1rem;
    margin-bottom: 2rem;
}

/* Custom pagination style */
.pagination {
    gap: 5px;
}

.page-link {
    border-radius: 8px;
    color: #996600;
    border: 1px solid #e9ecef;
    padding: 0.5rem 1rem;
}

.page-link:hover {
    background-color: #996600;
    color: white;
    border-color: #996600;
}

.page-item.active .page-link {
    background-color: #996600;
    border-color: #996600;
}
</style>
@endsection