@extends('layouts.app')

@section('content')
<!-- Heading dan tombol tambah -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800">Tambah Petugas</h1>
</div>

<div class="row">
    <div class="col">
        <form action="{{ route('petugas.store') }}" method="post">
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
                        <label for="name">Name</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            required
                        >
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-end" style="gap:10px;">
                        <a href="{{ route('petugas.index') }}" class="btn btn-outline-secondary">
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
