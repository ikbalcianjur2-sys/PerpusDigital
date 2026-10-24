<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_perpustakaan',
        'alamat',
        'telp',
        'email',
        'jam_operasional',
        'lama_peminjaman',
        'denda_per_hari',
        'logo',
    ];

    // Helper ambil satu-satunya baris setting (buat jika belum ada)
    public static function current()
    {
        return static::first() ?? static::create([
            'nama_perpustakaan' => 'Perpustakaan Digital',
        ]);
    }
}
