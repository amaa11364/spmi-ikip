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
use App\Http\Controllers\DashboardController;

// ==================== CONTROLLER IMPORTS ROLE-SPECIFIC ====================
use App\Http\Controllers\Verifikator\VerifikatorController;
use App\Http\Controllers\Verifikator\VerifikatorDashboardController;
use App\Http\Controllers\Verifikator\DokumenController as VerifikatorDokumenController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;

use App\Http\Controllers\User\UserDashboardController;

// ==================== PUBLIC ROUTES (TANPA LOGIN) ====================
Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

// Dokumen Publik
Route::get('/dokumen-publik', [DokumenPublikController::class, 'index'])->name('dokumen-publik.index');
Route::get('/dokumen-publik/{id}', [DokumenPublikController::class, 'show'])->name('dokumen-publik.show');

// Berita Publik
Route::get('/berita', [LandingPageController::class, 'beritaIndex'])->name('berita.index');
Route::get('/berita/{slug}', [LandingPageController::class, 'beritaShow'])->name('berita.show');

// Halaman Statis
Route::view('/upt', 'upt.index')->name('upt.index');
Route::view('/bagian', 'bagian.index')->name('bagian.index');
Route::view('/program-studi', 'program-studi.index')->name('program-studi.index');
Route::view('/unit-kerja', 'unit-kerja.index')->name('unit-kerja.index');
Route::view('/tentang/profil', 'tentang.profil')->name('tentang.profil');
Route::view('/tentang/visi-misi', 'tentang.visi-misi')->name('tentang.visi-misi');
Route::view('/tentang/struktur-organisasi', 'tentang.sotk')->name('tentang.sotk');

// ==================== AUTHENTICATION ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLoginForm'])->name('masuk');
    Route::post('/masuk', [AuthController::class, 'masuk'])->name('masuk.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== DASHBOARD UTAMA (REDIRECT BERDASARKAN ROLE) ====================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// ==================== ROUTES UNTUK SEMUA USER YANG LOGIN ====================
Route::middleware(['auth'])->group(function () {
    
    // ==================== UPLOAD DOKUMEN (SPMI CONTEXT) ====================
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::get('/spmi/penetapan/{id}', [UploadController::class, 'createWithContext'])->name('spmi-penetapan');
        Route::get('/spmi/pelaksanaan', [UploadController::class, 'createWithContext'])->name('spmi-pelaksanaan');
        Route::get('/spmi/evaluasi', [UploadController::class, 'createWithContext'])->name('spmi-evaluasi');
        Route::get('/spmi/pengendalian', [UploadController::class, 'createWithContext'])->name('spmi-pengendalian');
        Route::get('/spmi/peningkatan', [UploadController::class, 'createWithContext'])->name('spmi-peningkatan');
    });
    
    // ==================== UPLOAD DOKUMEN UMUM (CRUD) ====================
    Route::prefix('upload-dokumen')->name('upload-dokumen.')->group(function () {
        Route::get('/', [UploadController::class, 'create'])->name('create');
        Route::post('/', [UploadController::class, 'store'])->name('store');
    });
    
    // ==================== DOKUMEN SAYA (MANAJEMEN DOKUMEN) ====================
    Route::prefix('dokumen-saya')->name('dokumen-saya.')->group(function () {
        Route::get('/', [UploadController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [UploadController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UploadController::class, 'update'])->name('update');
        Route::delete('/{id}', [UploadController::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [UploadController::class, 'download'])->name('download');
        Route::get('/preview/{id}', [UploadController::class, 'preview'])->name('preview');
        Route::get('/status/{status}', [UploadController::class, 'byStatus'])->name('by-status');
    });
    
    // ==================== PROFILE (UNTUK SEMUA USER) ====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
        Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
    
    // ==================== SEARCH (UNTUK SEMUA USER) ====================
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [SearchController::class, 'index'])->name('index');
        Route::get('/results', [SearchController::class, 'search'])->name('results');
        Route::get('/dokumen/{id}/preview', [SearchController::class, 'preview'])->name('dokumen.preview');
        Route::get('/dokumen/{id}/download', [SearchController::class, 'download'])->name('dokumen.download');
    });
    
    // ==================== SPMI ROUTES (UNTUK SEMUA USER) ====================
    Route::prefix('spmi')->name('spmi.')->group(function () {
        
        // PENETAPAN SPMI
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
        
        // PELAKSANAAN SPMI
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
        
        // EVALUASI SPMI
        Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
            Route::get('/', [EvaluasiSpmController::class, 'indexEvaluasiFull'])->name('index');
            Route::get('/create', [EvaluasiSpmController::class, 'createEvaluasiFull'])->name('create');
            Route::post('/', [EvaluasiSpmController::class, 'storeEvaluasiFull'])->name('store');
            Route::get('/{id}', [EvaluasiSpmController::class, 'showEvaluasiFull'])->name('show');
            Route::get('/{id}/edit', [EvaluasiSpmController::class, 'editEvaluasiFull'])->name('edit');
            Route::put('/{id}', [EvaluasiSpmController::class, 'updateEvaluasiFull'])->name('update');
            Route::delete('/{id}', [EvaluasiSpmController::class, 'destroyEvaluasiFull'])->name('destroy');
            
            // Document management
            Route::post('/{id}/upload', [EvaluasiSpmController::class, 'uploadDokumenEvaluasi'])->name('upload');
            
            // AJAX endpoints
            Route::get('/{id}/detail', [EvaluasiSpmController::class, 'getEvaluasiData'])->name('ajax.detail');
            Route::get('/{id}/edit-form', [EvaluasiSpmController::class, 'getEvaluasiEditForm'])->name('ajax.edit-form');
            Route::put('/{id}/ajax-update', [EvaluasiSpmController::class, 'updateEvaluasiAjax'])->name('ajax.update');
        });
        
        // PENGENDALIAN
        Route::prefix('pengendalian')->name('pengendalian.')->group(function () {
            Route::get('/', [SpmController::class, 'indexPengendalian'])->name('index');
        });
        
        // PENINGKATAN SPMI
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
        
        // AKREDITASI
        Route::prefix('akreditasi')->name('akreditasi.')->group(function () {
            Route::get('/', [SpmController::class, 'indexAkreditasi'])->name('index');
        });
        
        // API ENDPOINTS (UNTUK AJAX)
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/penetapan/data', [SpmController::class, 'getPenetapanData'])->name('penetapan.data');
            Route::get('/penetapan/statistics', [SpmController::class, 'getPenetapanStatistics'])->name('penetapan.statistics');
            Route::get('/pelaksanaan/statistics', [SpmController::class, 'getPelaksanaanStatistics'])->name('pelaksanaan.statistics');
            Route::get('/peningkatan/statistics', [PeningkatanController::class, 'getStatistics'])->name('peningkatan.statistics');
        });
    });
});

// ==================== ROUTES UNTUK ROLE USER (REGULAR USER) ====================
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    
    // Dashboard User
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Statistik User
    Route::get('/statistik', function() {
        return view('user.statistik');
    })->name('statistik');
    
    // ALIAS ROUTE UNTUK UPLOAD DOKUMEN (SUPAYA SESUAI DENGAN user.upload-dokumen.create)
    Route::get('/upload-dokumen', [UploadController::class, 'create'])->name('upload-dokumen.create');
    Route::post('/upload-dokumen', [UploadController::class, 'store'])->name('upload-dokumen.store');
    
    // ALIAS ROUTE UNTUK DOKUMEN SAYA
    Route::get('/dokumen-saya', [UploadController::class, 'index'])->name('dokumen-saya.index');
});

