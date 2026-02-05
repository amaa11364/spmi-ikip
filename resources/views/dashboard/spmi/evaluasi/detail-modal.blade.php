<div class="row">
    <div class="col-md-8">
        <h4 class="text-primary">{{ $evaluasi->nama_komponen }}</h4>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-info">{{ $evaluasi->kode_evaluasi }}</span>
            <span class="badge bg-secondary">{{ $evaluasi->tahun }}</span>
            <span class="badge bg-primary">{{ $evaluasi->periode }}</span>
            <span class="badge bg-{{ $evaluasi->status_color }}">{{ $evaluasi->status_label }}</span>
            <span class="badge bg-{{ $evaluasi->status_dokumen_color }}">{{ $evaluasi->status_dokumen_label }}</span>
            <span class="badge bg-dark">{{ $allDokumen->count() }} dokumen</span>
        </div>
        
        @if($evaluasi->deskripsi)
        <div class="mb-4">
            <h6 class="text-muted mb-2">Deskripsi:</h6>
            <p class="mb-0">{{ $evaluasi->deskripsi }}</p>
        </div>
        @endif
        
        @if($evaluasi->hasil_evaluasi)
        <div class="mb-4">
            <h6 class="text-muted mb-2">Hasil Evaluasi:</h6>
            <div class="alert alert-info mb-0">
                {{ $evaluasi->hasil_evaluasi }}
            </div>
        </div>
        @endif
        
        @if($evaluasi->rekomendasi)
        <div class="mb-4">
            <h6 class="text-muted mb-2">Rekomendasi:</h6>
            <div class="alert alert-warning mb-0">
                {{ $evaluasi->rekomendasi }}
            </div>
        </div>
        @endif
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Informasi:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-user-tie me-2 text-primary"></i>
                        <strong>Penanggung Jawab:</strong> {{ $evaluasi->penanggung_jawab ?? 'Tidak ditentukan' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-building me-2 text-primary"></i>
                        <strong>Unit Kerja:</strong> {{ $evaluasi->unitKerja->nama ?? 'Tidak ada' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar me-2 text-primary"></i>
                        <strong>Tanggal Evaluasi:</strong> {{ $evaluasi->tanggal_evaluasi ? $evaluasi->tanggal_evaluasi->format('d/m/Y') : 'Belum ditentukan' }}
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Metadata:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-calendar me-2 text-primary"></i>
                        <strong>Dibuat:</strong> {{ $evaluasi->created_at->format('d/m/Y H:i') }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                        <strong>Diperbarui:</strong> {{ $evaluasi->updated_at->format('d/m/Y H:i') }}
                    </li>
                    @if($evaluasi->tanggal_selesai)
                    <li class="mb-2">
                        <i class="fas fa-check-circle me-2 text-primary"></i>
                        <strong>Selesai:</strong> {{ $evaluasi->tanggal_selesai->format('d/m/Y') }}
                    </li>
                    @endif
                </ul>
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
                <a href="{{ route('upload.spmi-evaluasi', $evaluasi->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-upload me-1"></i> Tambah Dokumen
                </a>
            </div>
        </div>
    </div>
</div>