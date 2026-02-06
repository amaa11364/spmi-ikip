<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Evaluasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nama_evaluasi" value="{{ $evaluasi->nama_evaluasi }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
        <select class="form-select" name="tipe_evaluasi" required>
            <option value="ami" {{ $evaluasi->tipe_evaluasi == 'ami' ? 'selected' : '' }}>Audit Mutu Internal</option>
            <option value="edom" {{ $evaluasi->tipe_evaluasi == 'edom' ? 'selected' : '' }}>Evaluasi Dosen oleh Mahasiswa</option>
            <option value="evaluasi_layanan" {{ $evaluasi->tipe_evaluasi == 'evaluasi_layanan' ? 'selected' : '' }}>Evaluasi Layanan</option>
            <option value="evaluasi_kinerja" {{ $evaluasi->tipe_evaluasi == 'evaluasi_kinerja' ? 'selected' : '' }}>Evaluasi Kinerja</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="tahun" value="{{ $evaluasi->tahun }}" min="2000" max="{{ date('Y') + 5 }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Periode</label>
        <input type="text" class="form-control" name="periode" value="{{ $evaluasi->periode }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" name="status" required>
            <option value="aktif" {{ $evaluasi->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="selesai" {{ $evaluasi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="berjalan" {{ $evaluasi->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
            <option value="nonaktif" {{ $evaluasi->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Dokumen</label>
        <select class="form-select" name="status_dokumen">
            <option value="valid" {{ $evaluasi->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="belum_valid" {{ $evaluasi->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
            <option value="dalam_review" {{ $evaluasi->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Unit Kerja</label>
        <select class="form-select" name="unit_kerja_id">
            <option value="">Pilih Unit Kerja</option>
            @foreach($unitKerjas as $unit)
                <option value="{{ $unit->id }}" {{ $evaluasi->unit_kerja_id == $unit->id ? 'selected' : '' }}>
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
                <option value="{{ $iku->id }}" {{ $evaluasi->iku_id == $iku->id ? 'selected' : '' }}>
                    {{ $iku->kode }} - {{ $iku->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Penanggung Jawab</label>
    <input type="text" class="form-control" name="penanggung_jawab" value="{{ $evaluasi->penanggung_jawab }}">
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea class="form-control" name="deskripsi" rows="3">{{ $evaluasi->deskripsi }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Hasil Evaluasi</label>
    <textarea class="form-control" name="hasil_evaluasi" rows="2">{{ $evaluasi->hasil_evaluasi }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Rekomendasi</label>
    <textarea class="form-control" name="rekomendasi" rows="2">{{ $evaluasi->rekomendasi }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Target Waktu</label>
    <input type="date" class="form-control" name="target_waktu" value="{{ $evaluasi->target_waktu }}">
</div>

<input type="hidden" name="kode_evaluasi" value="{{ $evaluasi->kode_evaluasi }}">