// ==================== ROUTES UNTUK ROLE VERIFIKATOR ====================
Route::middleware(['auth'])->prefix('verifikator')->name('verifikator.')->group(function () {
    
    // Dashboard Verifikator
    Route::get('/dashboard', [VerifikatorDashboardController::class, 'index'])->name('dashboard');
    
    // API Statistics
    Route::get('/statistics/pending', [VerifikatorDashboardController::class, 'getPendingStatistics'])
        ->name('dokumen.pending-count');
    
    // Review Dokumen
    Route::prefix('review')->name('review.')->group(function () {
        Route::get('/', [VerifikatorController::class, 'reviewDokumen'])->name('index');
        Route::get('/pending', [VerifikatorController::class, 'reviewDokumen'])
            ->defaults('status', 'pending')->name('pending');
        Route::get('/approved', [VerifikatorController::class, 'reviewDokumen'])
            ->defaults('status', 'approved')->name('approved');
        Route::get('/rejected', [VerifikatorController::class, 'reviewDokumen'])
            ->defaults('status', 'rejected')->name('rejected');
        Route::get('/revision', [VerifikatorController::class, 'reviewDokumen'])
            ->defaults('status', 'revision')->name('revision');
        Route::get('/{id}/detail', [VerifikatorController::class, 'reviewDetail'])->name('detail');
    });
    
    // Dokumen Management
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [VerifikatorController::class, 'dokumenList'])->name('index');
        Route::get('/all', [VerifikatorController::class, 'dokumenList'])->name('all');
        Route::get('/pending', [VerifikatorController::class, 'dokumenList'])
            ->defaults('status', 'pending')->name('pending');
        Route::get('/approved', [VerifikatorController::class, 'dokumenList'])
            ->defaults('status', 'approved')->name('approved');
        Route::get('/rejected', [VerifikatorController::class, 'dokumenList'])
            ->defaults('status', 'rejected')->name('rejected');
        Route::get('/revision', [VerifikatorController::class, 'dokumenList'])
            ->defaults('status', 'revision')->name('revision');
        Route::get('/search', [VerifikatorController::class, 'dokumenList'])->name('search');
        Route::get('/{id}/view', [VerifikatorController::class, 'viewDokumen'])->name('view');
        Route::get('/{id}/download', [VerifikatorController::class, 'downloadDokumen'])->name('download');
        Route::post('/{id}/verify', [VerifikatorController::class, 'verifyDokumen'])->name('verify');
        Route::get('/pending-count', [VerifikatorController::class, 'getPendingCount'])->name('pending-count');
        
        // HALAMAN STATISTIK VERIFIKATOR
        Route::get('/statistics', [VerifikatorController::class, 'statistik'])->name('statistics');
    });
    
    // Statistik (alias)
    Route::get('/statistik', [VerifikatorController::class, 'statistik'])->name('statistik.index');
    
    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [VerifikatorController::class, 'laporan'])->name('index');
        Route::get('/export/pdf', [VerifikatorController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [VerifikatorController::class, 'exportExcel'])->name('export.excel');
    });
});

