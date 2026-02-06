@extends('layouts.main')

@section('title', 'Tambah Program Peningkatan')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-plus-circle me-2 text-success"></i>
            Tambah Program Peningkatan
        </h4>
        <a href="{{ route('spmi.peningkatan.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('spmi.peningkatan.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nama Program <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_program') is-invalid @enderror" 
                               name="nama_program" value="{{ old('nama_program') }}" required>
                        @error('nama_program')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipe Program <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipe_peningkatan') is-invalid @enderror" name="tipe_peningkatan" required>
                            <option value="">Pilih Tipe</option>
                            <option value="strategis" {{ old('tipe_peningkatan') == 'strategis' ? 'selected' : '' }}>Strategis</option>
                            <option value="operasional" {{ old('tipe_peningkatan') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="perbaikan" {{ old('tipe_peningkatan') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                            <option value="pengembangan" {{ old('tipe_peningkatan') == 'pengembangan' ? 'selected' : '' }}>Pengembangan</option>
                            <option value="inovasi" {{ old('tipe_peningkatan') == 'inovasi' ? 'selected' : '' }}>Inovasi</option>
                        </select>
                        @error('tipe_peningkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('tahun') is-invalid @enderror" 
                               name="tahun" value="{{ old('tahun', date('Y')) }}" min="2000" max="{{ date('Y') + 5 }}" required>
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="disetujui" {{ old('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="berjalan" {{ old('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Kerja</label>
                        <select class="form-select" name="unit_kerja_id">
                            <option value="">Pilih Unit Kerja</option>
                            @foreach($unitKerjas as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <input type="text" class="form-control" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Anggaran (Rp)</label>
                        <input type="number" class="form-control" name="anggaran" value="{{ old('anggaran') }}" min="0" step="1000">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Progress (%)</label>
                        <input type="range" class="form-range" name="progress" min="0" max="100" value="{{ old('progress', 0) }}">
                        <div class="d-flex justify-content-between">
                            <small>0%</small>
                            <span id="progressValue">0%</span>
                            <small>100%</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('spmi.peningkatan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update progress value display
    document.querySelector('input[name="progress"]').addEventListener('input', function(e) {
        document.getElementById('progressValue').textContent = e.target.value + '%';
    });
</script>
@endpush
@endsection