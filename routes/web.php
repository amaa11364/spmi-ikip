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
use App\Http\Controllers\SpmController; // Pastikan sudah ada

// ==================== DOKUMEN PUBLIK ROUTES (TANPA LOGIN) - HARUS PALING ATAS ====================
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

// ==================== PROTECTED ROUTES (SETELAH LOGIN) ====================
Route::middleware(['auth'])->group(function () {
    
    // ==================== SPMI ROUTES (CRUD LENGKAP) ====================
    Route::prefix('spmi')->name('spmi.')->group(function () {
        
        // ===== PENETAPAN SPMI - CRUD LENGKAP =====
        Route::prefix('penetapan')->name('penetapan.')->group(function () {
            // List all & show detail
            Route::get('/', [SpmController::class, 'indexPenetapan'])->name('index');
            Route::get('/{id}', [SpmController::class, 'showPenetapan'])->name('show');
            
            // CRUD operations
            Route::get('/create', [SpmController::class, 'createPenetapan'])->name('create');
            Route::post('/', [SpmController::class, 'storePenetapan'])->name('store');
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
            Route::put('/{id}/status-dokumen', [SpmController::class, 'updateStatusDokumen'])->name('status.update');
            
            // Export & Report
            Route::get('/export/excel', [SpmController::class, 'exportExcelPenetapan'])->name('export.excel');
            Route::get('/export/pdf', [SpmController::class, 'exportPdfPenetapan'])->name('export.pdf');
        });
        
        // ===== PELAKSANAAN =====
        Route::prefix('pelaksanaan')->name('pelaksanaan.')->group(function () {
            Route::get('/', [SpmController::class, 'indexPelaksanaan'])->name('index');
        });
        
        // ===== EVALUASI =====
        Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
            Route::get('/', [SpmController::class, 'indexEvaluasi'])->name('index');
            Route::get('/{type}', [SpmController::class, 'showEvaluasi'])->name('show');
        });
        
        // ===== PENGENDALIAN =====
        Route::prefix('pengendalian')->name('pengendalian.')->group(function () {
            Route::get('/', [SpmController::class, 'indexPengendalian'])->name('index');
        });
        
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
        
        // ===== API ENDPOINTS (untuk AJAX) =====
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/penetapan/data', [SpmController::class, 'getPenetapanData'])->name('penetapan.data');
            Route::get('/penetapan/statistics', [SpmController::class, 'getPenetapanStatistics'])->name('penetapan.statistics');
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