// ==================== ROUTES UNTUK ROLE ADMIN ====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // ==================== USER MANAGEMENT ====================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/activate', [AdminUserController::class, 'activate'])->name('activate');
        Route::put('/{id}/deactivate', [AdminUserController::class, 'deactivate'])->name('deactivate');
        Route::put('/{id}/change-role', [AdminUserController::class, 'changeRole'])->name('change-role');
    });
    
    // ==================== BERITA MANAGEMENT ====================
    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/', [BeritaController::class, 'index'])->name('index');
        Route::get('/create', [BeritaController::class, 'create'])->name('create');
        Route::post('/', [BeritaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeritaController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/publish', [BeritaController::class, 'publish'])->name('publish');
        Route::put('/{id}/unpublish', [BeritaController::class, 'unpublish'])->name('unpublish');
    });

    // ==================== JADWAL MANAGEMENT ====================
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [JadwalController::class, 'index'])->name('index');
        Route::get('/create', [JadwalController::class, 'create'])->name('create');
        Route::post('/', [JadwalController::class, 'store'])->name('store');
        Route::get('/{jadwal}/edit', [JadwalController::class, 'edit'])->name('edit');
        Route::put('/{jadwal}', [JadwalController::class, 'update'])->name('update');
        Route::delete('/{jadwal}', [JadwalController::class, 'destroy'])->name('destroy');
    });
    
    // ==================== DOKUMEN MANAGEMENT ====================
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [AdminDokumenController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminDokumenController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminDokumenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminDokumenController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminDokumenController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/toggle-public', [AdminDokumenController::class, 'togglePublic'])->name('toggle-public');
        Route::get('/export', [AdminDokumenController::class, 'export'])->name('export');
    });
    
    // ==================== SETTINGS MANAGEMENT ====================
    Route::prefix('settings')->name('settings.')->group(function () {
        
        // IKU Management
        Route::prefix('iku')->name('iku.')->group(function () {
            Route::get('/', [SettingController::class, 'indexIku'])->name('index');
            Route::get('/create', [SettingController::class, 'createIku'])->name('create');
            Route::post('/', [SettingController::class, 'storeIku'])->name('store');
            Route::get('/{id}/edit', [SettingController::class, 'editIku'])->name('edit');
            Route::put('/{id}', [SettingController::class, 'updateIku'])->name('update');
            Route::delete('/{id}', [SettingController::class, 'destroyIku'])->name('destroy');
        });

        // Unit Kerja Management
        Route::prefix('unit-kerja')->name('unit-kerja.')->group(function () {
            Route::get('/', [SettingController::class, 'indexUnitKerja'])->name('index');
            Route::get('/create', [SettingController::class, 'createUnitKerja'])->name('create');
            Route::post('/', [SettingController::class, 'storeUnitKerja'])->name('store');
            Route::get('/{id}/edit', [SettingController::class, 'editUnitKerja'])->name('edit');
            Route::put('/{id}', [SettingController::class, 'updateUnitKerja'])->name('update');
            Route::delete('/{id}', [SettingController::class, 'destroyUnitKerja'])->name('destroy');
        });
        
        // Prodi Management
        Route::prefix('prodi')->name('prodi.')->group(function () {
            Route::get('/', [SettingController::class, 'indexProdi'])->name('index');
            Route::get('/create', [SettingController::class, 'createProdi'])->name('create');
            Route::post('/', [SettingController::class, 'storeProdi'])->name('store');
            Route::get('/{id}/edit', [SettingController::class, 'editProdi'])->name('edit');
            Route::put('/{id}', [SettingController::class, 'updateProdi'])->name('update');
            Route::delete('/{id}', [SettingController::class, 'destroyProdi'])->name('destroy');
        });
        
        // SPMI Settings
        Route::prefix('spmi')->name('spmi.')->group(function () {
            Route::get('/', [SettingController::class, 'indexSpm'])->name('index');
            Route::put('/update', [SettingController::class, 'updateSpm'])->name('update');
        });
        
        // System Settings
        Route::get('/system', [SettingController::class, 'system'])->name('system');
        Route::put('/system/update', [SettingController::class, 'updateSystem'])->name('system.update');
    });
    
    // ==================== REPORTS & ANALYTICS ====================
    Route::prefix('reports')->name('reports.')->group(function () {
        // SPMI Reports
        Route::get('/spmi-penetapan', [SpmController::class, 'reportPenetapan'])->name('spmi-penetapan');
        Route::get('/spmi-summary', [SpmController::class, 'reportSummary'])->name('spmi-summary');
        Route::get('/spmi-peningkatan', [PeningkatanController::class, 'exportExcel'])->name('spmi-peningkatan');
        
        // User Activity Reports
        Route::get('/user-activity', [AdminReportController::class, 'userActivity'])->name('user-activity');
        Route::get('/dokumen-activity', [AdminReportController::class, 'dokumenActivity'])->name('dokumen-activity');
        Route::get('/verification-stats', [AdminReportController::class, 'verificationStats'])->name('verification-stats');
        
        // Export Reports
        Route::get('/export/users', [AdminReportController::class, 'exportUsers'])->name('export.users');
        Route::get('/export/dokumen', [AdminReportController::class, 'exportDokumen'])->name('export.dokumen');
        Route::get('/export/verification', [AdminReportController::class, 'exportVerification'])->name('export.verification');
    });
    
    // ==================== SYSTEM TOOLS ====================
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/backup', [AdminToolController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [AdminToolController::class, 'createBackup'])->name('backup.create');
        Route::get('/logs', [AdminToolController::class, 'logs'])->name('logs');
        Route::get('/cache-clear', [AdminToolController::class, 'cacheClear'])->name('cache-clear');
        Route::get('/storage-link', [AdminToolController::class, 'storageLink'])->name('storage-link');
    });
});
// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});