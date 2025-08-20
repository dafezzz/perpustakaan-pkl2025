<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 100);
            $table->integer('stok')->default(0);
            $table->string('penerbit', 100);
            $table->year('tahun_terbit');
            $table->string('pengarang', 100);
            $table->integer('harga_peminjaman');
            $table->string('cover')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_books');
    }
};
