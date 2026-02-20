@extends('layouts.main')

@section('title', 'Detail Dokumen')

@section('page_heading')
    <div class="d-flex align-items-center justify-content-between">
        <h1>Detail Dokumen</h1>
        <div>
            <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('admin.dokumen.edit', $dokumen->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Informasi Utama -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Nama Dokumen</th>
                                        <td>: <strong>{{ $dokumen->nama_dokumen }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Dokumen</th>
                                        <td>: {{ $dokumen->jenis_dokumen }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahapan SPMI</th>
                                        <td>: {{ $dokumen->tahapan_label ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unit Kerja</th>
                                        <td>: {{ $dokumen->unitKerja->nama_unit ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Program Studi</th>
                                        <td>: {{ $dokumen->prodi->nama_prodi ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Status Verifikasi</th>
                                        <td>: {!! $dokumen->verification_badge !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Publik</th>
                                        <td>: 
                                            @if($dokumen->is_public)
                                                <span class="badge bg-success">Publik</span>
                                            @else
                                                <span class="badge bg-secondary">Private</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Upload</th>
                                        <td>: {{ $dokumen->jenis_upload_label }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ukuran File</th>
                                        <td>: {{ $dokumen->file_size_formatted ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ekstensi</th>
                                        <td>: {{ strtoupper($dokumen->file_extension) ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($dokumen->rejection_reason)
                            <div class="alert alert-danger mt-3">
                                <strong>Alasan Penolakan:</strong><br>
                                {{ $dokumen->rejection_reason }}
                            </div>
                        @endif
                        
                        @if($dokumen->metadata && count($dokumen->metadata) > 0)
                            <div class="mt-3">
                                <h6>Metadata:</h6>
                                <pre class="bg-light p-3 rounded">{{ json_encode($dokumen->metadata, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Riwayat Verifikasi -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Riwayat Verifikasi</h5>
                    </div>
                    <div class="card-body">
                        @if($dokumen->verified_at)
                            <table class="table table-borderless">
                                <tr>
                                    <th width="20%">Diverifikasi oleh</th>
                                    <td>: {{ $dokumen->verifier->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Verifikasi</th>
                                    <td>: {{ $dokumen->verified_at->format('d F Y H:i') }}</td>
                                </tr>
                                @if($dokumen->rejection_reason)
                                    <tr>
                                        <th>Alasan Penolakan</th>
                                        <td>: {{ $dokumen->rejection_reason }}</td>
                                    </tr>
                                @endif
                            </table>
                        @else
                            <p class="text-muted">Belum diverifikasi</p>
                        @endif
                    </div>
                </div>
                
                <!-- Komentar -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Komentar</h5>
                    </div>
                    <div class="card-body">
                        @forelse($dokumen->comments as $comment)
                            <div class="mb-3">
                                <strong>{{ $comment->user->name }}</strong>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                <p class="mb-0">{{ $comment->content }}</p>
                                <hr>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada komentar</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Preview Dokumen -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Preview Dokumen</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($dokumen->jenis_upload === 'file')
                            @if($dokumen->is_pdf)
                                <iframe src="{{ $dokumen->preview_url }}" 
                                        style="width: 100%; height: 300px;" 
                                        frameborder="0"></iframe>
                            @elseif($dokumen->is_image)
                                <img src="{{ $dokumen->getUrl() }}" 
                                     alt="{{ $dokumen->nama_dokumen }}"
                                     class="img-fluid" style="max-height: 300px;">
                            @else
                                <i class="{{ $dokumen->file_icon }} fa-5x mb-3"></i>
                                <p>{{ $dokumen->file_name }}</p>
                            @endif
                            
                            <div class="mt-3">
                                <a href="{{ $dokumen->download_url }}" 
                                   class="btn btn-primary" 
                                   target="_blank">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                            </div>
                        @else
                            <i class="fas fa-link fa-5x mb-3"></i>
                            <p>Link Eksternal</p>
                            <a href="{{ $dokumen->file_path }}" 
                               class="btn btn-primary" 
                               target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Buka Link
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Informasi Uploader -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Upload</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Uploader:</strong> {{ $dokumen->uploader->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $dokumen->uploader->email ?? '-' }}</p>
                        <p><strong>Tanggal Upload:</strong> {{ $dokumen->created_at->format('d F Y H:i') }}</p>
                        <p><strong>Terakhir Update:</strong> {{ $dokumen->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection