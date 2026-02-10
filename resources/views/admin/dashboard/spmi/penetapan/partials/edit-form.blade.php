<div class="mb-3">
    <label class="form-label">Nama Komponen <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="nama_komponen" value="{{ $penetapan->nama_komponen }}" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tipe Penetapan <span class="text-danger">*</span></label>
        <select class="form-select" name="tipe_penetapan" required>
            <option value="pengelolaan" {{ $penetapan->tipe_penetapan == 'pengelolaan' ? 'selected' : '' }}>Pengelolaan SPMI</option>
            <option value="organisasi" {{ $penetapan->tipe_penetapan == 'organisasi' ? 'selected' : '' }}>Organisasi SPMI</option>
            <option value="pelaksanaan" {{ $penetapan->tipe_penetapan == 'pelaksanaan' ? 'selected' : '' }}>Pelaksanaan SPMI</option>
            <option value="evaluasi" {{ $penetapan->tipe_penetapan == 'evaluasi' ? 'selected' : '' }}>Evaluasi SPMI</option>
            <option value="pengendalian" {{ $penetapan->tipe_penetapan == 'pengendalian' ? 'selected' : '' }}>Pengendalian SPMI</option>
            <option value="peningkatan" {{ $penetapan->tipe_penetapan == 'peningkatan' ? 'selected' : '' }}>Peningkatan SPMI</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="tahun" value="{{ $penetapan->tahun }}" min="2000" max="{{ date('Y') + 5 }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" name="status" required>
            <option value="aktif" {{ $penetapan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ $penetapan->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="revisi" {{ $penetapan->status == 'revisi' ? 'selected' : '' }}>Revisi</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Dokumen</label>
        <select class="form-select" name="status_dokumen">
            <option value="valid" {{ $penetapan->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="belum_valid" {{ $penetapan->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
            <option value="dalam_review" {{ $penetapan->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Unit Kerja</label>
        <select class="form-select" name="unit_kerja_id">
            <option value="">Pilih Unit Kerja</option>
            @foreach($unitKerjas as $unit)
                <option value="{{ $unit->id }}" {{ $penetapan->unit_kerja_id == $unit->id ? 'selected' : '' }}>
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
                <option value="{{ $iku->id }}" {{ $penetapan->iku_id == $iku->id ? 'selected' : '' }}>
                    {{ $iku->kode }} - {{ $iku->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Penanggung Jawab</label>
    <input type="text" class="form-control" name="penanggung_jawab" value="{{ $penetapan->penanggung_jawab }}">
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea class="form-control" name="deskripsi" rows="3">{{ $penetapan->deskripsi }}</textarea>
</div>

<input type="hidden" name="kode_penetapan" value="{{ $penetapan->kode_penetapan }}">