@extends('layouts.main')

@section('title', $berita->judul)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('landing.page') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Berita</a></li>
            <li class="breadcrumb-item active">{{ $berita->judul }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm">
                @if($berita->gambar)
                <img src="{{ asset('storage/' . $berita->gambar) }}" 
                     class="card-img-top" 
                     alt="{{ $berita->judul }}"
                     style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <h1 class="card-title h2 mb-3">{{ $berita->judul }}</h1>
                    
                    <div class="d-flex text-muted mb-4">
                        <small class="me-3">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $berita->created_at->format('d F Y') }}
                        </small>
                        <small class="me-3">
                            <i class="fas fa-eye me-1"></i>
                            {{ $berita->formatted_views ?? $berita->views }} x dilihat
                        </small>
                        @if($berita->user)
                        <small>
                            <i class="fas fa-user me-1"></i>
                            {{ $berita->user->name }}
                        </small>
                        @endif
                    </div>
                    
                    <div class="berita-content">
                        {!! nl2br(e($berita->isi)) !!}
                    </div>
                </div>
            </article>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Berita Lainnya</h5>
                </div>
                <div class="card-body">
                    @forelse($beritaLainnya as $item)
                    <div class="mb-3 pb-3 border-bottom">
                        <a href="{{ route('berita.show', $item->slug) }}" class="text-decoration-none">
                            <h6 class="text-dark">{{ $item->judul }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $item->created_at->format('d/m/Y') }}
                            </small>
                        </a>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Tidak ada berita lainnya</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection