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
use App\Http\Controllers\EvaluasiSpmController;
use App\Http\Controllers\PengendalianSpmController;

// ==================== DOKUMEN PUBLIK ROUTES (TANPA LOGIN) ====================
Route::get('/dokumen-publik', [DokumenPublikController::class, 'index'])->name('dokumen-publik.index');
Route::get('/dokumen-publik/{id}', [DokumenPublikController::class, 'show'])->name('dokumen-publik.show');

// ==================== LANDING PAGE & PUBLIC ROUTES ====================
Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

// ==================== PUBLIC BERITA ROUTES ====================
Route::get('/berita', [LandingPageController::class, 'beritaIndex'])->name('berita.index');
Route::get('/berita/{slug}', [LandingPageController::class, 'beritaShow'])->name('berita.show');

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
    
    // ==================== SPMI ROUTES (CRUD LENGKAP) ====================
    Route::prefix('spmi')->name('spmi.')->group(function () {
        
        // ===== PENETAPAN SPMI - CRUD LENGKAP =====
        Route::prefix('penetapan')->name('penetapan.')->group(function () {
            // ===== MAIN CRUD ROUTES =====
            Route::get('/', [SpmController::class, 'indexPenetapan'])->name('index');
            Route::get('/create', [SpmController::class, 'createPenetapan'])->name('create');
            Route::get('/{id}/dokumen-terkait', [SpmController::class, 'dokumenTerkait'])->name('dokumen-terkait');
            Route::post('/', [SpmController::class, 'storePenetapan'])->name('store');
            Route::get('/{id}', [SpmController::class, 'showPenetapan'])->name('show');
            Route::get('/{id}/edit', [SpmController::class, 'editPenetapan'])->name('edit');
            Route::put('/{id}', [SpmController::class, 'updatePenetapan'])->name('update');
            Route::delete('/{id}', [SpmController::class, 'destroyPenetapan'])->name('destroy');
            
            
            // Restore soft deleted
            Route::post('/{id}/restore', [SpmController::class, 'restorePenetapan'])->name('restore');
            
            // Document management
            Route::post('/{id}/upload', [SpmController::class, 'uploadDokumenPenetapan'])->name('upload');
            Route::get('/{id}/download', [SpmController::class, 'downloadDokumenPenetapan'])->name('download');
            Route::get('/{id}/preview', [SpmController::class, 'previewDokumenPenetapan'])->name('preview');
            Route::delete('/{id}/dokumen', [SpmController::class, 'hapusDokumenPenetapan'])->name('dokumen.hapus');
            
            // Status management
            Route::put('/{id}/status-dokumen', [SpmController::class, 'updateStatusDokumenPenetapan'])->name('status.update');
            
            // Export routes
            Route::get('/export/excel', [SpmController::class, 'exportExcelPenetapan'])->name('export.excel');
            Route::get('/export/pdf', [SpmController::class, 'exportPdfPenetapan'])->name('export.pdf');
            
            // AJAX endpoints
            Route::get('/{id}/detail', [SpmController::class, 'getPenetapanData'])->name('ajax.detail');
            Route::get('/{id}/edit-form', [SpmController::class, 'getEditFormPenetapan'])->name('ajax.edit-form');
            Route::put('/{id}/ajax-update', [SpmController::class, 'updatePenetapanAjax'])->name('ajax.update');
            Route::get('/{id}/dokumen-list', [SpmController::class, 'getDokumenListPenetapan'])->name('ajax.dokumen-list');
            Route::post('/bulk-action', [SpmController::class, 'bulkActionPenetapan'])->name('bulk-action');
            Route::get('/{id}/dokumen-detail', [SpmController::class, 'dokumenDetailAjax'])->name('ajax.dokumen-detail');
        });
        
        // ===== PELAKSANAAN SPMI - CRUD LENGKAP =====
        Route::prefix('pelaksanaan')->name('pelaksanaan.')->group(function () {
            // ===== MAIN CRUD ROUTES =====
            Route::get('/', [SpmController::class, 'indexPelaksanaan'])->name('index');
            Route::get('/create', [SpmController::class, 'createPelaksanaan'])->name('create');
            Route::post('/', [SpmController::class, 'storePelaksanaan'])->name('store');
            Route::get('/{id}', [SpmController::class, 'showPelaksanaan'])->name('show');
            Route::get('/{id}/edit', [SpmController::class, 'editPelaksanaan'])->name('edit');
            Route::put('/{id}', [SpmController::class, 'updatePelaksanaan'])->name('update');
            Route::delete('/{id}', [SpmController::class, 'destroyPelaksanaan'])->name('destroy');
            
            // Restore soft deleted
            Route::post('/{id}/restore', [SpmController::class, 'restorePelaksanaan'])->name('restore');
            
            // Document management
            Route::post('/{id}/upload', [SpmController::class, 'uploadDokumenPelaksanaan'])->name('upload');
            Route::get('/{id}/download', [SpmController::class, 'downloadDokumenPelaksanaan'])->name('download');
            Route::get('/{id}/preview', [SpmController::class, 'previewDokumenPelaksanaan'])->name('preview');
            Route::delete('/{id}/dokumen', [SpmController::class, 'hapusDokumenPelaksanaan'])->name('dokumen.hapus');
            
            // Status management
            Route::put('/{id}/status-dokumen', [SpmController::class, 'updateStatusDokumenPelaksanaan'])->name('status.update');
            
            // Export routes
            Route::get('/export/excel', [SpmController::class, 'exportExcelPelaksanaan'])->name('export.excel');
            
            // AJAX endpoints
            Route::get('/{id}/detail', [SpmController::class, 'getPelaksanaanData'])->name('ajax.detail');
            Route::get('/{id}/edit-form', [SpmController::class, 'getEditFormPelaksanaan'])->name('ajax.edit-form');
            Route::put('/{id}/ajax-update', [SpmController::class, 'updatePelaksanaanAjax'])->name('ajax.update');
            Route::get('/{id}/dokumen-list', [SpmController::class, 'getDokumenListPelaksanaan'])->name('ajax.dokumen-list');
            Route::post('/bulk-action', [SpmController::class, 'bulkActionPelaksanaan'])->name('bulk-action');
        });
        
        // ===== EVALUASI SPMI - CRUD LENGKAP =====
Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
    // ===== MAIN CRUD ROUTES =====
    Route::get('/', [EvaluasiSpmController::class, 'index'])->name('index');
    Route::get('/create', [EvaluasiSpmController::class, 'create'])->name('create');
    Route::post('/', [EvaluasiSpmController::class, 'store'])->name('store');
    Route::get('/{id}', [EvaluasiSpmController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [EvaluasiSpmController::class, 'edit'])->name('edit');
    Route::put('/{id}', [EvaluasiSpmController::class, 'update'])->name('update');
    Route::delete('/{id}', [EvaluasiSpmController::class, 'destroy'])->name('destroy');
    
    // Restore soft deleted
    Route::post('/{id}/restore', [EvaluasiSpmController::class, 'restoreEvaluasi'])->name('restore');
    
    // Document management
    Route::post('/{id}/upload', [EvaluasiSpmController::class, 'uploadDokumenEvaluasi'])->name('upload');
    Route::get('/{id}/download', [EvaluasiSpmController::class, 'downloadDokumenEvaluasi'])->name('download');
    Route::get('/{id}/preview', [EvaluasiSpmController::class, 'previewDokumenEvaluasi'])->name('preview');
    Route::delete('/{id}/dokumen', [EvaluasiSpmController::class, 'hapusDokumenEvaluasi'])->name('dokumen.hapus');
    
    // Status management
    Route::put('/{id}/status-dokumen', [EvaluasiSpmController::class, 'updateStatusDokumenEvaluasi'])->name('status.update');
    
    // Export routes
    Route::get('/export/excel', [EvaluasiSpmController::class, 'exportExcelEvaluasi'])->name('export.excel');
    // AJAX endpoints untuk evaluasi
Route::get('/{id}/detail', [EvaluasiSpmController::class, 'getEvaluasiData'])->name('ajax.detail');
Route::get('/{id}/edit-form', [EvaluasiSpmController::class, 'getEditFormEvaluasi'])->name('ajax.edit-form');
Route::put('/{id}/ajax-update', [EvaluasiSpmController::class, 'updateEvaluasiAjax'])->name('ajax.update');
Route::get('/{id}/dokumen-list', [EvaluasiSpmController::class, 'getDokumenListEvaluasi'])->name('ajax.dokumen-list');
Route::get('/export/excel', [EvaluasiSpmController::class, 'exportExcelEvaluasi'])->name('export.excel');
});
        
        // ===== PENINGKATAN SPMI - CRUD LENGKAP =====
Route::prefix('peningkatan')->name('peningkatan.')->group(function () {
    // ===== MAIN CRUD ROUTES =====
    Route::get('/', [PeningkatanController::class, 'index'])->name('index');
    Route::get('/create', [PeningkatanController::class, 'create'])->name('create');
    Route::post('/', [PeningkatanController::class, 'store'])->name('store');
    Route::get('/{id}', [PeningkatanController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PeningkatanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PeningkatanController::class, 'update'])->name('update');
    Route::delete('/{id}', [PeningkatanController::class, 'destroy'])->name('destroy');
    
    // Restore soft deleted
    Route::post('/{id}/restore', [PeningkatanController::class, 'restore'])->name('restore');
    
    // Document management
    Route::post('/{id}/upload', [PeningkatanController::class, 'uploadDokumen'])->name('upload');
    Route::get('/{id}/download', [PeningkatanController::class, 'downloadDokumen'])->name('download');
    Route::get('/{id}/preview', [PeningkatanController::class, 'previewDokumen'])->name('preview');
    Route::delete('/{id}/dokumen', [PeningkatanController::class, 'hapusDokumen'])->name('dokumen.hapus');
    
    // Status management
    Route::put('/{id}/status-dokumen', [PeningkatanController::class, 'updateStatusDokumen'])->name('status.update');
    
    // Bulk operations
    Route::put('/bulk/progress', [PeningkatanController::class, 'updateProgressBulk'])->name('bulk.progress');
    
    // AJAX endpoints
    Route::get('/{id}/detail', [PeningkatanController::class, 'getPeningkatanData'])->name('ajax.detail');
    Route::get('/{id}/edit-form', [PeningkatanController::class, 'getEditForm'])->name('ajax.edit-form');
    Route::put('/{id}/ajax-update', [PeningkatanController::class, 'updateAjax'])->name('ajax.update');
    Route::get('/{id}/dokumen-list', [PeningkatanController::class, 'getDokumenList'])->name('ajax.dokumen-list');
    Route::get('/{id}/sync-dokumen', [PeningkatanController::class, 'syncDokumenRelationships'])->name('ajax.sync-dokumen');
    Route::get('/{id}/export-data', [PeningkatanController::class, 'getExportData'])->name('ajax.export-data');
    
    // Statistics & reports
    Route::get('/statistics/data', [PeningkatanController::class, 'getStatistics'])->name('statistics');
    Route::get('/dashboard/summary', [PeningkatanController::class, 'getDashboardSummary'])->name('dashboard.summary');
    Route::get('/tahun/list', [PeningkatanController::class, 'getTahunList'])->name('tahun.list');
    Route::get('/status/{status}', [PeningkatanController::class, 'getByStatus'])->name('by.status');
    Route::get('/progress/filter', [PeningkatanController::class, 'getByProgress'])->name('by.progress');
    
    // Export
    Route::get('/export/excel', [PeningkatanController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [PeningkatanController::class, 'exportPdf'])->name('export.pdf');
});

       // ===== PENGENDALIAN SPMI - CONTROLLER TERPISAH =====
Route::prefix('pengendalian')->name('pengendalian.')->group(function () {
    // ===== MAIN CRUD ROUTES =====
    Route::get('/', [PengendalianSpmController::class, 'index'])->name('index');
    Route::get('/create', [PengendalianSpmController::class, 'create'])->name('create');
    Route::post('/', [PengendalianSpmController::class, 'store'])->name('store');
    Route::get('/{id}', [PengendalianSpmController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PengendalianSpmController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PengendalianSpmController::class, 'update'])->name('update');
    Route::delete('/{id}', [PengendalianSpmController::class, 'destroy'])->name('destroy');
    
    // Restore soft deleted
    Route::post('/{id}/restore', [PengendalianSpmController::class, 'restore'])->name('restore');
    
    // Document management
    Route::post('/{id}/upload', [PengendalianSpmController::class, 'uploadDokumen'])->name('upload');
    Route::get('/{id}/download', [PengendalianSpmController::class, 'downloadDokumen'])->name('download');
    
    // Status management
    Route::put('/{id}/status-dokumen', [PengendalianSpmController::class, 'updateStatusDokumen'])->name('status.update');
    
    // AJAX endpoints
    Route::get('/{id}/detail', [PengendalianSpmController::class, 'getPengendalianData'])->name('ajax.detail');
    Route::get('/{id}/edit-form', [PengendalianSpmController::class, 'getEditForm'])->name('ajax.edit-form');
    Route::put('/{id}/ajax-update', [PengendalianSpmController::class, 'updateAjax'])->name('ajax.update');
});

// API Endpoint untuk statistics
Route::get('/spmi/api/pengendalian/statistics', [PengendalianSpmController::class, 'getStatistics'])->name('spmi.api.pengendalian.statistics');
        // ===== PENINGKATAN =====
        Route::prefix('peningkatan')->name('peningkatan.')->group(function () {
            Route::get('/', [SpmController::class, 'indexPeningkatan'])->name('index');
        });

        
        // ===== AKREDITASI =====
        Route::prefix('akreditasi')->name('akreditasi.')->group(function () {
            Route::get('/', [SpmController::class, 'indexAkreditasi'])->name('index');
        });
        
        // ===== SPMI DASHBOARD =====
        Route::get('/dashboard', function () {
            return view('dashboard.spmi.dashboard');
        })->name('dashboard');
        
        // ===== REPORTS =====
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/penetapan', [SpmController::class, 'reportPenetapan'])->name('penetapan');
            Route::get('/summary', [SpmController::class, 'reportSummary'])->name('summary');
        });
        
        // ===== API ENDPOINTS (untuk AJAX) =====
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/penetapan/statistics', [SpmController::class, 'getPenetapanStatisticsAjax'])->name('penetapan.statistics');
            Route::get('/pelaksanaan/statistics', [SpmController::class, 'getPelaksanaanStatisticsAjax'])->name('pelaksanaan.statistics');
            Route::get('/evaluasi/statistics', [SpmController::class, 'getEvaluasiStatisticsAjax'])->name('evaluasi.statistics');
            Route::get('/pengendalian/statistics', [SpmController::class, 'getPengendalianStatisticsAjax'])->name('pengendalian.statistics');
        });
    });
    
    // ==================== ADMIN ROUTES ====================
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        // Dashboard Routes
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        
        Route::get('/home', function () {
            return view('dashboard');
        });

        // Search Routes (Protected)
        Route::prefix('search')->group(function () {
            Route::get('/', [SearchController::class, 'index'])->name('search.index');
            Route::get('/results', [SearchController::class, 'search'])->name('search.results');
            Route::get('/dokumen/{id}/preview', [SearchController::class, 'preview'])->name('search.dokumen.preview');
            Route::get('/dokumen/{id}/download', [SearchController::class, 'download'])->name('search.dokumen.download');
        });

        // Upload Dokumen Routes
        Route::prefix('upload-dokumen')->group(function () {
            Route::get('/', [UploadController::class, 'create'])->name('upload-dokumen.create');
            Route::post('/', [UploadController::class, 'store'])->name('upload-dokumen.store');
        });

        // Dokumen Saya Routes
        Route::prefix('dokumen-saya')->group(function () {
            Route::get('/', [UploadController::class, 'index'])->name('dokumen-saya');
            Route::delete('/{id}', [UploadController::class, 'destroy'])->name('dokumen-saya.destroy');
            Route::get('/download/{id}', [UploadController::class, 'download'])->name('dokumen-saya.download');
            Route::get('/preview/{id}', [UploadController::class, 'preview'])->name('dokumen-saya.preview');
        });

        // Profile Routes
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

        // ==================== SETTINGS ROUTES ====================
        Route::prefix('settings')->group(function () {
            
            // IKU Management Routes
            Route::prefix('iku')->group(function () {
                Route::get('/', [SettingController::class, 'indexIku'])->name('settings.iku.index');
                Route::get('/create', [SettingController::class, 'createIku'])->name('settings.iku.create');
                Route::post('/', [SettingController::class, 'storeIku'])->name('settings.iku.store');
                Route::get('/{id}/edit', [SettingController::class, 'editIku'])->name('settings.iku.edit');
                Route::put('/{id}', [SettingController::class, 'updateIku'])->name('settings.iku.update');
                Route::delete('/{id}', [SettingController::class, 'destroyIku'])->name('settings.iku.destroy');
            });

            // Unit Kerja Management Routes
            Route::prefix('unit-kerja')->group(function () {
                Route::get('/', [SettingController::class, 'indexUnitKerja'])->name('settings.unit-kerja.index');
                Route::get('/create', [SettingController::class, 'createUnitKerja'])->name('settings.unit-kerja.create');
                Route::post('/', [SettingController::class, 'storeUnitKerja'])->name('settings.unit-kerja.store');
                Route::get('/{id}/edit', [SettingController::class, 'editUnitKerja'])->name('settings.unit-kerja.edit');
                Route::put('/{id}', [SettingController::class, 'updateUnitKerja'])->name('settings.unit-kerja.update');
                Route::delete('/{id}', [SettingController::class, 'destroyUnitKerja'])->name('settings.unit-kerja.destroy');
            });
            
            // SPMI Settings (Optional)
            Route::prefix('spmi')->group(function () {
                Route::get('/', [SettingController::class, 'indexSpm'])->name('settings.spmi.index');
                Route::put('/update', [SettingController::class, 'updateSpm'])->name('settings.spmi.update');
            });
            
        }); 
        
        // Berita Management Routes
        Route::prefix('berita')->group(function () {
            Route::get('/', [BeritaController::class, 'index'])->name('admin.berita.index');
            Route::get('/create', [BeritaController::class, 'create'])->name('admin.berita.create');
            Route::post('/', [BeritaController::class, 'store'])->name('admin.berita.store');
            Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit');
            Route::put('/{id}', [BeritaController::class, 'update'])->name('admin.berita.update');
            Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');
        });

        // Jadwal Management Routes  
        Route::prefix('jadwal')->group(function () {
            Route::get('/', [JadwalController::class, 'index'])->name('admin.jadwal.index');
            Route::get('/create', [JadwalController::class, 'create'])->name('admin.jadwal.create');
            Route::post('/', [JadwalController::class, 'store'])->name('admin.jadwal.store');
            Route::get('/{id}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal.edit');
            Route::put('/{id}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
            Route::delete('/{id}', [JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
        });
        
        // Report & Analytics Routes
        Route::prefix('reports')->group(function () {
            Route::get('/spmi-penetapan', [SpmController::class, 'reportPenetapan'])->name('reports.spmi-penetapan');
            Route::get('/spmi-summary', [SpmController::class, 'reportSummary'])->name('reports.spmi-summary');
        });
        
    });
});

// ==================== ERROR PAGES ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});