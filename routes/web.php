<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DokumenPublikController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\SpmController;
use App\Http\Controllers\PeningkatanController;
use App\Http\Controllers\VerifikatorController;
use App\Http\Controllers\DashboardController;

// ==================== PUBLIC ROUTES (TANPA LOGIN) ====================
Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

// Dokumen Publik
Route::get('/dokumen-publik', [DokumenPublikController::class, 'index'])->name('dokumen-publik.index');
Route::get('/dokumen-publik/{id}', [DokumenPublikController::class, 'show'])->name('dokumen-publik.show');

// Berita Publik
Route::get('/berita', [LandingPageController::class, 'beritaIndex'])->name('berita.index');
Route::get('/berita/{slug}', [LandingPageController::class, 'beritaShow'])->name('berita.show');

// Halaman Statis
Route::get('/upt', function () {
    return view('upt.index');
})->name('upt.index');

Route::get('/bagian', function () {
    return view('bagian.index');
})->name('bagian.index');

Route::get('/program-studi', function () {
    return view('program-studi.index');
})->name('program-studi.index');

Route::get('/unit-kerja', function () {
    return view('unit-kerja.index');
})->name('unit-kerja.index');

Route::get('/tentang/profil', function () {
    return view('tentang.profil');
})->name('tentang.profil');

Route::get('/tentang/visi-misi', function () {
    return view('tentang.visi-misi');
})->name('tentang.visi-misi');

Route::get('/tentang/struktur-organisasi', function () {
    return view('tentang.sotk');
})->name('tentang.sotk');

// ==================== AUTHENTICATION ROUTES ====================
Route::get('/masuk', [AuthController::class, 'showLoginForm'])->name('masuk');
Route::post('/masuk', [AuthController::class, 'masuk'])->name('masuk.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== UPLOAD DOKUMEN DENGAN KONTEKS ====================
Route::middleware(['auth'])->prefix('upload')->group(function () {
    Route::get('/spmi/penetapan/{id}', [UploadController::class, 'createWithContext'])
        ->name('upload.spmi-penetapan');
    
    Route::get('/spmi/pelaksanaan', [UploadController::class, 'createWithContext'])
        ->name('upload.spmi-pelaksanaan');
    
    Route::get('/spmi/evaluasi', [UploadController::class, 'createWithContext'])
        ->name('upload.spmi-evaluasi');
    
    Route::get('/spmi/pengendalian', [UploadController::class, 'createWithContext'])
        ->name('upload.spmi-pengendalian');
    
    Route::get('/spmi/peningkatan', [UploadController::class, 'createWithContext'])
        ->name('upload.spmi-peningkatan');
});

// ==================== PROTECTED ROUTES (SETELAH LOGIN) ====================
Route::middleware(['auth'])->group(function () {
    
    // ==================== DASHBOARD UTAMA ====================
    // Dashboard berdasarkan role - HAPUS DUPLIKAT
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ==================== ROUTES UMUM UNTUK SEMUA USER ====================
    Route::prefix('dokumen-saya')->name('dokumen-saya.')->group(function () {
        Route::get('/', [UploadController::class, 'index'])->name('index');
        Route::delete('/{id}', [UploadController::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [UploadController::class, 'download'])->name('download');
        Route::get('/preview/{id}', [UploadController::class, 'preview'])->name('preview');
        Route::post('/upload', [UploadController::class, 'store'])->name('upload');
        Route::get('/create', [UploadController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [UploadController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UploadController::class, 'update'])->name('update');
    });
    
    // Profile Routes untuk semua user
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
        Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
    
    // Search Routes untuk semua user
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [SearchController::class, 'index'])->name('index');
        Route::get('/results', [SearchController::class, 'search'])->name('results');
        Route::get('/dokumen/{id}/preview', [SearchController::class, 'preview'])->name('dokumen.preview');
        Route::get('/dokumen/{id}/download', [SearchController::class, 'download'])->name('dokumen.download');
    });
    
    // ==================== SPMI ROUTES (UNTUK SEMUA YANG TERKAIT SPMI) ====================
    Route::prefix('spmi')->name('spmi.')->group(function () {
        
        // ===== PENETAPAN SPMI =====
        Route::prefix('penetapan')->name('penetapan.')->group(function () {
            Route::get('/', [SpmController::class, 'indexPenetapan'])->name('index');
            Route::get('/create', [SpmController::class, 'createPenetapan'])->name('create');
            Route::post('/', [SpmController::class, 'storePenetapan'])->name('store');
            Route::get('/{id}', [SpmController::class, 'showPenetapan'])->name('show');
            Route::get('/{id}/edit', [SpmController::class, 'editPenetapan'])->name('edit');
            Route::put('/{id}', [SpmController::class, 'updatePenetapan'])->name('update');
            Route::delete('/{id}', [SpmController::class, 'destroyPenetapan'])->name('destroy');
            
            // Document management
            Route::post('/{id}/upload', [SpmController::class, 'uploadDokumenPenetapan'])->name('upload');
            Route::get('/{id}/download', [SpmController::class, 'downloadDokumenPenetapan'])->name('download');
            Route::get('/{id}/preview', [SpmController::class, 'previewDokumenPenetapan'])->name('preview');
            Route::delete('/{id}/dokumen', [SpmController::class, 'hapusDokumenPenetapan'])->name('dokumen.hapus');
            
            // AJAX endpoints
            Route::get('/{id}/detail', [SpmController::class, 'getPenetapanData'])->name('ajax.detail');
            Route::get('/{id}/edit-form', [SpmController::class, 'getEditForm'])->name('ajax.edit-form');
            Route::put('/{id}/ajax-update', [SpmController::class, 'updateAjax'])->name('ajax.update');
            Route::get('/{id}/dokumen-list', [SpmController::class, 'getDokumenList'])->name('ajax.dokumen-list');
        });
        
        // ===== PELAKSANAAN SPMI =====
        Route::prefix('pelaksanaan')->name('pelaksanaan.')->group(function () {
            Route::get('/', [SpmController::class, 'indexPelaksanaan'])->name('index');
            Route::get('/create', [SpmController::class, 'createPelaksanaan'])->name('create');
            Route::post('/', [SpmController::class, 'storePelaksanaan'])->name('store');
            Route::get('/{id}', [SpmController::class, 'showPelaksanaan'])->name('show');
            Route::get('/{id}/edit', [SpmController::class, 'editPelaksanaan'])->name('edit');
            Route::put('/{id}', [SpmController::class, 'updatePelaksanaan'])->name('update');
            Route::delete('/{id}', [SpmController::class, 'destroyPelaksanaan'])->name('destroy');
            
            // Document management
            Route::post('/{id}/upload', [SpmController::class, 'uploadDokumenPelaksanaan'])->name('upload');
            Route::get('/{id}/download', [SpmController::class, 'downloadDokumenPelaksanaan'])->name('download');
            Route::get('/{id}/preview', [SpmController::class, 'previewDokumenPelaksanaan'])->name('preview');
            Route::delete('/{id}/dokumen', [SpmController::class, 'hapusDokumenPelaksanaan'])->name('dokumen.hapus');
            
            // AJAX endpoints
            Route::get('/{id}/detail', [SpmController::class, 'getPelaksanaanData'])->name('ajax.detail');
            Route::get('/{id}/edit-form', [SpmController::class, 'getPelaksanaanEditForm'])->name('ajax.edit-form');
            Route::put('/{id}/ajax-update', [SpmController::class, 'updatePelaksanaanAjax'])->name('ajax.update');
            Route::get('/{id}/dokumen-list', [SpmController::class, 'getDokumenListPelaksanaan'])->name('ajax.dokumen-list');
        });
        
        // ===== EVALUASI SPMI =====
        Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
            Route::get('/', [SpmController::class, 'indexEvaluasiFull'])->name('index');
            Route::get('/create', [SpmController::class, 'createEvaluasiFull'])->name('create');
            Route::post('/', [SpmController::class, 'storeEvaluasiFull'])->name('store');
            Route::get('/{id}', [SpmController::class, 'showEvaluasiFull'])->name('show');
            Route::get('/{id}/edit', [SpmController::class, 'editEvaluasiFull'])->name('edit');
            Route::put('/{id}', [SpmController::class, 'updateEvaluasiFull'])->name('update');
            Route::delete('/{id}', [SpmController::class, 'destroyEvaluasiFull'])->name('destroy');
            
            // Document management
            Route::post('/{id}/upload', [SpmController::class, 'uploadDokumenEvaluasi'])->name('upload');
            
            // AJAX endpoints
            Route::get('/{id}/detail', [SpmController::class, 'getEvaluasiData'])->name('ajax.detail');
            Route::get('/{id}/edit-form', [SpmController::class, 'getEvaluasiEditForm'])->name('ajax.edit-form');
            Route::put('/{id}/ajax-update', [SpmController::class, 'updateEvaluasiAjax'])->name('ajax.update');
        });
        
        // ===== PENGENDALIAN =====
        Route::prefix('pengendalian')->name('pengendalian.')->group(function () {
            Route::get('/', [SpmController::class, 'indexPengendalian'])->name('index');
        });
        
        // ===== PENINGKATAN SPMI =====
        Route::prefix('peningkatan')->name('peningkatan.')->group(function () {
            Route::get('/', [PeningkatanController::class, 'index'])->name('index');
            Route::get('/create', [PeningkatanController::class, 'create'])->name('create');
            Route::post('/', [PeningkatanController::class, 'store'])->name('store');
            Route::get('/{id}', [PeningkatanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PeningkatanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PeningkatanController::class, 'update'])->name('update');
            Route::delete('/{id}', [PeningkatanController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/upload', [PeningkatanController::class, 'uploadDokumen'])->name('upload');
        });
        
        // ===== AKREDITASI =====
        Route::prefix('akreditasi')->name('akreditasi.')->group(function () {
            Route::get('/', [SpmController::class, 'indexAkreditasi'])->name('index');
        });
        
        // ===== API ENDPOINTS (untuk AJAX) =====
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/penetapan/data', [SpmController::class, 'getPenetapanData'])->name('penetapan.data');
            Route::get('/penetapan/statistics', [SpmController::class, 'getPenetapanStatistics'])->name('penetapan.statistics');
            Route::get('/pelaksanaan/statistics', [SpmController::class, 'getPelaksanaanStatistics'])->name('pelaksanaan.statistics');
            Route::get('/peningkatan/statistics', [PeningkatanController::class, 'getStatistics'])->name('peningkatan.statistics');
        });
    });
});

// ==================== VERIFIKATOR ROUTES ====================
// Verifikator Routes
Route::prefix('verifikator')->name('verifikator.')->middleware(['auth', 'verifikator'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'verifikatorDashboard'])->name('dashboard');
    
    // Dokumen Routes
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Verifikator\DokumenController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Verifikator\DokumenController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [\App\Http\Controllers\Verifikator\DokumenController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\Verifikator\DokumenController::class, 'reject'])->name('reject');
        Route::post('/{id}/revision', [\App\Http\Controllers\Verifikator\DokumenController::class, 'requestRevision'])->name('revision');
        Route::post('/{id}/comment', [\App\Http\Controllers\Verifikator\DokumenController::class, 'addComment'])->name('comment.add');
        Route::get('/statistics', [\App\Http\Controllers\Verifikator\DokumenController::class, 'statistics'])->name('statistics');
    });
    
    // Review Dokumen
    Route::prefix('review')->name('review.')->group(function () {
        Route::get('/', [VerifikatorController::class, 'index'])->name('index');
        Route::get('/pending', [VerifikatorController::class, 'pending'])->name('pending');
        Route::get('/approved', [VerifikatorController::class, 'approved'])->name('approved');
        Route::get('/rejected', [VerifikatorController::class, 'rejected'])->name('rejected');
        Route::post('/{id}/approve', [VerifikatorController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [VerifikatorController::class, 'reject'])->name('reject');
        Route::get('/{id}/detail', [VerifikatorController::class, 'detail'])->name('detail');
    });
    
    // Dokumen Management
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [VerifikatorController::class, 'dokumen'])->name('index');
        Route::get('/{id}/preview', [VerifikatorController::class, 'preview'])->name('preview');
        Route::get('/{id}/download', [VerifikatorController::class, 'download'])->name('download');
        Route::get('/search', [VerifikatorController::class, 'search'])->name('search');
    });
    
    // Statistik
    Route::prefix('statistik')->name('statistik.')->group(function () {
        Route::get('/', [VerifikatorController::class, 'statistik'])->name('index');
        Route::get('/by-prodi', [VerifikatorController::class, 'statistikByProdi'])->name('by-prodi');
        Route::get('/by-tahapan', [VerifikatorController::class, 'statistikByTahapan'])->name('by-tahapan');
        Route::get('/by-bulan', [VerifikatorController::class, 'statistikByBulan'])->name('by-bulan');
    });
    
    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [VerifikatorController::class, 'laporan'])->name('index');
        Route::get('/export/pdf', [VerifikatorController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [VerifikatorController::class, 'exportExcel'])->name('export.excel');
    });
});

