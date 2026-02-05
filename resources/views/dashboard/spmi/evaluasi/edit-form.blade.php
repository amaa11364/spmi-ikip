<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Komponen Evaluasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nama_komponen" value="{{ $evaluasi->nama_komponen }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
        <select class="form-select" name="tipe_evaluasi" required>
            <option value="internal" {{ $evaluasi->tipe_evaluasi == 'internal' ? 'selected' : '' }}>Evaluasi Internal</option>
            <option value="eksternal" {{ $evaluasi->tipe_evaluasi == 'eksternal' ? 'selected' : '' }}>Evaluasi Eksternal</option>
            <option value="berkala" {{ $evaluasi->tipe_evaluasi == 'berkala' ? 'selected' : '' }}>Evaluasi Berkala</option>
            <option value="khusus" {{ $evaluasi->tipe_evaluasi == 'khusus' ? 'selected' : '' }}>Evaluasi Khusus</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="tahun" value="{{ $evaluasi->tahun }}" min="2000" max="{{ date('Y') + 5 }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Periode <span class="text-danger">*</span></label>
        <select class="form-select" name="periode" required>
            <option value="Semester I" {{ $evaluasi->periode == 'Semester I' ? 'selected' : '' }}>Semester I</option>
            <option value="Semester II" {{ $evaluasi->periode == 'Semester II' ? 'selected' : '' }}>Semester II</option>
            <option value="Triwulan I" {{ $evaluasi->periode == 'Triwulan I' ? 'selected' : '' }}>Triwulan I</option>
            <option value="Triwulan II" {{ $evaluasi->periode == 'Triwulan II' ? 'selected' : '' }}>Triwulan II</option>
            <option value="Triwulan III" {{ $evaluasi->periode == 'Triwulan III' ? 'selected' : '' }}>Triwulan III</option>
            <option value="Triwulan IV" {{ $evaluasi->periode == 'Triwulan IV' ? 'selected' : '' }}>Triwulan IV</option>
            <option value="Tahunan" {{ $evaluasi->periode == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" name="status" required>
            <option value="draft" {{ $evaluasi->status == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="proses" {{ $evaluasi->status == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
            <option value="selesai" {{ $evaluasi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="ditunda" {{ $evaluasi->status == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Dokumen</label>
        <select class="form-select" name="status_dokumen">
            <option value="valid" {{ $evaluasi->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="belum_valid" {{ $evaluasi->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
            <option value="dalam_review" {{ $evaluasi->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
        </select>
    </div>
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
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Penanggung Jawab</label>
        <input type="text" class="form-control" name="penanggung_jawab" value="{{ $evaluasi->penanggung_jawab }}">
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
    <label class="form-label">Tanggal Evaluasi</label>
    <input type="date" class="form-control" name="tanggal_evaluasi" 
           value="{{ $evaluasi->tanggal_evaluasi ? $evaluasi->tanggal_evaluasi->format('Y-m-d') : '' }}">
</div>

<div class="mb-3">
    <label class="form-label">Hasil Evaluasi (Ringkasan)</label>
    <textarea class="form-control" name="hasil_evaluasi" rows="2">{{ $evaluasi->hasil_evaluasi }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Rekomendasi</label>
    <textarea class="form-control" name="rekomendasi" rows="2">{{ $evaluasi->rekomendasi }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi Lengkap</label>
    <textarea class="form-control" name="deskripsi" rows="3">{{ $evaluasi->deskripsi }}</textarea>
</div>

<input type="hidden" name="kode_evaluasi" value="{{ $evaluasi->kode_evaluasi }}">