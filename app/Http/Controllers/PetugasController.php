<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = User::where('role', 'petugas')->get();
        return view('pages.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('pages.petugas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'petugas',
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(User $petuga)
    {
        return view('pages.petugas.edit', compact('petuga'));
    }

    public function update(Request $request, User $petuga)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $petuga->id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $petuga->update($validated);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diperbarui.');
    }

    public function destroy(User $petuga)
    {
        $petuga->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}
