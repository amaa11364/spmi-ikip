@extends('layouts.main')

@section('title', 'Tambah Evaluasi SPMI')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Evaluasi Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('spmi.evaluasi.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Komponen Evaluasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_komponen" required 
                                   placeholder="Contoh: Evaluasi Kinerja Akademik">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipe_evaluasi" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="internal">Evaluasi Internal</option>
                                    <option value="eksternal">Evaluasi Eksternal</option>
                                    <option value="berkala">Evaluasi Berkala</option>
                                    <option value="khusus">Evaluasi Khusus</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="tahun" 
                                       value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Periode <span class="text-danger">*</span></label>
                                <select class="form-select" name="periode" required>
                                    <option value="">Pilih Periode</option>
                                    <option value="Semester I">Semester I</option>
                                    <option value="Semester II">Semester II</option>
                                    <option value="Triwulan I">Triwulan I</option>
                                    <option value="Triwulan II">Triwulan II</option>
                                    <option value="Triwulan III">Triwulan III</option>
                                    <option value="Triwulan IV">Triwulan IV</option>
                                    <option value="Tahunan">Tahunan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="proses">Dalam Proses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditunda">Ditunda</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Kerja</label>
                                <select class="form-select" name="unit_kerja_id">
                                    <option value="">Pilih Unit Kerja</option>
                                    @foreach($unitKerjas as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">IKU</label>
                                <select class="form-select" name="iku_id">
                                    <option value="">Pilih IKU</option>
                                    @foreach($ikus as $iku)
                                        <option value="{{ $iku->id }}">{{ $iku->kode }} - {{ $iku->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" name="penanggung_jawab" 
                                   placeholder="Nama penanggung jawab evaluasi">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" 
                                      placeholder="Deskripsi lengkap tentang evaluasi ini"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('spmi.evaluasi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Evaluasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection