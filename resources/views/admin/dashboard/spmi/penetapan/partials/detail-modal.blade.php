<div class="row">
    <div class="col-md-8">
        <h4 class="text-primary">{{ $penetapan->nama_komponen }}</h4>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-info">{{ $penetapan->kode_penetapan }}</span>
            <span class="badge bg-secondary">{{ $penetapan->tahun }}</span>
            <span class="badge bg-{{ $penetapan->status == 'aktif' ? 'success' : ($penetapan->status == 'nonaktif' ? 'danger' : 'warning') }}">
                {{ ucfirst($penetapan->status) }}
            </span>
            <span class="badge bg-{{ $penetapan->status_dokumen == 'valid' ? 'success' : ($penetapan->status_dokumen == 'belum_valid' ? 'danger' : 'warning') }}">
                Dokumen: {{ ucfirst(str_replace('_', ' ', $penetapan->status_dokumen)) }}
            </span>
            <span class="badge bg-primary">{{ $allDokumen->count() }} dokumen</span>
        </div>
        
        <div class="mb-4">
            <h6 class="text-muted mb-2">Deskripsi:</h6>
            <p class="mb-0">{{ $penetapan->deskripsi ?? 'Tidak ada deskripsi' }}</p>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Informasi:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-user-tie me-2 text-primary"></i>
                        <strong>Penanggung Jawab:</strong> {{ $penetapan->penanggung_jawab ?? 'Tidak ditentukan' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-building me-2 text-primary"></i>
                        <strong>Unit Kerja:</strong> {{ $penetapan->unitKerja->nama ?? 'Tidak ada' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        <strong>IKU:</strong> {{ $penetapan->iku->nama ?? 'Tidak ada' }}
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Metadata:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-calendar me-2 text-primary"></i>
                        <strong>Dibuat:</strong> {{ $penetapan->created_at->format('d/m/Y H:i') }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                        <strong>Diperbarui:</strong> {{ $penetapan->updated_at->format('d/m/Y H:i') }}
                    </li>
                    @if($penetapan->tanggal_review)
                    <li class="mb-2">
                        <i class="fas fa-search me-2 text-primary"></i>
                        <strong>Review Terakhir:</strong> {{ $penetapan->tanggal_review->format('d/m/Y') }}
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
                <a href="{{ route('upload.spmi-penetapan', $penetapan->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-upload me-1"></i> Tambah Dokumen
                </a>
            </div>
        </div>
    </div>
</div>