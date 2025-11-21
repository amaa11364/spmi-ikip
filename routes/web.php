<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;

// ==================== LANDING PAGE ====================
Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

// ==================== PUBLIC PAGES ====================
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
Route::post('/masuk', [AuthController::class, 'masuk']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== PROTECTED ROUTES (HARUS LOGIN) ====================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/home', function () {
        return view('dashboard');
    });
    
    // Upload Dokumen Routes - SEPARATED
    Route::get('/upload-dokumen', [UploadController::class, 'create'])->name('upload-dokumen.create');
    Route::post('/upload-dokumen', [UploadController::class, 'store'])->name('upload-dokumen.store');
    Route::get('/dokumen-saya', [UploadController::class, 'index'])->name('dokumen-saya');
    Route::delete('/dokumen-saya/{id}', [UploadController::class, 'destroy'])->name('dokumen-saya.destroy');
    Route::get('/dokumen-saya/download/{id}', [UploadController::class, 'download'])->name('dokumen-saya.download');
    Route::get('/dokumen-saya/preview/{id}', [UploadController::class, 'preview'])->name('dokumen-saya.preview');
});