<div class="row">
    <div class="col-md-8">
        <h4 class="text-primary">{{ $akreditasi->judul_akreditasi }}</h4>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-info">{{ $akreditasi->kode_akreditasi }}</span>
            <span class="badge bg-secondary">{{ $akreditasi->tahun }}</span>
            <span class="badge bg-{{ $akreditasi->status_color }}">
                {{ $akreditasi->status_label }}
            </span>
            <span class="badge bg-{{ $akreditasi->status_dokumen_color }}">
                Dokumen: {{ $akreditasi->status_dokumen_label }}
            </span>
            <span class="badge bg-primary">{{ $allDokumen->count() }} dokumen</span>
            @if($akreditasi->peringkat)
            <span class="badge peringkat-{{ $akreditasi->peringkat }}">{{ $akreditasi->peringkat }}</span>
            @endif
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Informasi Akreditasi:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-landmark me-2 text-primary"></i>
                        <strong>Lembaga:</strong> {{ $akreditasi->lembaga_akreditasi }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-tag me-2 text-primary"></i>
                        <strong>Jenis:</strong> {{ $akreditasi->jenis_akreditasi_label }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-certificate me-2 text-primary"></i>
                        <strong>No. Sertifikat:</strong> {{ $akreditasi->no_sertifikat ?? 'Tidak ada' }}
                    </li>
                    @if($akreditasi->skor)
                    <li class="mb-2">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        <strong>Skor:</strong> {{ $akreditasi->skor }}
                    </li>
                    @endif
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Tanggal Penting:</h6>
                <ul class="list-unstyled">
                    @if($akreditasi->tanggal_akreditasi)
                    <li class="mb-2">
                        <i class="fas fa-calendar-day me-2 text-primary"></i>
                        <strong>Tanggal Akreditasi:</strong> {{ $akreditasi->tanggal_akreditasi->format('d/m/Y') }}
                    </li>
                    @endif
                    @if($akreditasi->tanggal_berlaku)
                    <li class="mb-2">
                        <i class="fas fa-play-circle me-2 text-primary"></i>
                        <strong>Berlaku Mulai:</strong> {{ $akreditasi->tanggal_berlaku->format('d/m/Y') }}
                    </li>
                    @endif
                    @if($akreditasi->tanggal_kadaluarsa)
                    <li class="mb-2">
                        <i class="fas fa-calendar-times me-2 text-primary"></i>
                        <strong>Kadaluarsa:</strong> {{ $akreditasi->tanggal_kadaluarsa->format('d/m/Y') }}
                    </li>
                    @endif
                    <li class="mb-2">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        <strong>Masa Berlaku:</strong> {{ $akreditasi->masa_berlaku }}
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="mb-4">
            <h6 class="text-muted mb-2">Deskripsi:</h6>
            <p class="mb-0">{{ $akreditasi->deskripsi ?? 'Tidak ada deskripsi' }}</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Penanggung Jawab:</h6>
                <p class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    {{ $akreditasi->penanggung_jawab ?? 'Belum ditentukan' }}
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Unit Kerja:</h6>
                <p class="mb-0">
                    <i class="fas fa-building me-2"></i>
                    {{ $akreditasi->unitKerja->nama ?? 'Tidak ada' }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i> Dokumen Terkait ({{ $allDokumen->count() }})</h6>
            </div>
            <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                @if($allDokumen->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($allDokumen as $dokumen)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="{{ $dokumen->file_icon }} me-2"></i>
                                    <small class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $dokumen->nama_dokumen }}">
                                        {{ $dokumen->nama_dokumen }}
                                    </small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                                       class="btn btn-outline-primary" title="Download" onclick="event.stopPropagation()">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @if($dokumen->is_pdf)
                                    <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                                       class="btn btn-outline-info" title="Preview" target="_blank" onclick="event.stopPropagation()">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $dokumen->created_at->format('d/m/Y') }}
                                    • {{ $dokumen->file_size_formatted }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-excel text-muted fa-2x mb-2"></i>
                        <p class="text-muted mb-0">Belum ada dokumen</p>
                    </div>
                @endif
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('upload.spmi-akreditasi', $akreditasi->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-upload me-1"></i> Tambah Dokumen
                </a>
            </div>
        </div>
    </div>
</div>