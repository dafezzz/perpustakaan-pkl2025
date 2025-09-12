<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::with('user')->get();
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
            'alamat'   => 'nullable|string|max:255',
            'telp'     => 'nullable|string|max:20',
            'cover'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $coverName = null;

            // Upload cover ke storage/app/public/cover_users
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $file = $request->file('cover');
                $coverName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $file->storeAs('cover_users', $coverName, 'public');
            }

            // Simpan user
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'resident',
            ]);

            // Simpan resident
            Resident::create([
                'user_id' => $user->id,
                'alamat'  => $validated['alamat'] ?? null,
                'telp'    => $validated['telp'] ?? null,
                'cover'   => $coverName,
            ]);

            return redirect()->route('resident.index')->with('success', 'Resident berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Resident $resident)
    {
        return view('pages.resident.edit', compact('resident'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $resident->user_id,
            'password' => 'nullable|string|min:6',
            'alamat'   => 'nullable|string|max:255',
            'telp'     => 'nullable|string|max:20',
            'cover'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Update user
            $userData = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $resident->user->update($userData);

            // Update cover
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                if ($resident->cover && file_exists(storage_path('app/public/'.$resident->cover))) {
                    unlink(storage_path('app/public/'.$resident->cover));
                }
                $file = $request->file('cover');
                $coverName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $file->storeAs('cover_users', $coverName, 'public');
                $resident->cover = $coverName;
            }

            // Update detail resident
            $resident->alamat = $validated['alamat'] ?? null;
            $resident->telp   = $validated['telp'] ?? null;
            $resident->save();

            return redirect()->route('resident.index')->with('success', 'Resident berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Resident $resident)
    {
        try {
            if ($resident->cover && file_exists(storage_path('app/public/'.$resident->cover))) {
                unlink(storage_path('app/public/'.$resident->cover));
            }

            $resident->user->delete();
            $resident->delete();

            return redirect()->route('resident.index')->with('success', 'Resident berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}
