@extends('layouts.main')

@section('title', 'Review Dokumen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Review Dokumen</h3>
                    <div class="card-tools">
                        <!-- Stats Badges -->
                        <span class="badge bg-warning me-1">Pending: {{ $counts['pending'] }}</span>
                        <span class="badge bg-success me-1">Approved: {{ $counts['approved'] }}</span>
                        <span class="badge bg-danger me-1">Rejected: {{ $counts['rejected'] }}</span>
                        <span class="badge bg-info me-1">Revision: {{ $counts['revision'] }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('verifikator.review.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Revision</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari judul, nomor dokumen, atau pengupload..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ route('verifikator.review.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Dokumen -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Nomor Dokumen</th>
                                    <th width="25%">Judul</th>
                                    <th width="15%">Pengupload</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dokumens as $index => $dokumen)
                                <tr>
                                    <td>{{ $dokumens->firstItem() + $index }}</td>
                                    <td>{{ $dokumen->nomor_dokumen ?? '-' }}</td>
                                    <td>{{ $dokumen->judul }}</td>
                                    <td>{{ $dokumen->user->name ?? 'Unknown' }}</td>
                                    <td>{{ $dokumen->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($dokumen->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($dokumen->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($dokumen->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($dokumen->status == 'revision')
                                            <span class="badge bg-info">Revision</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('verifikator.review.detail', $dokumen->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        @if($dokumen->status == 'pending')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="approveDokumen({{ $dokumen->id }})">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="rejectDokumen({{ $dokumen->id }})">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada dokumen ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $dokumens->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui dokumen ini?</p>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Komentar (Opsional)</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Tambahkan komentar..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required minlength="10" 
                                  placeholder="Berikan alasan penolakan (minimal 10 karakter)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Komentar Tambahan (Opsional)</label>
                        <textarea name="comment" class="form-control" rows="2" placeholder="Tambahkan komentar..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveDokumen(id) {
    const form = document.getElementById('approveForm');
    form.action = "{{ url('verifikator/dokumen') }}/" + id + "/verify";
    
    // Tambah input hidden untuk action
    let actionInput = document.querySelector('input[name="action"]');
    if (!actionInput) {
        actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        form.appendChild(actionInput);
    }
    actionInput.value = 'approve';
    
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function rejectDokumen(id) {
    const form = document.getElementById('rejectForm');
    form.action = "{{ url('verifikator/dokumen') }}/" + id + "/verify";
    
    // Tambah input hidden untuk action
    let actionInput = document.querySelector('input[name="action"]');
    if (!actionInput) {
        actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        form.appendChild(actionInput);
    }
    actionInput.value = 'reject';
    
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endpush