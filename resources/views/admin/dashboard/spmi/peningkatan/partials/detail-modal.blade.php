<div class="row">
    <div class="col-md-8">
        <h4 class="text-primary">{{ $peningkatan->nama_program }}</h4>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-info">{{ $peningkatan->kode_peningkatan }}</span>
            <span class="badge bg-secondary">{{ $peningkatan->tahun }}</span>
            <span class="badge bg-{{ $peningkatan->status_color }}">
                {{ $peningkatan->status_label }}
            </span>
            <span class="badge bg-{{ $peningkatan->status_dokumen_color }}">
                Dokumen: {{ $peningkatan->status_dokumen_label }}
            </span>
            <span class="badge bg-primary">{{ $allDokumen->count() }} dokumen</span>
            <span class="badge bg-{{ $peningkatan->tipe_peningkatan == 'strategis' ? 'danger' : 'warning' }}">
                {{ $peningkatan->tipe_peningkatan_label }}
            </span>
        </div>
        
        <div class="mb-4">
            <h6 class="text-muted mb-2">Deskripsi:</h6>
            <p class="mb-0">{{ $peningkatan->deskripsi ?? 'Tidak ada deskripsi' }}</p>
        </div>
        
        @if($peningkatan->catatan_evaluasi)
        <div class="mb-4">
            <h6 class="text-muted mb-2">Catatan Evaluasi:</h6>
            <p class="mb-0">{{ $peningkatan->catatan_evaluasi }}</p>
        </div>
        @endif
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Informasi Program:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-user-tie me-2 text-primary"></i>
                        <strong>Penanggung Jawab:</strong> {{ $peningkatan->penanggung_jawab ?? 'Tidak ditentukan' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-building me-2 text-primary"></i>
                        <strong>Unit Kerja:</strong> {{ $peningkatan->unitKerja->nama ?? 'Tidak ada' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        <strong>IKU:</strong> {{ $peningkatan->iku->nama ?? 'Tidak ada' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-coins me-2 text-primary"></i>
                        <strong>Anggaran:</strong> {{ $peningkatan->anggaran_formatted }}
                        @if($peningkatan->realisasi_anggaran > 0)
                            <br><small class="text-muted">Realisasi: {{ $peningkatan->realisasi_anggaran_formatted }}</small>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Progress & Timeline:</h6>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-tasks me-2 text-primary"></i> Progress:</span>
                            <strong>{{ $peningkatan->progress }}%</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-{{ $peningkatan->progress_color }}" 
                                 style="width: {{ $peningkatan->progress }}%"></div>
                        </div>
                    </li>
                    @if($peningkatan->tanggal_mulai)
                    <li class="mb-2">
                        <i class="fas fa-calendar-start me-2 text-primary"></i>
                        <strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($peningkatan->tanggal_mulai)->format('d/m/Y') }}
                    </li>
                    @endif
                    @if($peningkatan->tanggal_selesai)
                    <li class="mb-2">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                        <strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($peningkatan->tanggal_selesai)->format('d/m/Y') }}
                    </li>
                    @endif
                    <li class="mb-2">
                        <i class="fas fa-calendar-plus me-2 text-primary"></i>
                        <strong>Dibuat:</strong> {{ $peningkatan->created_at->format('d/m/Y H:i') }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                        <strong>Diperbarui:</strong> {{ $peningkatan->updated_at->format('d/m/Y H:i') }}
                    </li>
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
                <a href="{{ route('upload.spmi-penetapan', ['id' => $peningkatan->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-upload me-1"></i> Tambah Dokumen
                </a>
            </div>
        </div>
        
        <!-- Status Dokumen -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i> Status Dokumen</h6>
            </div>
            <div class="card-body">
                <form id="statusDokumenForm" action="{{ route('spmi.peningkatan.status.update', $peningkatan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Ubah Status Dokumen</label>
                        <select class="form-select" name="status_dokumen" id="statusDokumenSelect">
                            <option value="valid" {{ $peningkatan->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
                            <option value="belum_valid" {{ $peningkatan->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
                            <option value="dalam_review" {{ $peningkatan->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="2" placeholder="Tambahkan catatan jika diperlukan">{{ $peningkatan->catatan_evaluasi }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>