<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function bayar($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        if ($pengembalian->status_denda === 'lunas') {
            return back()->with('info', 'Denda sudah lunas.');
        }

        $pengembalian->update([
            'status_denda' => 'lunas'
        ]);

        return back()->with('success', 'Denda berhasil dibayarkan.');
    }
}
