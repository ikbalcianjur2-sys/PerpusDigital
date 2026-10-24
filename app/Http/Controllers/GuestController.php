<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $bukuPopuler = Buku::orderBy('jumlah_dipinjam', 'desc')->take(5)->get();

        $quotes = [
            "Membaca adalah napas bagi pikiran.",
            "Satu buku, satu pena, satu anak, dan satu guru dapat mengubah dunia.",
            "Semakin banyak Anda membaca, semakin banyak hal yang akan Anda ketahui. Semakin banyak yang Anda pelajari, semakin banyak tempat yang akan Anda kunjungi.",
            "Ada lebih banyak harta karun di dalam buku daripada di semua rampasan bajak laut di Pulau Harta.",
            "Sebuah ruangan tanpa buku seperti tubuh tanpa jiwa.",
        ];
        $quoteOfTheDay = $quotes[array_rand($quotes)];

        return view('welcome', compact('bukuPopuler', 'quoteOfTheDay'));
    }
}