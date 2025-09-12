<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use App\Helpers\ActivityLogger; // <-- import helper
use Illuminate\Http\Request;

class RakController extends Controller
{
    public function index()
    {
        $raks = Rak::all();
        return view('rak.index', compact('raks'));
    }

    public function create()
    {
        return view('rak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rak' => 'required|unique:raks',
            'nama_rak' => 'required',
        ]);

        $rak = Rak::create($request->all());

        // --- LOG ACTIVITY ---
        ActivityLogger::log(
            'Tambah Rak',
            $rak,
            'Rak: ' . $rak->nama_rak
        );

        return redirect()->route('rak.index')->with('success', 'Rak berhasil ditambahkan.');
    }

    public function edit(Rak $rak)
    {
        return view('rak.edit', compact('rak'));
    }

    public function update(Request $request, Rak $rak)
    {
        $request->validate([
            'kode_rak' => 'required|unique:raks,kode_rak,' . $rak->id,
            'nama_rak' => 'required',
        ]);

        $rak->update($request->all());

        // --- LOG ACTIVITY ---
        ActivityLogger::log(
            'Update Rak',
            $rak,
            'Rak: ' . $rak->nama_rak
        );

        return redirect()->route('rak.index')->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(Rak $rak)
    {
        $rak->delete();

        // --- LOG ACTIVITY ---
        ActivityLogger::log(
            'Hapus Rak',
            $rak,
            'Rak: ' . $rak->nama_rak
        );

        return redirect()->route('rak.index')->with('success', 'Rak berhasil dihapus.');
    }
}
