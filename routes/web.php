<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DokumenPublikController;

// ==================== LANDING PAGE & PUBLIC ROUTES ====================
Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

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

// ==================== AUTHENTICATION ROUTES ====================
Route::get('/masuk', [AuthController::class, 'showLoginForm'])->name('masuk');
Route::post('/masuk', [AuthController::class, 'masuk'])->name('masuk.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== PROTECTED ROUTES (SETELAH LOGIN) ====================
Route::middleware(['auth'])->group(function () {
    
    // ==================== ADMIN PREFIX ROUTES ====================
    Route::prefix('admin')->group(function () {
        
        // Dashboard Routes
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        
        Route::get('/home', function () {
            return view('dashboard');
        });

        // **NEW: Search Routes (Protected)**
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

            // **NEW: Search Routes (Protected)**
            Route::prefix('search')->group(function () {
                Route::get('/', [SearchController::class, 'index'])->name('search.index');
                Route::get('/results', [SearchController::class, 'search'])->name('search.results');
                Route::get('/ajax-search', [SearchController::class, 'ajaxSearch'])->name('search.ajax');
                Route::get('/dokumen/{id}/preview', [SearchController::class, 'preview'])->name('search.dokumen.preview');
                Route::get('/dokumen/{id}/download', [SearchController::class, 'download'])->name('search.dokumen.download');
            });
            
        }); 
        
    }); 
    
});