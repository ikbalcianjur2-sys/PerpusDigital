<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anggota;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_pengembalian',
        'status',
        'denda',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_pengembalian' => 'date',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id', 'id');
    }


    // Berapa hari terlambat (jika masih dipinjam & melewati batas)
    public function getTerlambatHariAttribute()
    {
        if ($this->status === 'Dikembalikan') {
            return 0;
        }
        $selisih = now()->startOfDay()->diffInDays($this->tanggal_kembali->startOfDay(), false);
        return $selisih < 0 ? abs($selisih) : 0;
    }
}