<!-- Modal Request Revision -->
<div class="modal fade" id="revisionModal{{ $dokumen->id }}" tabindex="-1" aria-labelledby="revisionModalLabel{{ $dokumen->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('verifikator.dokumen.revision', $dokumen->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="revisionModalLabel{{ $dokumen->id }}">
                        <i class="fas fa-edit"></i> Minta Revisi Dokumen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Berikan instruksi revisi untuk dokumen <strong>{{ $dokumen->judul ?? $dokumen->nama_dokumen }}</strong>:</p>
                    
                    <div class="mb-3">
                        <label for="reason{{ $dokumen->id }}" class="form-label">Instruksi Revisi <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason{{ $dokumen->id }}" class="form-control" rows="4" required 
                                  placeholder="Jelaskan secara detail apa yang perlu direvisi..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deadline{{ $dokumen->id }}" class="form-label">Deadline Revisi</label>
                        <input type="date" name="deadline" id="deadline{{ $dokumen->id }}" class="form-control" 
                               value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        <small class="text-muted">Batas waktu pengumpulan revisi</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comment{{ $dokumen->id }}" class="form-label">Komentar Tambahan (Opsional)</label>
                        <textarea name="comment" id="comment{{ $dokumen->id }}" class="form-control" rows="2" 
                                  placeholder="Tambahkan komentar..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane"></i> Kirim Permintaan Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>