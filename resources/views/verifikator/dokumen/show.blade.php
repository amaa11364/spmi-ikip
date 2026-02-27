@extends('layouts.main')

@section('title', 'Detail Dokumen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Dokumen</h3>
                    <div class="card-tools">
                        <a href="{{ route('verifikator.dokumen.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nama Dokumen</th>
                                    <td>{{ $dokumen->nama_dokumen }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Dokumen</th>
                                    <td>{{ $dokumen->jenis_dokumen ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tahapan PPEPP</th>
                                    <td>
                                        @if($dokumen->tahapan)
                                            <span class="badge bg-info">{{ ucfirst($dokumen->tahapan) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @php
                                            $badgeColor = match($dokumen->status) {
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'revision' => 'info',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }}">
                                            {{ ucfirst($dokumen->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Pengupload</th>
                                    <td>{{ $dokumen->uploader->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email Uploader</th>
                                    <td>{{ $dokumen->uploader->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Unit Kerja</th>
                                    <td>{{ $dokumen->unitKerja->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Upload</th>
                                    <td>{{ $dokumen->created_at ? $dokumen->created_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                                @if($dokumen->verified_at)
                                <tr>
                                    <th>Diverifikasi Pada</th>
                                    <td>{{ $dokumen->verified_at instanceof \Carbon\Carbon ? $dokumen->verified_at->format('d/m/Y H:i') : $dokumen->verified_at }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($dokumen->deskripsi)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">Deskripsi</h5>
                                </div>
                                <div class="card-body">
                                    {{ $dokumen->deskripsi }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($dokumen->rejection_reason)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-times-circle"></i> Alasan Penolakan:</h5>
                                <p class="mb-0">{{ $dokumen->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($dokumen->revision_instructions)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-edit"></i> Instruksi Revisi:</h5>
                                <p class="mb-0">{{ $dokumen->revision_instructions }}</p>
                                @if($dokumen->revision_deadline)
                                <small class="text-muted">Deadline: {{ $dokumen->revision_deadline instanceof \Carbon\Carbon ? $dokumen->revision_deadline->format('d/m/Y') : $dokumen->revision_deadline }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Preview Dokumen -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">Preview Dokumen</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($dokumen->jenis_upload == 'file' && $dokumen->file_path)
                                        @php
                                            $extension = strtolower($dokumen->file_extension);
                                        @endphp
                                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <img src="{{ asset('storage/'.$dokumen->file_path) }}" 
                                                 class="img-fluid" style="max-height: 500px;" alt="Preview">
                                        @elseif($extension == 'pdf')
                                            <iframe src="{{ asset('storage/'.$dokumen->file_path) }}" 
                                                    style="width:100%; height:600px;" frameborder="0"></iframe>
                                        @else
                                            <p>Preview tidak tersedia untuk tipe file ini.</p>
                                        @endif
                                    @elseif($dokumen->jenis_upload == 'link')
                                        <p>Dokumen berupa link: <a href="{{ $dokumen->file_path }}" target="_blank">{{ $dokumen->file_path }}</a></p>
                                    @else
                                        <p>Tidak ada file untuk dipreview.</p>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('verifikator.dokumen.download', $dokumen->id) }}" 
                                           class="btn btn-success">
                                            <i class="fas fa-download"></i> Download Dokumen
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksi Verifikasi -->
                    @if(in_array($dokumen->status, ['pending', 'revision']))
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0">Verifikasi Dokumen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revisionModal">
                                            <i class="fas fa-edit"></i> Minta Revisi
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Komentar -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0">Komentar</h5>
                                </div>
                                <div class="card-body">
                                    @forelse($dokumen->comments ?? [] as $comment)
                                    <div class="mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $comment->user->name ?? 'System' }}</strong>
                                            <small class="text-muted">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</small>
                                        </div>
                                        <p class="mb-0 mt-2">{{ $comment->content ?? $comment->comment }}</p>
                                        @if($comment->type)
                                        <small class="text-muted">Tipe: {{ $comment->type }}</small>
                                        @endif
                                    </div>
                                    @empty
                                    <p class="text-center text-muted">Belum ada komentar</p>
                                    @endforelse

                                    <!-- Form Tambah Komentar -->
                                    <form action="{{ route('verifikator.dokumen.comment', $dokumen->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        <div class="input-group">
                                            <textarea name="comment" class="form-control" rows="2" 
                                                      placeholder="Tambah komentar..." required></textarea>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-paper-plane"></i> Kirim
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
            <form action="{{ route('verifikator.dokumen.approve', $dokumen->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Setujui Dokumen</h5>
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
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('verifikator.dokumen.reject', $dokumen->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Berikan alasan penolakan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Komentar Tambahan (Opsional)</label>
                        <textarea name="comment" class="form-control" rows="2" placeholder="Tambahkan komentar..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Revision -->
<div class="modal fade" id="revisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('verifikator.dokumen.revision', $dokumen->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Minta Revisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Instruksi Revisi <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Jelaskan apa yang perlu direvisi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline Revisi</label>
                        <input type="date" name="deadline" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Komentar Tambahan (Opsional)</label>
                        <textarea name="comment" class="form-control" rows="2" placeholder="Tambahkan komentar..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Permintaan Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection