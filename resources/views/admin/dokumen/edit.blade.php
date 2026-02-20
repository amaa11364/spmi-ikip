@extends('layouts.main')

@section('title', 'Edit Dokumen')

@section('page_heading')
    <div class="d-flex align-items-center justify-content-between">
        <h1>Edit Dokumen</h1>
        <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Form Edit Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.dokumen.update', $dokumen->id) }}" 
                              method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_dokumen') is-invalid @enderror" 
                                       id="nama_dokumen" 
                                       name="nama_dokumen" 
                                       value="{{ old('nama_dokumen', $dokumen->nama_dokumen) }}" 
                                       required>
                                @error('nama_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="jenis_dokumen" class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('jenis_dokumen') is-invalid @enderror" 
                                       id="jenis_dokumen" 
                                       name="jenis_dokumen" 
                                       value="{{ old('jenis_dokumen', $dokumen->jenis_dokumen) }}" 
                                       required>
                                @error('jenis_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="unit_kerja_id" class="form-label">Unit Kerja</label>
                                    <select class="form-select @error('unit_kerja_id') is-invalid @enderror" 
                                            id="unit_kerja_id" 
                                            name="unit_kerja_id">
                                        <option value="">Pilih Unit Kerja</option>
                                        @foreach($unitKerjas as $unit)
                                            <option value="{{ $unit->id }}" 
                                                {{ old('unit_kerja_id', $dokumen->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->nama }} {{-- Ubah dari 'nama_unit' ke 'nama' --}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_kerja_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="prodi_id" class="form-label">Program Studi</label>
                                    <select class="form-select @error('prodi_id') is-invalid @enderror" 
                                            id="prodi_id" 
                                            name="prodi_id">
                                        <option value="">Pilih Prodi</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}" 
                                                {{ old('prodi_id', $dokumen->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                                {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('prodi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="iku_id" class="form-label">IKU</label>
                                    <select class="form-select @error('iku_id') is-invalid @enderror" 
                                            id="iku_id" 
                                            name="iku_id">
                                        <option value="">Pilih IKU</option>
                                        @foreach($ikus as $iku)
                                            <option value="{{ $iku->id }}" 
                                                {{ old('iku_id', $dokumen->iku_id) == $iku->id ? 'selected' : '' }}>
                                                {{ $iku->nama }} {{-- Ubah dari 'nama_iku' ke 'nama' --}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('iku_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="tahapan" class="form-label">Tahapan SPMI</label>
                                    <select class="form-select @error('tahapan') is-invalid @enderror" 
                                            id="tahapan" 
                                            name="tahapan">
                                        <option value="">Pilih Tahapan</option>
                                        @foreach($tahapanOptions as $value => $label)
                                            <option value="{{ $value }}" 
                                                {{ old('tahapan', $dokumen->tahapan) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tahapan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_public" 
                                           name="is_public" 
                                           value="1"
                                           {{ old('is_public', $dokumen->is_public) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_public">
                                        <i class="fas fa-globe me-1"></i>Publik (Dapat dilihat oleh semua orang)
                                    </label>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label class="form-label">Informasi File</label>
                                <div class="bg-light p-3 rounded">
                                    @if($dokumen->jenis_upload === 'file')
                                        <p><strong>Nama File:</strong> {{ $dokumen->file_name }}</p>
                                        <p><strong>Ukuran:</strong> {{ $dokumen->file_size_formatted }}</p>
                                        <p><strong>Ekstensi:</strong> {{ $dokumen->file_extension }}</p>
                                        <p><strong>Path:</strong> {{ $dokumen->file_path }}</p>
                                    @else
                                        <p><strong>Link:</strong> <a href="{{ $dokumen->file_path }}" target="_blank">{{ $dokumen->file_path }}</a></p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Status</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Status Verifikasi:</strong> {!! $dokumen->verification_badge !!}</p>
                        
                        @if($dokumen->verified_at)
                            <p><strong>Diverifikasi oleh:</strong> {{ $dokumen->verifier->name ?? '-' }}</p>
                            <p><strong>Tanggal:</strong> {{ $dokumen->verified_at->format('d/m/Y H:i') }}</p>
                        @endif
                        
                        @if($dokumen->rejection_reason)
                            <div class="alert alert-danger">
                                <strong>Alasan Penolakan:</strong>
                                <p class="mb-0">{{ $dokumen->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection