<!-- Ganti isi resources/views/peminjaman/index.blade.php dengan ini -->
@extends('layouts.app')

@section('title', 'Peminjaman Buku')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Peminjaman Buku <small>{{ $peminjaman->total() }} transaksi</small></div>
            @if ($bukuTersedia->isEmpty())
                <button class="btn btn-primary" disabled title="Tidak ada buku dengan stok tersedia">+ Catat Peminjaman</button>
            @else
                <button class="btn btn-primary" data-open-modal="modalTambahPinjam">+ Catat Peminjaman</button>
            @endif
        </div>

        <form method="GET" action="{{ route('peminjaman.index') }}" class="filter-bar">
            <div class="search-box">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama peminjam / no. identitas..." data-live-search>
            </div>
            <select name="status" data-auto-submit>
                <option value="">Semua Status</option>
                <option value="Dipinjam" {{ $status === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="Terlambat" {{ $status === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="Dikembalikan" {{ $status === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </form>

        @if ($peminjaman->isEmpty())
            <div class="empty-state">
                <div class="icon">🗂️</div>
                <p>Tidak ada data peminjaman yang cocok dengan filter.</p>
            </div>
        @else
            <div class="card-grid">
                @foreach ($peminjaman as $p)
                    @php
                        $statusClass = match($p->status) {
                            'Terlambat' => 'status-terlambat',
                            'Dikembalikan' => 'status-dikembalikan',
                            default => '',
                        };
                    @endphp
                    <div class="loan-card {{ $statusClass }}">
                        <div class="loan-card-top">
                            <div class="loan-cover">
                                @if (optional($p->buku)->cover)
                                    <img src="{{ asset('storage/' . $p->buku->cover) }}" alt="{{ $p->buku->judul }}">
                                @else
                                    <div class="loan-cover-placeholder">📕</div>
                                @endif
                            </div>

                            <div class="loan-card-info">
                                <div class="loan-card-head">
                                    <div>
                                        <div class="loan-book-title">{{ $p->buku->judul ?? 'Buku telah dihapus' }}</div>
                                        <div class="loan-book-meta">{{ $p->buku->penulis ?? '-' }}</div>
                                    </div>
                                    @if ($p->status === 'Dipinjam')
                                        <span class="badge badge-purple">Dipinjam</span>
                                    @elseif ($p->status === 'Terlambat')
                                        <span class="badge badge-red">Terlambat</span>
                                    @else
                                        <span class="badge badge-green">Dikembalikan</span>
                                    @endif
                                </div>

                                <div class="loan-info">
                                    <span>👤 <b>{{ $p->anggota->nama ?? 'Anggota dihapus' }}</b> ({{ $p->anggota->no_identitas ?? '-' }})</span>
                                    @if (optional($p->anggota)->kontak)
                                        <span>📞 {{ $p->anggota->kontak }}</span>
                                    @endif
                                    <span>📅 Pinjam: <b>{{ $p->tanggal_pinjam->format('d M Y') }}</b></span>
                                    <span>⏳ Batas Kembali: <b>{{ $p->tanggal_kembali->format('d M Y') }}</b></span>
                                    @if ($p->status === 'Dikembalikan')
                                        <span>✅ Dikembalikan: <b>{{ optional($p->tanggal_pengembalian)->format('d M Y') }}</b></span>
                                        @if ($p->denda > 0)
                                            <span>💰 Denda: <b>Rp {{ number_format($p->denda, 0, ',', '.') }}</b></span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="loan-card-footer">
                            @if ($p->status !== 'Dikembalikan')
                                <form action="{{ route('peminjaman.kembalikan', $p) }}" method="POST" style="flex:1;" data-confirm-delete="Tandai buku ini sebagai sudah dikembalikan?">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm btn-block">Kembalikan</button>
                                </form>
                            @endif
                            <form action="{{ route('peminjaman.destroy', $p) }}" method="POST" data-confirm-delete="Hapus data peminjaman ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrap">{{ $peminjaman->links() }}</div>
        @endif
    </div>

    {{-- MODAL TAMBAH PEMINJAMAN --}}
    <div class="modal-backdrop" id="modalTambahPinjam" data-modal>
        <div class="modal-box">
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <div class="modal-head">
                    <h3>Catat Peminjaman Baru</h3>
                    <button type="button" class="modal-close" data-close-modal>&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Buku</label>
                        <select name="buku_id" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($bukuTersedia as $b)
                                <option value="{{ $b->id }}">{{ $b->judul }} (stok: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Anggota</label>
                        <select name="anggota_id" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach ($anggotaTersedia as $a)
                                <option value="{{ $a->id }}">{{ $a->nama }} ({{ $a->no_identitas }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                        <div class="form-group">
                            <label>Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <p class="text-sm text-muted">Batas pengembalian akan dihitung otomatis sesuai durasi pada halaman <b>Pengaturan</b>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>

@endsection