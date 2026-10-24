<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perpustakaan')->default('Perpustakaan Digital');
            $table->string('alamat')->nullable();
            $table->string('telp')->nullable();
            $table->string('email')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->integer('lama_peminjaman')->default(7); // hari
            $table->integer('denda_per_hari')->default(1000); // rupiah
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
