<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'stok',
        'jumlah_dipinjam',
        'cover',
        'deskripsi',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Accessor: status ketersediaan
    public function getStatusStokAttribute()
    {
        return $this->stok > 0 ? 'Tersedia' : 'Habis';
    }
}
