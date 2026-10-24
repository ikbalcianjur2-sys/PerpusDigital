<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku       = Buku::count();
        $totalStok       = (int) Buku::sum('stok');
        $totalKategori   = Kategori::count();
        $peminjamanAktif = Peminjaman::where('status', 'Dipinjam')->count();
        $peminjamanTerlambat = Peminjaman::where('status', '!=', 'Dikembalikan')
            ->whereDate('tanggal_kembali', '<', now())
            ->count();
        $totalPeminjaman = Peminjaman::count();

        $bukuTerpopuler = Buku::with('kategori')
            ->orderByDesc('jumlah_dipinjam')
            ->take(5)
            ->get();

        $peminjamanTerbaru = Peminjaman::with('buku')
            ->latest()
            ->take(6)
            ->get();

        // Data untuk grafik jumlah buku per kategori
        $kategoriChart = Kategori::withCount('buku')->get();

// TAMBAHKAN kode ini di app/Http/Controllers/DashboardController.php,
// di dalam method index(), sebelum baris "return view(...)".
// Lalu tambahkan 'akanJatuhTempo' ke dalam compact() yang dikirim ke view.
 
$akanJatuhTempo = \App\Models\Peminjaman::with('buku')
    ->where('status', 'Dipinjam')
    ->whereBetween('tanggal_kembali', [now()->startOfDay(), now()->addDays(2)->endOfDay()])
    ->orderBy('tanggal_kembali')
    ->get();
 
// Contoh baris return-nya jadi (sesuaikan dengan variabel lain yang sudah ada):
// return view('dashboard.index', compact(
//     'totalBuku', 'totalStok', 'totalKategori', 'peminjamanAktif',
//     'peminjamanTerlambat', 'peminjamanTerbaru', 'kategoriChart',
//     'bukuTerpopuler', 'akanJatuhTempo'
// ));
 
 
// =========================================================
// BONUS: Badge notifikasi jumlah terlambat di sidebar
// =========================================================
// Supaya badge merah muncul di menu "Peminjaman" pada SEMUA halaman
// (bukan cuma dashboard), daftarkan View Composer.
// Tambahkan kode ini di app/Providers/AppServiceProvider.php,
// di dalam method boot():
 
// use Illuminate\Support\Facades\View;
// use App\Models\Peminjaman;
//
// public function boot(): void
// {
//     View::composer('layouts.app', function ($view) {
//         $jumlahTerlambat = Peminjaman::where('status', 'Terlambat')->count();
//         $view->with('jumlahTerlambatGlobal', $jumlahTerlambat);
//     });
// }
 
        return view('dashboard.index', compact(
            'totalBuku',
            'totalStok',
            'totalKategori',
            'peminjamanAktif',
            'peminjamanTerlambat',
            'totalPeminjaman',
            'bukuTerpopuler',
            'peminjamanTerbaru',
            'kategoriChart'
        ));
    }
}
