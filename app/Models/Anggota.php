<?php
// Simpan sebagai: app/Models/Anggota.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    protected $fillable = ['nama', 'no_identitas', 'kontak', 'alamat'];

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }
}