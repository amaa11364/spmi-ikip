@if($jadwals->count() > 0)
<div class="jadwal-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col text-center">
                <h2 class="fw-bold">Jadwal Kegiatan</h2>
                <p class="text-muted">Kegiatan yang akan datang di lingkungan SPMI</p>
            </div>
        </div>
        
        <div class="row">
            @foreach($jadwals as $jadwal)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="date-box text-center me-3" 
                                 style="background-color: {{ $jadwal->warna ?? '#0d6efd' }}20; 
                                        color: {{ $jadwal->warna ?? '#0d6efd' }}; 
                                        min-width: 60px; 
                                        padding: 10px 5px; 
                                        border-radius: 10px;">
                                <div class="day fw-bold fs-4">{{ $jadwal->tanggal->format('d') }}</div>
                                <div class="month">{{ $jadwal->tanggal->format('M') }}</div>
                            </div>
                            <div>
                                <span class="badge bg-{{ $jadwal->status_class }} mb-2">
                                    {{ $jadwal->status_label }}
                                </span>
                                <h6 class="mb-0">{{ $jadwal->kegiatan }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pt-2">
                        <div class="info-item mb-2">
                            <i class="fas fa-clock text-primary me-2" style="width: 20px;"></i>
                            <span class="text-muted">{{ $jadwal->waktu_formatted }} WIB</span>
                        </div>
                        @if($jadwal->tempat)
                        <div class="info-item mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2" style="width: 20px;"></i>
                            <span class="text-muted">{{ $jadwal->tempat }}</span>
                        </div>
                        @endif
                        @if($jadwal->deskripsi)
                        <p class="text-muted small mt-3 mb-0">
                            {{ Str::limit($jadwal->deskripsi, 80) }}
                        </p>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-4 px-4">
                        <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailJadwal{{ $jadwal->id }}">
                            Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Modal Detail --}}
            <div class="modal fade" id="detailJadwal{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: {{ $jadwal->warna ?? '#0d6efd' }}; color: white;">
                            <h5 class="modal-title">
                                <i class="fas fa-calendar-alt me-2"></i>Detail Kegiatan
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <h4 class="mb-3">{{ $jadwal->kegiatan }}</h4>
                            
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Hari/Tanggal</div>
                                <div class="col-8">{{ $jadwal->tanggal->translatedFormat('l, d F Y') }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Waktu</div>
                                <div class="col-8">{{ $jadwal->waktu_formatted }} WIB</div>
                            </div>
                            
                            @if($jadwal->tempat)
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Tempat</div>
                                <div class="col-8">{{ $jadwal->tempat }}</div>
                            </div>
                            @endif
                            
                            @if($jadwal->penanggung_jawab)
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Penanggung Jawab</div>
                                <div class="col-8">{{ $jadwal->penanggung_jawab }}</div>
                            </div>
                            @endif
                            
                            @if($jadwal->deskripsi)
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Deskripsi</div>
                                <div class="col-8">{{ $jadwal->deskripsi }}</div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Status</div>
                                <div class="col-8">
                                    <span class="badge bg-{{ $jadwal->status_class }}">
                                        {{ $jadwal->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row mt-4">
            <div class="col text-center">
                <a href="{{ route('landing.jadwal') }}" class="btn btn-outline-primary px-4">
                    Lihat Semua Jadwal <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-shadow {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}
.date-box {
    transition: background-color 0.3s;
}
.card:hover .date-box {
    background-color: {{ $jadwal->warna ?? '#0d6efd' }}40 !important;
}
</style>
@endpush
@endif