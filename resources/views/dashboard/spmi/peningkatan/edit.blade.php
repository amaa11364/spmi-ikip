@extends('layouts.main')

@section('title', 'Edit Program Peningkatan')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Edit Program Peningkatan
        </h4>
        <a href="{{ route('spmi.peningkatan.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('spmi.peningkatan.update', $peningkatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nama Program <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_program" value="{{ $peningkatan->nama_program }}" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipe Program <span class="text-danger">*</span></label>
                        <select class="form-select" name="tipe_peningkatan" required>
                            <option value="strategis" {{ $peningkatan->tipe_peningkatan == 'strategis' ? 'selected' : '' }}>Strategis</option>
                            <option value="operasional" {{ $peningkatan->tipe_peningkatan == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="perbaikan" {{ $peningkatan->tipe_peningkatan == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                            <option value="pengembangan" {{ $peningkatan->tipe_peningkatan == 'pengembangan' ? 'selected' : '' }}>Pengembangan</option>
                            <option value="inovasi" {{ $peningkatan->tipe_peningkatan == 'inovasi' ? 'selected' : '' }}>Inovasi</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="tahun" value="{{ $peningkatan->tahun }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="draft" {{ $peningkatan->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="disetujui" {{ $peningkatan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="berjalan" {{ $peningkatan->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ $peningkatan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Kerja</label>
                        <select class="form-select" name="unit_kerja_id">
                            <option value="">Pilih Unit Kerja</option>
                            @foreach($unitKerjas as $unit)
                                <option value="{{ $unit->id }}" {{ $peningkatan->unit_kerja_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <input type="text" class="form-control" name="penanggung_jawab" value="{{ $peningkatan->penanggung_jawab }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Anggaran (Rp)</label>
                        <input type="number" class="form-control" name="anggaran" value="{{ $peningkatan->anggaran }}" min="0">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Progress (%)</label>
                        <input type="range" class="form-range" name="progress" min="0" max="100" value="{{ $peningkatan->progress }}">
                        <div class="d-flex justify-content-between">
                            <small>0%</small>
                            <span id="progressValue">{{ $peningkatan->progress }}%</span>
                            <small>100%</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3">{{ $peningkatan->deskripsi }}</textarea>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('spmi.peningkatan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i> Update
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