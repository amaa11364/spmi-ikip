<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Program <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nama_program" value="{{ $peningkatan->nama_program }}" required>
    </div>
    
    <div class="col-md-4 mb-3">
        <label class="form-label">Tipe Peningkatan <span class="text-danger">*</span></label>
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
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode Peningkatan</label>
        <input type="text" class="form-control" value="{{ $peningkatan->kode_peningkatan }}" readonly>
    </div>
    
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="tahun" value="{{ $peningkatan->tahun }}" min="2000" max="{{ date('Y') + 5 }}" required>
    </div>
    
    <div class="col-md-4 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" name="status" required>
            <option value="draft" {{ $peningkatan->status == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="disetujui" {{ $peningkatan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="berjalan" {{ $peningkatan->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
            <option value="selesai" {{ $peningkatan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="ditunda" {{ $peningkatan->status == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
            <option value="dibatalkan" {{ $peningkatan->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
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
        <label class="form-label">IKU</label>
        <select class="form-select" name="iku_id">
            <option value="">Pilih IKU</option>
            @foreach($ikus as $iku)
                <option value="{{ $iku->id }}" {{ $peningkatan->iku_id == $iku->id ? 'selected' : '' }}>
                    {{ $iku->kode }} - {{ $iku->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Penanggung Jawab</label>
    <input type="text" class="form-control" name="penanggung_jawab" value="{{ $peningkatan->penanggung_jawab }}">
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea class="form-control" name="deskripsi" rows="3">{{ $peningkatan->deskripsi }}</textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Anggaran (Rp)</label>
        <input type="number" class="form-control" name="anggaran" value="{{ $peningkatan->anggaran }}" min="0" step="1000">
    </div>
    
    <div class="col-md-6 mb-3">
        <label class="form-label">Realisasi Anggaran (Rp)</label>
        <input type="number" class="form-control" name="realisasi_anggaran" value="{{ $peningkatan->realisasi_anggaran }}" min="0" step="1000">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Progress (%)</label>
        <div class="d-flex align-items-center">
            <input type="range" class="form-range me-3" name="progress" id="progressRange" 
                   min="0" max="100" step="5" value="{{ $peningkatan->progress }}">
            <span class="badge bg-info" id="progressValue">{{ $peningkatan->progress }}%</span>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Dokumen</label>
        <select class="form-select" name="status_dokumen">
            <option value="valid" {{ $peningkatan->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="belum_valid" {{ $peningkatan->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
            <option value="dalam_review" {{ $peningkatan->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" class="form-control" name="tanggal_mulai" value="{{ $peningkatan->tanggal_mulai ? $peningkatan->tanggal_mulai->format('Y-m-d') : '' }}">
    </div>
    
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" class="form-control" name="tanggal_selesai" value="{{ $peningkatan->tanggal_selesai ? $peningkatan->tanggal_selesai->format('Y-m-d') : '' }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Catatan Evaluasi</label>
    <textarea class="form-control" name="catatan_evaluasi" rows="2">{{ $peningkatan->catatan_evaluasi }}</textarea>
</div>

<script>
    document.getElementById('progressRange').addEventListener('input', function(e) {
        document.getElementById('progressValue').textContent = e.target.value + '%';
    });
</script>