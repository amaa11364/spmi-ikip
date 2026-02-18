{{-- Responsive Filter --}}
<div class="custom-card p-3 p-md-4 mb-4">
    <form action="{{ route('verifikator.dokumen.index') }}" method="GET" class="row g-2 g-md-3">
        <div class="col-12 col-md-6 col-lg-6">
            <input type="text" name="search" class="form-control form-control-sm" 
                   placeholder="Cari judul dokumen..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <select name="unit_kerja" class="form-control form-control-sm">
                <option value="">Semua Unit Kerja</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_kerja') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama }}
                    </option>
                @endforeach
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
    @if($dokumen->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="d-none d-md-table-header-group">
                    <tr>
                        <th>#</th>
                        <th>Judul Dokumen</th>
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
                            <td class="d-none d-md-table-cell">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="mb-1">{{ $item->judul }}</strong>
                                    <small class="text-muted">{{ $item->kategori ?? 'Tidak ada kategori' }}</small>
                                    <div class="d-flex d-md-none justify-content-between mt-2">
                                        <div>
                                            <small class="d-block">{{ $item->uploader->name ?? 'Tidak diketahui' }}</small>
                                            <small class="text-muted">{{ $item->uploader->unit_kerja ?? '-' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small>{{ $item->created_at->format('d/m/Y') }}</small>
                                            <br>
                                            @if($item->status == 'pending')
                                                <span class="badge badge-pending">Menunggu</span>
                                            @elseif($item->status == 'approved')
                                                <span class="badge badge-approved">Disetujui</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge badge-rejected">Ditolak</span>
                                            @elseif($item->status == 'revision')
                                                <span class="badge badge-revision">Revisi</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $item->uploader->name ?? 'Tidak diketahui' }}
                                <br>
                                <small class="text-muted">{{ $item->uploader->email ?? '' }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $item->uploader->unit_kerja ?? '-' }}
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $item->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($item->status == 'pending')
                                    <span class="badge badge-pending">Menunggu</span>
                                @elseif($item->status == 'approved')
                                    <span class="badge badge-approved">Disetujui</span>
                                @elseif($item->status == 'rejected')
                                    <span class="badge badge-rejected">Ditolak</span>
                                @elseif($item->status == 'revision')
                                    <span class="badge badge-revision">Revisi</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('verifikator.dokumen.view', $item->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">Lihat</span>
                                        </a>
                                        <button onclick="downloadDokumen('{{ $item->id }}')" 
                                                class="btn btn-outline-info" 
                                                title="Download">
                                            <i class="fas fa-download"></i>
                                            <span class="d-none d-md-inline ms-1">Download</span>
                                        </button>
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
                {{ $dokumen->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        {{-- No data message --}}
    @endif
</div>