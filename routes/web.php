<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PaketReviewController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminUserController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PPK Specific Routes (Static first)
    Route::middleware('role:PPK')->group(function () {
        Route::get('paket', [PaketController::class, 'index'])->name('paket.index');
        Route::get('paket/create', [PaketController::class, 'create'])->name('paket.create');
        Route::post('paket', [PaketController::class, 'store'])->name('paket.store');
        Route::delete('paket/{paket}', [PaketController::class, 'destroy'])->name('paket.destroy');
        Route::post('paket/{paket}/upload-lampiran', [PaketController::class, 'uploadLampiran'])->name('paket.upload-lampiran');
        Route::post('paket/{paket}/submit', [PaketController::class, 'submitPaket'])->name('paket.submit');
    });

    // PP Specific Routes
    Route::middleware('role:PP')->group(function () {
        Route::get('paket-review', [PaketReviewController::class, 'index'])->name('paket-review.index');
        Route::post('paket-review/{paket}/update-status', [PaketReviewController::class, 'updateStatus'])->name('paket-review.update-status');
        Route::post('lampiran/{lampiran}/review', [PaketReviewController::class, 'reviewLampiran'])->name('lampiran.review');
        Route::get('paket-bypass/create', [PaketReviewController::class, 'bypassCreate'])->name('paket-bypass.create');
        Route::post('paket-bypass', [PaketReviewController::class, 'bypassStore'])->name('paket-bypass.store');
    });

    // Detail paket dan komentar dinamis (Wildcards di bagian bawah)
    Route::get('paket/{paket}', [PaketController::class, 'show'])->name('paket.show');
    Route::post('paket/{paket}/comment', [CommentController::class, 'store'])->name('paket.comment');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::delete('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::post('/users/{user}/reset-token', [AdminUserController::class, 'generateResetToken'])->name('users.reset-token');
});

require __DIR__.'/auth.php';
