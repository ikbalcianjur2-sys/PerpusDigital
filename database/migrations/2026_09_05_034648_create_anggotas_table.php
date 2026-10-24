<?php
// Simpan sebagai: database/migrations/2024_01_01_000001_create_anggotas_table.php
// (nama file harus diawali tanggal lebih lama dari migration lain yang mereferensikannya,
//  atau cukup jalankan migrate seperti biasa - Laravel urutkan otomatis dari nama file)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_identitas', 50)->unique();
            $table->string('kontak', 50)->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};