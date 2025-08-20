@extends('layouts.app')

@section('content')
<!-- Heading dan tombol tambah -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800">Tambah Buku</h1>
</div>

<div class="row">
    <div class="col">
        <form action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card-body">

                    {{-- Pesan error jika validasi gagal --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Harap isi terlebih dahulu:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label for="judul">Judul Buku</label>
                        <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}" required>
                    </div>

                    <div class="form-group mb-3">
    <label for="kategori">Kategori</label>
    <select name="kategori" id="kategori" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
       
        <option value="Fiksi">Fiksi</option>
        <option value="Non-Fiksi">Non-Fiksi</option>
        <option value="Teknologi">Teknologi</option>
        <option value="Pendidikan">Pendidikan</option>
        <option value="Sains">Sains</option>
        <option value="Sejarah">Sejarah</option>
        <option value="Biografi">Biografi</option>
        <option value="Komik">Komik</option>
        <option value="Agama">Agama</option>
        <option value="Majalah">Majalah</option>
        <option value="Roman">Roman</option>
        <option value="lainnya">Lainnya...</option>

    </select>
</div>


                    <div class="form-group mb-3">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" class="form-control" value="{{ old('penerbit') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" value="{{ old('tahun_terbit') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" name="pengarang" id="pengarang" class="form-control" value="{{ old('pengarang') }}" required>
                    </div>


                    <div class="form-group mb-3">
                        <label for="cover">Cover Buku (Opsional)</label>
                        <input type="file" name="cover" id="cover" class="form-control">
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-end" style="gap:10px;">
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
