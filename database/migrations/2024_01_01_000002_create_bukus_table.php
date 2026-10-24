<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn')->nullable()->unique();
            $table->integer('stok')->default(0);
            $table->integer('jumlah_dipinjam')->default(0);
            $table->string('cover')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
