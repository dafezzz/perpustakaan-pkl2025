<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Group memerlukan login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD Resident dan Petugas (khusus role resident/admin)
    Route::middleware(['role:resident'])->group(function () {
        Route::resource('resident', ResidentController::class);
        Route::resource('petugas', PetugasController::class);
    });

    // CRUD Books (boleh resident & petugas)
    Route::middleware(['role:resident,petugas,member'])->group(function () {
        Route::resource('books', BookController::class);
    });

    // CRUD peminjaman untuk semua role login
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');

    // ✅ Tambahkan route konfirmasi peminjaman untuk admin/petugas
    Route::post('/peminjaman/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasi'])
        ->middleware('role:resident,petugas')
        ->name('peminjaman.konfirmasi');

    // Profile universal
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::delete('/peminjaman/clear', [PeminjamanController::class, 'clearAll'])->name('peminjaman.clear');


require __DIR__.'/auth.php';
