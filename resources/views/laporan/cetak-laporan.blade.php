<!-- Simpan sebagai: resources/views/laporan/cetak.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; color: #1f2937; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        p.subtitle { color: #6b7280; font-size: 13px; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 12.5px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; text-align: left; }
        th { background: #f3f4f6; }
        .btn-print {
            background: #7c3aed; color: #fff; border: none; padding: 9px 18px;
            border-radius: 8px; font-size: 13px; cursor: pointer; margin-bottom: 16px;
        }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>

    <h1>{{ \App\Models\Setting::current()->nama_perpustakaan ?? 'Perpustakaan' }}</h1>
    <p class="subtitle">Laporan Peminjaman Buku &mdash; {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Judul Buku</th>
                <th>Peminjam</th>
                <th>No. Identitas</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ $p->anggota->nama ?? '-' }}</td>
                    <td>{{ $p->anggota->no_identitas ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                    <td>{{ $p->tanggal_kembali->format('d-m-Y') }}</td>
                    <td>{{ $p->status }}</td>
                    <td>{{ $p->denda > 0 ? 'Rp ' . number_format($p->denda, 0, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center; color:#6b7280;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top:30px; font-size:11.5px; color:#9ca3af;">
        Dicetak pada {{ now()->format('d M Y, H:i') }} WIB
    </p>
</body>
</html>