<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['nama_peminjam', 'no_identitas', 'kontak']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('nama_peminjam')->after('buku_id')->nullable();
            $table->string('no_identitas')->after('nama_peminjam')->nullable();
            $table->string('kontak')->after('no_identitas')->nullable();
        });
    }
};