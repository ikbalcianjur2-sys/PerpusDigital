<?php
// Simpan sebagai: database/migrations/2024_01_01_000002_add_anggota_id_to_peminjamans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreignId('anggota_id')->nullable()->after('id')
                ->constrained('anggotas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']);
            $table->dropColumn('anggota_id');
        });
    }
};