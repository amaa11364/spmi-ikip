<div class="mb-3">
    <label class="form-label">Judul Akreditasi <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="judul_akreditasi" value="{{ $akreditasi->judul_akreditasi }}" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Jenis Akreditasi <span class="text-danger">*</span></label>
        <select class="form-select" name="jenis_akreditasi" required>
            <option value="institusi" {{ $akreditasi->jenis_akreditasi == 'institusi' ? 'selected' : '' }}>Akreditasi Institusi</option>
            <option value="program_studi" {{ $akreditasi->jenis_akreditasi == 'program_studi' ? 'selected' : '' }}>Akreditasi Program Studi</option>
            <option value="fakultas" {{ $akreditasi->jenis_akreditasi == 'fakultas' ? 'selected' : '' }}>Akreditasi Fakultas</option>
            <option value="laboratorium" {{ $akreditasi->jenis_akreditasi == 'laboratorium' ? 'selected' : '' }}>Akreditasi Laboratorium</option>
            <option value="lainnya" {{ $akreditasi->jenis_akreditasi == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Lembaga Akreditasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="lembaga_akreditasi" value="{{ $akreditasi->lembaga_akreditasi }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="tahun" value="{{ $akreditasi->tahun }}" min="2000" max="{{ date('Y') + 5 }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Peringkat</label>
        <select class="form-select" name="peringkat">
            <option value="">Pilih Peringkat</option>
            <option value="A" {{ $akreditasi->peringkat == 'A' ? 'selected' : '' }}>A</option>
            <option value="B" {{ $akreditasi->peringkat == 'B' ? 'selected' : '' }}>B</option>
            <option value="C" {{ $akreditasi->peringkat == 'C' ? 'selected' : '' }}>C</option>
            <option value="Unggul" {{ $akreditasi->peringkat == 'Unggul' ? 'selected' : '' }}>Unggul</option>
            <option value="Baik" {{ $akreditasi->peringkat == 'Baik' ? 'selected' : '' }}>Baik</option>
            <option value="Cukup" {{ $akreditasi->peringkat == 'Cukup' ? 'selected' : '' }}>Cukup</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Skor</label>
        <input type="number" class="form-control" name="skor" value="{{ $akreditasi->skor }}" step="0.01" min="0" max="100" placeholder="Contoh: 85.5">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tanggal Akreditasi</label>
        <input type="date" class="form-control" name="tanggal_akreditasi" value="{{ $akreditasi->tanggal_akreditasi ? $akreditasi->tanggal_akreditasi->format('Y-m-d') : '' }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tanggal Berlaku</label>
        <input type="date" class="form-control" name="tanggal_berlaku" value="{{ $akreditasi->tanggal_berlaku ? $akreditasi->tanggal_berlaku->format('Y-m-d') : '' }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tanggal Kadaluarsa</label>
        <input type="date" class="form-control" name="tanggal_kadaluarsa" value="{{ $akreditasi->tanggal_kadaluarsa ? $akreditasi->tanggal_kadaluarsa->format('Y-m-d') : '' }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" name="status" required>
            <option value="aktif" {{ $akreditasi->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="berjalan" {{ $akreditasi->status == 'berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
            <option value="selesai" {{ $akreditasi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="tidak_akreditasi" {{ $akreditasi->status == 'tidak_akreditasi' ? 'selected' : '' }}>Tidak Terakreditasi</option>
            <option value="kadaluarsa" {{ $akreditasi->status == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Dokumen</label>
        <select class="form-select" name="status_dokumen">
            <option value="valid" {{ $akreditasi->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="belum_valid" {{ $akreditasi->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
            <option value="dalam_review" {{ $akreditasi->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Unit Kerja</label>
        <select class="form-select" name="unit_kerja_id">
            <option value="">Pilih Unit Kerja</option>
            @foreach($unitKerjas as $unit)
                <option value="{{ $unit->id }}" {{ $akreditasi->unit_kerja_id == $unit->id ? 'selected' : '' }}>
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
                <option value="{{ $iku->id }}" {{ $akreditasi->iku_id == $iku->id ? 'selected' : '' }}>
                    {{ $iku->kode }} - {{ $iku->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor Sertifikat</label>
        <input type="text" class="form-control" name="no_sertifikat" value="{{ $akreditasi->no_sertifikat }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Penanggung Jawab</label>
        <input type="text" class="form-control" name="penanggung_jawab" value="{{ $akreditasi->penanggung_jawab }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea class="form-control" name="deskripsi" rows="3">{{ $akreditasi->deskripsi }}</textarea>
</div>

<input type="hidden" name="kode_akreditasi" value="{{ $akreditasi->kode_akreditasi }}">