// ==================== USER ROUTES (USER BIASA) ====================
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    
    // Dashboard User
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    
    // Dokumen Saya (user-specific)
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [UploadController::class, 'userDokumen'])->name('index');
        Route::get('/create', [UploadController::class, 'userCreate'])->name('create');
        Route::post('/', [UploadController::class, 'userStore'])->name('store');
        Route::get('/{id}/edit', [UploadController::class, 'userEdit'])->name('edit');
        Route::put('/{id}', [UploadController::class, 'userUpdate'])->name('update');
        Route::get('/status/{status}', [UploadController::class, 'byStatus'])->name('by-status');
    });
    
    // Statistik User
    Route::get('/statistik', function() {
        return view('user.statistik');
    })->name('statistik');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/home', function () {
        return view('admin.dashboard');
    })->name('home');
    
    // ==================== USER MANAGEMENT ====================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/activate', [App\Http\Controllers\Admin\UserController::class, 'activate'])->name('activate');
        Route::put('/{id}/deactivate', [App\Http\Controllers\Admin\UserController::class, 'deactivate'])->name('deactivate');
        Route::put('/{id}/change-role', [App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('change-role');
    });
    
    // ==================== SETTINGS MANAGEMENT ====================
    Route::prefix('settings')->name('settings.')->group(function () {
        
        // IKU Management
        Route::prefix('iku')->group(function () {
            Route::get('/', [SettingController::class, 'indexIku'])->name('iku.index');
            Route::get('/create', [SettingController::class, 'createIku'])->name('iku.create');
            Route::post('/', [SettingController::class, 'storeIku'])->name('iku.store');
            Route::get('/{id}/edit', [SettingController::class, 'editIku'])->name('iku.edit');
            Route::put('/{id}', [SettingController::class, 'updateIku'])->name('iku.update');
            Route::delete('/{id}', [SettingController::class, 'destroyIku'])->name('iku.destroy');
        });

        // Unit Kerja Management
        Route::prefix('unit-kerja')->group(function () {
            Route::get('/', [SettingController::class, 'indexUnitKerja'])->name('unit-kerja.index');
            Route::get('/create', [SettingController::class, 'createUnitKerja'])->name('unit-kerja.create');
            Route::post('/', [SettingController::class, 'storeUnitKerja'])->name('unit-kerja.store');
            Route::get('/{id}/edit', [SettingController::class, 'editUnitKerja'])->name('unit-kerja.edit');
            Route::put('/{id}', [SettingController::class, 'updateUnitKerja'])->name('unit-kerja.update');
            Route::delete('/{id}', [SettingController::class, 'destroyUnitKerja'])->name('unit-kerja.destroy');
        });
        
        // Prodi Management
        Route::prefix('prodi')->group(function () {
            Route::get('/', [SettingController::class, 'indexProdi'])->name('prodi.index');
            Route::get('/create', [SettingController::class, 'createProdi'])->name('prodi.create');
            Route::post('/', [SettingController::class, 'storeProdi'])->name('prodi.store');
            Route::get('/{id}/edit', [SettingController::class, 'editProdi'])->name('prodi.edit');
            Route::put('/{id}', [SettingController::class, 'updateProdi'])->name('prodi.update');
            Route::delete('/{id}', [SettingController::class, 'destroyProdi'])->name('prodi.destroy');
        });
        
        // SPMI Settings
        Route::prefix('spmi')->group(function () {
            Route::get('/', [SettingController::class, 'indexSpm'])->name('spmi.index');
            Route::put('/update', [SettingController::class, 'updateSpm'])->name('spmi.update');
        });
        
        // System Settings
        Route::get('/system', [SettingController::class, 'system'])->name('system');
        Route::put('/system/update', [SettingController::class, 'updateSystem'])->name('system.update');
    });
    
    // ==================== CONTENT MANAGEMENT ====================
    // Berita Management
    Route::prefix('berita')->group(function () {
        Route::get('/', [BeritaController::class, 'index'])->name('berita.index');
        Route::get('/create', [BeritaController::class, 'create'])->name('berita.create');
        Route::post('/', [BeritaController::class, 'store'])->name('berita.store');
        Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
        Route::put('/{id}', [BeritaController::class, 'update'])->name('berita.update');
        Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');
        Route::put('/{id}/publish', [BeritaController::class, 'publish'])->name('berita.publish');
        Route::put('/{id}/unpublish', [BeritaController::class, 'unpublish'])->name('berita.unpublish');
    });

    // Jadwal Management  
    Route::prefix('jadwal')->group(function () {
        Route::get('/', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    });
    
    // ==================== DOKUMEN MANAGEMENT ====================
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DokumenController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\DokumenController::class, 'show'])->name('show');
        Route::delete('/{id}', [App\Http\Controllers\Admin\DokumenController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\DokumenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\DokumenController::class, 'update'])->name('update');
        Route::put('/{id}/toggle-public', [App\Http\Controllers\Admin\DokumenController::class, 'togglePublic'])->name('toggle-public');
        Route::get('/export', [App\Http\Controllers\Admin\DokumenController::class, 'export'])->name('export');
    });
    
    // ==================== REPORT & ANALYTICS ====================
    Route::prefix('reports')->name('reports.')->group(function () {
        // SPMI Reports
        Route::get('/spmi-penetapan', [SpmController::class, 'reportPenetapan'])->name('spmi-penetapan');
        Route::get('/spmi-summary', [SpmController::class, 'reportSummary'])->name('spmi-summary');
        Route::get('/spmi-peningkatan', [PeningkatanController::class, 'exportExcel'])->name('spmi-peningkatan');
        
        // User Activity Reports
        Route::get('/user-activity', [App\Http\Controllers\Admin\ReportController::class, 'userActivity'])->name('user-activity');
        Route::get('/dokumen-activity', [App\Http\Controllers\Admin\ReportController::class, 'dokumenActivity'])->name('dokumen-activity');
        Route::get('/verification-stats', [App\Http\Controllers\Admin\ReportController::class, 'verificationStats'])->name('verification-stats');
        
        // Export Reports
        Route::get('/export/users', [App\Http\Controllers\Admin\ReportController::class, 'exportUsers'])->name('export.users');
        Route::get('/export/dokumen', [App\Http\Controllers\Admin\ReportController::class, 'exportDokumen'])->name('export.dokumen');
        Route::get('/export/verification', [App\Http\Controllers\Admin\ReportController::class, 'exportVerification'])->name('export.verification');
    });
    
    // ==================== SYSTEM TOOLS ====================
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/backup', [App\Http\Controllers\Admin\ToolController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [App\Http\Controllers\Admin\ToolController::class, 'createBackup'])->name('backup.create');
        Route::get('/logs', [App\Http\Controllers\Admin\ToolController::class, 'logs'])->name('logs');
        Route::get('/cache-clear', [App\Http\Controllers\Admin\ToolController::class, 'cacheClear'])->name('cache-clear');
        Route::get('/storage-link', [App\Http\Controllers\Admin\ToolController::class, 'storageLink'])->name('storage-link');
    });
});

// ==================== DEBUG & TESTING ROUTES ====================
Route::get('/test-peningkatan', function() {
    $totalPeningkatan = 15;
    $peningkatanAktif = 8;
    $dokumenValid = 5;
    $dokumenBelumValid = 7;
    
    return view('dashboard.spmi.peningkatan.index', compact(
        'totalPeningkatan',
        'peningkatanAktif',
        'dokumenValid',
        'dokumenBelumValid'
    ));
})->name('test.peningkatan');

Route::get('/test-role-redirect', function() {
    return view('test.role-redirect');
})->name('test.role-redirect');

// ==================== ERROR PAGES ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});