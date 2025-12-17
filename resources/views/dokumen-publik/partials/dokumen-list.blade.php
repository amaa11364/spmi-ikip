@if($dokumens->count() > 0)
    @foreach($dokumens as $dokumen)
        <!-- Desktop Table View -->
        <tr class="desktop-row">
            <td class="file-icon-cell">
                <i class="{{ $dokumen->file_icon }} file-icon"></i>
            </td>
            <td>
                <div class="document-name">{{ $dokumen->nama_dokumen }}</div>
                <div class="document-type">{{ $dokumen->jenis_dokumen }}</div>
            </td>
            <td>{{ $dokumen->unitKerja->nama ?? '-' }}</td>
            <td>
                @if($dokumen->iku)
                <span class="iku-badge" title="{{ $dokumen->iku->nama }}">
                    {{ $dokumen->iku->kode }}
                </span>
                @else
                <span class="text-muted">-</span>
                @endif
            </td>
            <td>{{ $dokumen->file_size_formatted }}</td>
            <td>{{ $dokumen->uploader->name ?? '-' }}</td>
            <td>{{ $dokumen->upload_time_ago }}</td>
            <td class="actions-cell">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary" 
                            data-bs-toggle="modal" data-bs-target="#detailModal{{ $dokumen->id }}">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    @if($dokumen->is_pdf)
                    <button type="button" class="btn btn-outline-info require-login" 
                            data-dokumen-id="{{ $dokumen->id }}" data-action="preview">
                        <i class="fas fa-eye"></i>
                    </button>
                    @endif
                    <button type="button" class="btn btn-outline-success require-login" 
                            data-dokumen-id="{{ $dokumen->id }}" data-action="download">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </td>
        </tr>

        <!-- Mobile Card View -->
        <div class="mobile-card">
            <div class="mobile-card-header">
                <i class="{{ $dokumen->file_icon }} mobile-file-icon"></i>
                <div class="mobile-document-info">
                    <h6 class="document-name mb-1">{{ $dokumen->nama_dokumen }}</h6>
                    <small class="document-type d-block">{{ $dokumen->jenis_dokumen }}</small>
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class="fas fa-folder me-1"></i>{{ $dokumen->unitKerja->nama ?? '-' }}
                        </small>
                        @if($dokumen->iku)
                        <small class="text-muted ms-2">
                            <i class="fas fa-chart-line me-1"></i>{{ $dokumen->iku->kode }}
                        </small>
                        @endif
                    </div>
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>{{ $dokumen->uploader->name ?? '-' }}
                        </small>
                        <small class="text-muted ms-2">
                            <i class="fas fa-clock me-1"></i>{{ $dokumen->upload_time_ago }}
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="mobile-actions">
                <button type="button" class="btn btn-outline-primary btn-sm" 
                        data-bs-toggle="modal" data-bs-target="#detailModal{{ $dokumen->id }}">
                    <i class="fas fa-info-circle"></i>
                </button>
                @if($dokumen->is_pdf)
                <button type="button" class="btn btn-outline-info btn-sm require-login" 
                        data-dokumen-id="{{ $dokumen->id }}" data-action="preview">
                    <i class="fas fa-eye"></i>
                </button>
                @endif
                <button type="button" class="btn btn-outline-success btn-sm require-login" 
                        data-dokumen-id="{{ $dokumen->id }}" data-action="download">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade dynamic-modal" id="detailModal{{ $dokumen->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle me-2"></i>Detail Dokumen
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <i class="{{ $dokumen->file_icon }} fa-4x text-primary"></i>
                            </div>
                            <div class="col-md-9">
                                <h5 class="fw-bold">{{ $dokumen->nama_dokumen }}</h5>
                                <p class="text-muted">{{ $dokumen->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-folder me-1"></i>
                                            <strong>Unit Kerja:</strong><br>
                                            {{ $dokumen->unitKerja->nama ?? '-' }}
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        @if($dokumen->iku)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-chart-line me-1"></i>
                                            <strong>IKU:</strong><br>
                                            {{ $dokumen->iku->kode }} - {{ $dokumen->iku->nama }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-file me-1"></i>
                                            <strong>Ukuran:</strong><br>
                                            {{ $dokumen->file_size_formatted }}
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-user me-1"></i>
                                            <strong>Uploader:</strong><br>
                                            {{ $dokumen->uploader->name ?? '-' }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-calendar me-1"></i>
                                            <strong>Tanggal Upload:</strong><br>
                                            {{ $dokumen->created_at->format('d M Y') }}
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-clock me-1"></i>
                                            <strong>Jenis:</strong><br>
                                            {{ $dokumen->jenis_dokumen }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        @if($dokumen->is_pdf)
                        <button type="button" class="btn btn-info require-login" 
                                data-dokumen-id="{{ $dokumen->id }}" data-action="preview">
                            <i class="fas fa-eye me-1"></i>Preview
                        </button>
                        @endif
                        <button type="button" class="btn btn-success require-login" 
                                data-dokumen-id="{{ $dokumen->id }}" data-action="download">
                            <i class="fas fa-download me-1"></i>Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <!-- Untuk Desktop -->
    <tr>
        <td colspan="8">
            <div class="no-documents">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">
                    @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                        Tidak ada dokumen yang sesuai dengan pencarian
                    @else
                        Belum ada dokumen publik
                    @endif
                </h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                        Coba ubah kata kunci atau filter pencarian Anda
                    @else
                        Dokumen akan ditampilkan di sini ketika tersedia
                    @endif
                </p>
            </div>
        </td>
    </tr>

    <!-- Untuk Mobile -->
    <div class="no-documents">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">
            @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                Tidak ada dokumen yang sesuai dengan pencarian
            @else
                Belum ada dokumen publik
            @endif
        </h5>
        <p class="text-muted">
            @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                Coba ubah kata kunci atau filter pencarian Anda
            @else
                Dokumen akan ditampilkan di sini ketika tersedia
            @endif
        </p>
    </div>
@endif