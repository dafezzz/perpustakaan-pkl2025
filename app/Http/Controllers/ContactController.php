<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Bisa disesuaikan, contohnya kirim email
        $data = $request->only('name', 'email', 'message');

        // Contoh: kirim email ke admin
        /*
        Mail::to('admin@perpustakaan.com')->send(new \App\Mail\ContactMail($data));
        */

        // Simpan ke database kalau mau
        // Contact::create($data);

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
