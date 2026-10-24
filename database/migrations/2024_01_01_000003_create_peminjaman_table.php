<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
            $table->string('nama_peminjam');
            $table->string('no_identitas');
            $table->string('kontak')->nullable();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->date('tanggal_pengembalian')->nullable();
            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat'])->default('Dipinjam');
            $table->integer('denda')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
