<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Hanya resident dan petugas
        $users = User::whereIn('role', ['resident', 'petugas', 'member'])->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:resident,petugas',
            'alamat' => 'nullable|string|max:255',
            'telp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Jika resident, buat entri residents untuk data tambahan
        if ($user->role === 'resident') {
            Resident::create([
                'user_id' => $user->id,
                'alamat' => $validated['alamat'],
                'telp' => $validated['telp'],
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // Ambil data tambahan jika resident
        $resident = $user->role === 'resident'
            ? Resident::where('user_id', $user->id)->first()
            : null;

        return view('users.edit', compact('user', 'resident'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:resident,petugas',
            'alamat' => 'nullable|string|max:255',
            'telp' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($validated['password']) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Update atau hapus resident data
        if ($user->role === 'resident') {
            Resident::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'alamat' => $validated['alamat'],
                    'telp' => $validated['telp'],
                ]
            );
        } else {
            Resident::where('user_id', $user->id)->delete();
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Hapus data resident jika ada
        Resident::where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
