{{-- Responsive Filter --}}
<div class="custom-card p-3 p-md-4 mb-4">
    <form action="{{ route('verifikator.dokumen.index') }}" method="GET" class="row g-2 g-md-3">
        <div class="col-12 col-md-4 col-lg-4">
            <input type="text" name="search" class="form-control form-control-sm" 
                   placeholder="Cari judul dokumen..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-3 col-lg-3">
            <select name="status" class="form-control form-control-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Revision</option>
            </select>
        </div>
        <div class="col-12 col-md-3 col-lg-3">
            <select name="tahapan" class="form-control form-control-sm">
                <option value="">Semua Tahapan</option>
                <option value="penetapan" {{ request('tahapan') == 'penetapan' ? 'selected' : '' }}>Penetapan</option>
                <option value="pelaksanaan" {{ request('tahapan') == 'pelaksanaan' ? 'selected' : '' }}>Pelaksanaan</option>
                <option value="evaluasi" {{ request('tahapan') == 'evaluasi' ? 'selected' : '' }}>Evaluasi</option>
                <option value="pengendalian" {{ request('tahapan') == 'pengendalian' ? 'selected' : '' }}>Pengendalian</option>
                <option value="peningkatan" {{ request('tahapan') == 'peningkatan' ? 'selected' : '' }}>Peningkatan</option>
            </select>
        </div>
        <div class="col-12 col-md-2 col-lg-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-search me-1"></i>
                <span class="d-none d-md-inline">Cari</span>
            </button>
        </div>
    </form>
</div>

{{-- Responsive Table --}}
<div class="custom-card p-3 p-md-4">
    @if(isset($dokumen) && $dokumen->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="d-none d-md-table-header-group">
                    <tr>
                        <th>#</th>
                        <th>Judul Dokumen</th>
                        <th>Tahapan</th>
                        <th>Pengunggah</th>
                        <th>Unit Kerja</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumen as $item)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $loop->iteration + (($dokumen->currentPage() - 1) * $dokumen->perPage()) }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="mb-1">{{ $item->judul ?? $item->nama_dokumen }}</strong>
                                    <small class="text-muted">{{ $item->kategori ?? 'Tidak ada kategori' }}</small>
                                    <div class="d-flex d-md-none justify-content-between mt-2">
                                        <div>
                                            <small class="d-block">{{ $item->uploader->name ?? $item->user->name ?? 'Tidak diketahui' }}</small>
                                            <small class="text-muted">{{ $item->uploader->unit_kerja ?? $item->user->unit_kerja ?? '-' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</small>
                                            <br>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($item->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif($item->status == 'revision')
                                                <span class="badge bg-info">Revisi</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($item->tahapan)
                                    <span class="badge bg-info">{{ ucfirst($item->tahapan) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $item->uploader->name ?? $item->user->name ?? 'Tidak diketahui' }}
                                <br>
                                <small class="text-muted">{{ $item->uploader->email ?? $item->user->email ?? '' }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $item->uploader->unit_kerja ?? $item->user->unit_kerja ?? '-' }}
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                                <br>
                                <small class="text-muted">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($item->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($item->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($item->status == 'revision')
                                    <span class="badge bg-info">Revisi</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('verifikator.dokumen.show', $item->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">Lihat</span>
                                        </a>
                                        <a href="{{ route('verifikator.dokumen.download', $item->id) }}" 
                                                class="btn btn-outline-info" 
                                                title="Download">
                                            <i class="fas fa-download"></i>
                                            <span class="d-none d-md-inline ms-1">Download</span>
                                        </a>
                                        @if($item->status == 'pending')
                                            <div class="dropdown">
                                                <button class="btn btn-outline-success dropdown-toggle" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown"
                                                        title="Verifikasi">
                                                    <i class="fas fa-check"></i>
                                                    <span class="d-none d-md-inline ms-1">Verifikasi</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item text-success" 
                                                           href="#" 
                                                           onclick="simpleVerification('{{ $item->id }}', 'approved')">
                                                            <i class="fas fa-check me-2"></i>Setujui
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" 
                                                           href="#" 
                                                           onclick="simpleVerification('{{ $item->id }}', 'rejected')">
                                                            <i class="fas fa-times me-2"></i>Tolak
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-warning" 
                                                           href="#" 
                                                           onclick="simpleVerification('{{ $item->id }}', 'revision')">
                                                            <i class="fas fa-edit me-2"></i>Minta Revisi
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Responsive Pagination --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
            <div class="text-muted mb-2 mb-md-0">
                Menampilkan {{ $dokumen->firstItem() }} - {{ $dokumen->lastItem() }} dari {{ $dokumen->total() }} dokumen
            </div>
            <div>
                {{ $dokumen->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada dokumen ditemukan</h5>
            <p class="text-muted mb-4">Belum ada dokumen yang diupload atau filter terlalu ketat.</p>
            <a href="{{ route('verifikator.dokumen.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-sync-alt me-1"></i> Reset Filter
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function simpleVerification(id, action) {
    let message = '';
    let title = '';
    
    switch(action) {
        case 'approved':
            title = 'Setujui Dokumen';
            message = 'Apakah Anda yakin ingin menyetujui dokumen ini?';
            break;
        case 'rejected':
            title = 'Tolak Dokumen';
            message = 'Apakah Anda yakin ingin menolak dokumen ini?';
            break;
        case 'revision':
            title = 'Minta Revisi';
            message = 'Apakah Anda yakin ingin meminta revisi dokumen ini?';
            break;
    }
    
    if (confirm(title + '\n' + message)) {
        window.location.href = "{{ url('verifikator/dokumen') }}/" + id + "/" + action;
    }
}
</script>
@endpush