<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisArsipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::resource('arsip', ArsipController::class);
    Route::get('arsip/{arsip}/file', [ArsipController::class, 'show'])->name('arsip.show');
    Route::get('arsip/export/excel', [ArsipController::class, 'exportExcel'])->name('arsip.export.excel');
    Route::get('arsip/export/pdf', [ArsipController::class, 'exportPdf'])->name('arsip.export.pdf');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except('show');
        Route::resource('bidang', BidangController::class)->except('show');
        Route::resource('jenis-arsip', JenisArsipController::class)->except('show');
    });
});
