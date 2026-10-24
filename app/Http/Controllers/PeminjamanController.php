<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Setting;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Perbarui status yang lewat tenggat menjadi "Terlambat" secara otomatis
        Peminjaman::where('status', 'Dipinjam')
            ->whereDate('tanggal_kembali', '<', now())
            ->update(['status' => 'Terlambat']);

        $query = Peminjaman::with(['buku', 'anggota']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('q')) {
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%");
            });
        }

        $peminjaman = $query->latest('tanggal_pinjam')->paginate(6)->withQueryString();
        $bukuTersedia = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        $anggotaTersedia = Anggota::orderBy('nama')->get();

        return view('peminjaman.index', compact('peminjaman', 'bukuTersedia', 'anggotaTersedia', 'status', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id'       => 'required|exists:bukus,id',
            'anggota_id'    => 'required|exists:anggotas,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok <= 0) {
            return redirect()->route('peminjaman.index')->with('error', 'Stok buku "' . $buku->judul . '" sedang habis.');
        }

        $lamaPeminjaman = Setting::current()->lama_peminjaman ?? 7;

        Peminjaman::create([
            'buku_id'         => $buku->id,
            'anggota_id'      => $validated['anggota_id'],
            'tanggal_pinjam'  => $validated['tanggal_pinjam'],
            'tanggal_kembali' => date('Y-m-d', strtotime($validated['tanggal_pinjam'] . " +{$lamaPeminjaman} days")),
            'status'          => 'Dipinjam',
        ]);

        $buku->decrement('stok');
        $buku->increment('jumlah_dipinjam');

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    // Tandai buku sebagai sudah dikembalikan + hitung denda otomatis jika telat
    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'Dikembalikan') {
            return redirect()->route('peminjaman.index')->with('error', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        $hariTelat = 0;
        if (now()->startOf('day')->gt($peminjaman->tanggal_kembali->copy()->startOf('day'))) {
            $hariTelat = now()->startOfDay()->diffInDays($peminjaman->tanggal_kembali->startOfDay());
        }

        $dendaPerHari = Setting::current()->denda_per_hari ?? 1000;
        $denda = $hariTelat * $dendaPerHari;

        $peminjaman->update([
            'status'                => 'Dikembalikan',
            'tanggal_pengembalian'  => now(),
            'denda'                 => $denda,
        ]);

        $peminjaman->buku->increment('stok');

        $pesan = $denda > 0
            ? "Buku berhasil dikembalikan. Denda keterlambatan: Rp " . number_format($denda, 0, ',', '.')
            : 'Buku berhasil dikembalikan tepat waktu.';

        return redirect()->route('peminjaman.index')->with('success', $pesan);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'Dikembalikan') {
            $peminjaman->buku->increment('stok');
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}