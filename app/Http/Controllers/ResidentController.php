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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'cover'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverName = null;
        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverName = time() . '_' . $coverFile->getClientOriginalName();
            $coverFile->move(public_path('cover_users'), $coverName);
        }

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'resident',
            'cover'    => $coverName,
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $resident->id,
            'password' => 'nullable|string|min:6',
            'cover'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // password opsional
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // cover opsional
        if ($request->hasFile('cover')) {
            // hapus file lama kalau ada
            if ($resident->cover && file_exists(public_path('cover_users/' . $resident->cover))) {
                unlink(public_path('cover_users/' . $resident->cover));
            }

            $coverFile = $request->file('cover');
            $coverName = time() . '_' . $coverFile->getClientOriginalName();
            $coverFile->move(public_path('cover_users'), $coverName);
            $validated['cover'] = $coverName;
        }

        $resident->update($validated);

        return redirect()->route('resident.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(User $resident)
    {
        try {
            if ($resident->cover && file_exists(public_path('cover_users/' . $resident->cover))) {
                unlink(public_path('cover_users/' . $resident->cover));
            }

            $resident->delete();
            return redirect()->route('resident.index')->with('success', 'Admin berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}
