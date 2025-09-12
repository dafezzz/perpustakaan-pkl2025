<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Mulai query
        $query = ActivityLog::with('user')->latest();

        // Batasi data berdasarkan role
        if ($user->role !== 'resident') {
            // Petugas/member hanya melihat log mereka sendiri
            $query->where('user_id', $user->id);
        }

        // Filter user & role hanya untuk admin (DB role = resident)
        if ($user->role === 'resident') {

            // Filter berdasarkan nama user
            if ($request->filled('user')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->user . '%');
                });
            }

            // Filter berdasarkan role
            if ($request->filled('role')) {
                $roleSearch = strtolower($request->role);

                // Mapping 'admin' ke 'resident' di DB
                if ($roleSearch === 'admin') {
                    $roleSearch = 'resident';
                }

                $query->whereHas('user', function ($q) use ($roleSearch) {
                    $q->where('role', $roleSearch);
                });
            }
        }

        // Filter aktivitas
        if ($request->filled('activity')) {
            $query->where('activity', 'like', '%' . $request->activity . '%');
        }

        // Filter model
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Pagination 15 log per page
        $logs = $query->paginate(15)->withQueryString();

        return view('activity_logs.index', compact('logs'));
    }
}
