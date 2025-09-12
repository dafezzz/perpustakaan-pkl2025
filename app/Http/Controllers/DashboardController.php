<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung user berdasarkan role
        $totalResidents = User::where('role', 'resident')->count();
        $totalPetugas   = User::where('role', 'petugas')->count();
        $totalUsers     = User::count();

        // Statistik peminjaman (hanya angka)
        $todayLoans = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();

        $weekLoans  = Peminjaman::whereBetween('tanggal_pinjam', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ])->count();

        $monthLoans = Peminjaman::whereMonth('tanggal_pinjam', Carbon::now()->month)
                        ->whereYear('tanggal_pinjam', Carbon::now()->year)
                        ->count();

        // Data detail peminjaman (buat card user+buku)
        $todayLoanDetails = Peminjaman::with(['user','book'])
            ->whereDate('tanggal_pinjam', Carbon::today())
            ->latest()
            ->get();

        $weekLoanDetails = Peminjaman::with(['user','book'])
            ->whereBetween('tanggal_pinjam', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->latest()
            ->get();

        $monthLoanDetails = Peminjaman::with(['user','book'])
            ->whereMonth('tanggal_pinjam', Carbon::now()->month)
            ->whereYear('tanggal_pinjam', Carbon::now()->year)
            ->latest()
            ->get();

        // Peminjaman terbaru global
        $latestLoans = Peminjaman::with(['user', 'book'])
            ->latest()
            ->take(6)
            ->get();

      // 📊 Statistik Peminjaman 1 Tahun (Januari - Desember)
$months = [];
$peminjamanData = [];

for ($m = 1; $m <= 12; $m++) {
    $months[] = Carbon::createFromDate(null, $m, 1)->format('M'); // Jan, Feb, ...
    
    $count = Peminjaman::whereYear('tanggal_pinjam', Carbon::now()->year)
        ->whereMonth('tanggal_pinjam', $m)
        ->count();
        
    $peminjamanData[] = $count;
}


        return view('dashboard', compact(
            'totalResidents',
            'totalPetugas',
            'totalUsers',
            'todayLoans',
            'weekLoans',
            'monthLoans',
            'todayLoanDetails',
            'weekLoanDetails',
            'monthLoanDetails',
            'latestLoans',
            'months',
            'peminjamanData'
        ));
    }
}
