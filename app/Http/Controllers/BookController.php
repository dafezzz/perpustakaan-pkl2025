<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Tampilkan semua buku.
     */
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    /**
     * Tampilkan form tambah buku.
     */
    public function create()
    {
        $categories = Book::select('kategori')->distinct()->pluck('kategori');
        return view('books.create', compact('categories'));
    }

    /**
     * Simpan buku baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer',
            'pengarang' => 'required|string|max:100',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            
        ]);

        if ($request->hasFile('cover')) {
            $coverName = time() . '.' . $request->cover->extension();
            $request->cover->move(public_path('cover_buku'), $coverName);
            $validated['cover'] = $coverName;
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit buku.
     */
    public function edit(Book $book)
    {
        $categories = Book::select('kategori')->distinct()->pluck('kategori');
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Simpan perubahan buku.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer',
            'pengarang' => 'required|string|max:100',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
        ]);

        if ($request->hasFile('cover')) {
            $coverName = time() . '.' . $request->cover->extension();
            $request->cover->move(public_path('cover_buku'), $coverName);
            $validated['cover'] = $coverName;
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
