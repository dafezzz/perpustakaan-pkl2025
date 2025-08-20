@extends('layouts.app')

@section('content')
<!-- Heading -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800">Edit Buku</h1>
</div>

<div class="row">
    <div class="col">
        <form action="{{ route('books.update', $book->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">

                    {{-- Pesan error jika validasi gagal --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Harap isi dengan benar:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Judul Buku --}}
                    <div class="form-group mb-3">
                        <label for="judul">Judul Buku</label>
                        <input 
                            type="text" 
                            name="judul" 
                            id="judul" 
                            class="form-control" 
                            value="{{ old('judul', $book->judul) }}"
                            required
                        >
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group mb-3">
                        <label for="kategori">Kategori</label>
                        <select 
                            name="kategori" 
                            id="kategori" 
                            class="form-control"
                            required
                        >
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ old('kategori', $book->kategori) == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stok --}}
                    <div class="form-group mb-3">
                        <label for="stok">Stok</label>
                        <input 
                            type="number" 
                            name="stok" 
                            id="stok" 
                            class="form-control" 
                            value="{{ old('stok', $book->stok) }}"
                            required
                        >
                    </div>

                    {{-- Penerbit --}}
                    <div class="form-group mb-3">
                        <label for="penerbit">Penerbit</label>
                        <input 
                            type="text" 
                            name="penerbit" 
                            id="penerbit" 
                            class="form-control" 
                            value="{{ old('penerbit', $book->penerbit) }}"
                            required
                        >
                    </div>

                    {{-- Tahun Terbit --}}
                    <div class="form-group mb-3">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input 
                            type="number" 
                            name="tahun_terbit" 
                            id="tahun_terbit" 
                            class="form-control" 
                            value="{{ old('tahun_terbit', $book->tahun_terbit) }}"
                            required
                        >
                    </div>

                    {{-- Pengarang --}}
                    <div class="form-group mb-3">
                        <label for="pengarang">Pengarang</label>
                        <input 
                            type="text" 
                            name="pengarang" 
                            id="pengarang" 
                            class="form-control" 
                            value="{{ old('pengarang', $book->pengarang) }}"
                            required
                        >
                    </div>

                    {{-- Cover Buku --}}
                    <div class="form-group mb-3">
                        <label for="cover">Cover Buku (Opsional)</label>
                        <input 
                            type="file" 
                            name="cover" 
                            id="cover" 
                            class="form-control"
                        >
                        @if ($book->cover)
                            <small class="text-muted">
                                Cover saat ini: <a href="{{ asset('cover_buku/'.$book->cover) }}" target="_blank">{{ $book->cover }}</a>
                            </small>
                        @endif
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="card-footer d-flex justify-content-end" style="gap:10px;">
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
