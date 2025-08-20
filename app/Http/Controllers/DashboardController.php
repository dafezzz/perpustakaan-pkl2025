<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalResidents = User::where('role', 'resident')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalUsers = User::count();

        return view('dashboard', compact('totalResidents', 'totalPetugas', 'totalUsers'));
    }
}
