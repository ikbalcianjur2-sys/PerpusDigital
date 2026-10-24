<?php
// Simpan sebagai: app/Http/Controllers/LaporanController.php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan: pilih rentang tanggal & status.
     */
    public function index(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $status = $request->get('status', '');

        $peminjaman = Peminjaman::with(['buku', 'anggota'])
            ->whereBetween('tanggal_pinjam', [$dari, $sampai])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('tanggal_pinjam', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('laporan.index', compact('peminjaman', 'dari', 'sampai', 'status'));
    }

    /**
     * Ambil data laporan berdasarkan filter (dipakai bareng oleh CSV & cetak).
     */
    private function ambilData(Request $request)
    {
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));
        $status = $request->get('status', '');

        return Peminjaman::with(['buku', 'anggota'])
            ->whereBetween('tanggal_pinjam', [$dari, $sampai])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('tanggal_pinjam')
            ->get();
    }

    /**
     * Export ke CSV (bisa dibuka di Excel).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $data = $this->ambilData($request);
        $namaFile = 'laporan-peminjaman-' . now()->format('Ymd-His') . '.csv';

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');

            // BOM biar karakter Indonesia (é, dsb) tampil benar di Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Judul Buku', 'Penulis', 'Peminjam', 'No. Identitas', 'Tanggal Pinjam', 'Batas Kembali', 'Tanggal Dikembalikan', 'Status', 'Denda']);

            foreach ($data as $p) {
                fputcsv($handle, [
                    $p->buku->judul ?? '-',
                    $p->buku->penulis ?? '-',
                    $p->anggota->nama ?? '-',
                    $p->anggota->no_identitas ?? '-',
                    $p->tanggal_pinjam->format('d-m-Y'),
                    $p->tanggal_kembali->format('d-m-Y'),
                    optional($p->tanggal_pengembalian)->format('d-m-Y') ?? '-',
                    $p->status,
                    $p->denda ?? 0,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $namaFile, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Halaman versi cetak (rapi untuk di-print jadi PDF lewat browser).
     */
    public function cetak(Request $request)
    {
        $data = $this->ambilData($request);
        $dari = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->format('Y-m-d'));

        return view('laporan.cetak', [
            'data'   => $data,
            'dari'   => $dari,
            'sampai' => $sampai,
        ]);
    }
}