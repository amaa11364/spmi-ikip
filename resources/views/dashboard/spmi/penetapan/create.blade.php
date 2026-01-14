{{-- create.blade.php --}}
@extends('layouts.main')

@section('title', 'Tambah Penetapan SPMI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Tambah Data Penetapan
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('spmi.penetapan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Komponen *</label>
                            <input type="text" class="form-control" name="nama_komponen" 
                                   value="{{ old('nama_komponen') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipe Penetapan *</label>
                            <select class="form-select" name="tipe_penetapan" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="pengelolaan">Pengelolaan SPMI Institusi</option>
                                <option value="organisasi">Organisasi Pengelola SPMI</option>
                                <option value="pelaksanaan">Standar Pelaksanaan SPMI</option>
                                <option value="evaluasi">Standar Evaluasi</option>
                                <option value="pengendalian">Standar Pengendalian</option>
                                <option value="peningkatan">Standar Peningkatan</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun *</label>
                                <input type="number" class="form-control" name="tahun" 
                                       value="{{ old('tahun', date('Y')) }}" min="2000" max="{{ date('Y') + 5 }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="revisi">Dalam Revisi</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" name="penanggung_jawab" 
                                   value="{{ old('penanggung_jawab') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('spmi.penetapan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection