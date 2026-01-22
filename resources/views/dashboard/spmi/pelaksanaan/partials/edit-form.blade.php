<div class="mb-3">
    <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="nama_komponen" value="{{ $pelaksanaan->nama_komponen }}" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tahun Pelaksanaan <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="tahun" value="{{ $pelaksanaan->tahun }}" min="2000" max="{{ date('Y') + 5 }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" name="status" required>
            <option value="aktif" {{ $pelaksanaan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ $pelaksanaan->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="revisi" {{ $pelaksanaan->status == 'revisi' ? 'selected' : '' }}>Revisi</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Dokumen</label>
        <select class="form-select" name="status_dokumen">
            <option value="valid" {{ $pelaksanaan->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="belum_valid" {{ $pelaksanaan->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
            <option value="dalam_review" {{ $pelaksanaan->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Unit Kerja</label>
        <select class="form-select" name="unit_kerja_id">
            <option value="">Pilih Unit Kerja</option>
            @foreach($unitKerjas as $unit)
                <option value="{{ $unit->id }}" {{ $pelaksanaan->unit_kerja_id == $unit->id ? 'selected' : '' }}>
                    {{ $unit->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Penanggung Jawab</label>
        <input type="text" class="form-control" name="penanggung_jawab" value="{{ $pelaksanaan->penanggung_jawab }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">IKU</label>
        <select class="form-select" name="iku_id">
            <option value="">Pilih IKU</option>
            @foreach($ikus as $iku)
                <option value="{{ $iku->id }}" {{ $pelaksanaan->iku_id == $iku->id ? 'selected' : '' }}>
                    {{ $iku->kode }} - {{ $iku->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi Kegiatan</label>
    <textarea class="form-control" name="deskripsi" rows="3">{{ $pelaksanaan->deskripsi }}</textarea>
</div>

<input type="hidden" name="tipe_penetapan" value="pelaksanaan">
<input type="hidden" name="kode_penetapan" value="{{ $pelaksanaan->kode_penetapan }}">