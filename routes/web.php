<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DendaController;
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

    // CRUD Books (boleh resident & petugas & member)
    Route::middleware(['role:resident,petugas,member'])->group(function () {
        Route::resource('books', BookController::class);
    });

    // CRUD peminjaman untuk semua role login
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');

    // ✅ Route konfirmasi peminjaman untuk admin/petugas
    Route::post('/peminjaman/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasi'])
        ->middleware('role:resident,petugas')
        ->name('peminjaman.konfirmasi');

    // Pengembalian (khusus admin & petugas)
    Route::middleware(['role:resident,petugas'])->group(function () {
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::post('/pengembalian/kembali/{id}', [PengembalianController::class, 'kembali'])->name('pengembalian.kembali');
    });

    // Profile universal
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Clear all peminjaman (khusus admin/petugas)
    Route::delete('/peminjaman/clear', [PeminjamanController::class, 'clearAll'])->name('peminjaman.clear');

    // ✅ Route Riwayat (untuk semua user login)
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});

// Denda
Route::get('/denda/bayar/{id}', [DendaController::class, 'bayar'])->name('denda.bayar');

require __DIR__ . '/auth.php';
