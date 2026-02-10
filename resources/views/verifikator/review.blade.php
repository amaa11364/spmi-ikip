<!-- resources/views/verifikator/review.blade.php -->
<form method="GET" class="mb-4">
    <select name="prodi_id" class="form-select" onchange="this.form.submit()">
        <option value="">-- Semua Prodi --</option>
        @foreach($prodis as $prodi)
            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                {{ $prodi->nama_prodi }}
            </option>
        @endforeach
    </select>
</form>

<!-- Tampilkan dokumen sesuai filter -->