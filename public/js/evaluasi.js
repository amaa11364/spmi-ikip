// Ganti file evaluasi.js dengan ini

// View Evaluasi
function viewEvaluasi(id) {
    showLoading(true);
    
    fetch(`/spmi/evaluasi/${id}/detail-ajax`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        // Cek jika response bukan JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new TypeError("Server returned non-JSON response");
        }
        return response.json();
    })
    .then(data => {
        showLoading(false);
        
        if (data.success) {
            document.getElementById('viewModalBody').innerHTML = data.html;
            var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
            viewModal.show();
        } else {
            showError('Gagal memuat data: ' + (data.message || 'Data tidak ditemukan'));
        }
    })
    .catch(error => {
        showLoading(false);
        console.error('View error:', error);
        showError('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
    });
}

// Edit Evaluasi
function editEvaluasi(id) {
    showLoading(true);
    
    fetch(`/spmi/evaluasi/${id}/edit-ajax`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new TypeError("Server returned non-JSON response");
        }
        return response.json();
    })
    .then(data => {
        showLoading(false);
        
        if (data.success) {
            document.getElementById('editModalBody').innerHTML = data.html;
            document.getElementById('editForm').action = `/spmi/evaluasi/${id}`;
            
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
            
            // Setup form submission
            setupEditForm(id);
        } else {
            showError('Gagal memuat form edit: ' + (data.message || 'Data tidak ditemukan'));
        }
    })
    .catch(error => {
        showLoading(false);
        console.error('Edit error:', error);
        showError('Terjadi kesalahan saat memuat form. Silakan coba lagi.');
    });
}

// Setup form edit submission
function setupEditForm(id) {
    const form = document.getElementById('editForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new TypeError("Server returned non-JSON response");
                }
                return response.json();
            })
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                if (data.success) {
                    showSuccess('Data berhasil diperbarui!');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showError('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                console.error('Update error:', error);
                showError('Terjadi kesalahan saat menyimpan. Silakan coba lagi.');
            });
        });
    }
}

// Helper functions
function showLoading(show) {
    if (show) {
        // Tambahkan loading overlay jika belum ada
        if (!document.getElementById('loadingOverlay')) {
            const overlay = document.createElement('div');
            overlay.id = 'loadingOverlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            `;
            overlay.innerHTML = `
                <div class="spinner-border text-white" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            document.body.appendChild(overlay);
        }
    } else {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.remove();
        }
    }
}

function showError(message) {
    // Gunakan Toast atau Alert Bootstrap
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        // Implement toast notification
    } else {
        alert(message);
    }
}

function showSuccess(message) {
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        // Implement toast notification
    } else {
        alert(message);
    }
}

// Upload inline file
function uploadInlineFile(evaluasiId, button) {
    const form = document.getElementById(`uploadFormEvaluasi${evaluasiId}`);
    if (!form) return;
    
    const formData = new FormData(form);
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
    button.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new TypeError("Server returned non-JSON response");
        }
        return response.json();
    })
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showSuccess('Dokumen berhasil diupload!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showError('Upload gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        button.innerHTML = originalText;
        button.disabled = false;
        console.error('Upload error:', error);
        showError('Terjadi kesalahan saat upload.');
    });
}

// Toggle upload modal
function toggleUploadModal(evaluasiId) {
    const modal = document.getElementById(`uploadModalEvaluasi${evaluasiId}`);
    if (modal) {
        modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
    }
}