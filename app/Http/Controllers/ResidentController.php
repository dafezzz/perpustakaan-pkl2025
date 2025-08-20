<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = User::where('role', 'resident')->get();
        return view('pages.resident.index', compact('residents'));
    }

    public function create()
    {
        return view('pages.resident.create');
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
            'role' => 'resident',
        ]);

        return redirect()->route('resident.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(User $resident)
    {
        return view('pages.resident.edit', compact('resident'));
    }

    public function update(Request $request, User $resident)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $resident->id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $resident->update($validated);

        return redirect()->route('resident.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(User $resident)
{
    try {
        $resident->delete();
        return redirect()->route('resident.index')->with('success', 'Admin berhasil dihapus.');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
    }
}

}
