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
                        <a href="{{ route('verifikator.review.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nomor Dokumen</th>
                                    <td>{{ $dokumen->nomor_dokumen ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Judul</th>
                                    <td>{{ $dokumen->judul }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Dokumen</th>
                                    <td>{{ $dokumen->jenis_dokumen ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
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
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Pengupload</th>
                                    <td>{{ $dokumen->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Unit Kerja</th>
                                    <td>{{ $dokumen->unitKerja->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Upload</th>
                                    <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($dokumen->verified_at)
                                <tr>
                                    <th>Diverifikasi Oleh</th>
                                    <td>{{ $dokumen->verifikator->name ?? '-' }} ({{ $dokumen->verified_at->format('d/m/Y H:i') }})</td>
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
                                <small class="text-muted">Deadline: {{ $dokumen->revision_deadline->format('d/m/Y') }}</small>
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
                                    @if(in_array(pathinfo($dokumen->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ asset('storage/'.$dokumen->file_path) }}" 
                                             class="img-fluid" style="max-height: 500px;" alt="Preview">
                                    @elseif(pathinfo($dokumen->file_path, PATHINFO_EXTENSION) == 'pdf')
                                        <iframe src="{{ asset('storage/'.$dokumen->file_path) }}" 
                                                style="width:100%; height:600px;" frameborder="0"></iframe>
                                    @else
                                        <p>Preview tidak tersedia untuk tipe file ini.</p>
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

                    <!-- Komentar -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0">Komentar</h5>
                                </div>
                                <div class="card-body">
                                    @forelse($dokumen->comments as $comment)
                                    <div class="mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $comment->user->name ?? 'System' }}</strong>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 mt-2">{{ $comment->comment }}</p>
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
@endsection