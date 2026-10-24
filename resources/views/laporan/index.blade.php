<!-- Simpan sebagai: resources/views/laporan/index.blade.php -->
@extends('layouts.app')

@section('title', 'Laporan')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Laporan Peminjaman <small>{{ $peminjaman->total() }} transaksi sesuai filter</small></div>
        </div>

        <form method="GET" action="{{ route('laporan.index') }}" class="filter-bar">
            <div class="form-group mb-0">
                <label>Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}">
            </div>
            <div class="form-group mb-0">
                <label>Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai }}">
            </div>
            <div class="form-group mb-0">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="Dipinjam" {{ $status === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Terlambat" {{ $status === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="Dikembalikan" {{ $status === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="align-self:flex-end;">Terapkan Filter</button>
        </form>

        <div class="flex gap-8 mb-0" style="margin-bottom:16px;">
            <a href="{{ route('laporan.export', request()->query()) }}" class="btn btn-success btn-sm">⬇️ Export ke Excel (CSV)</a>
            <a href="{{ route('laporan.cetak', request()->query()) }}" target="_blank" class="btn btn-outline btn-sm">🖨️ Cetak / Simpan PDF</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Peminjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $p)
                        <tr>
                            <td><b>{{ $p->buku->judul ?? '-' }}</b></td>
                            <td>{{ $p->anggota->nama ?? '-' }}</td>
                            <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                            <td>{{ $p->tanggal_kembali->format('d M Y') }}</td>
                            <td>
                                @if ($p->status === 'Dipinjam')
                                    <span class="badge badge-purple">Dipinjam</span>
                                @elseif ($p->status === 'Terlambat')
                                    <span class="badge badge-red">Terlambat</span>
                                @else
                                    <span class="badge badge-green">Dikembalikan</span>
                                @endif
                            </td>
                            <td>{{ $p->denda > 0 ? 'Rp ' . number_format($p->denda, 0, ',', '.') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="table-empty">Tidak ada data pada rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $peminjaman->links() }}</div>
    </div>

@endsection