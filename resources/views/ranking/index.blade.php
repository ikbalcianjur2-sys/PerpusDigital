<!-- Ganti isi resources/views/ranking/index.blade.php dengan ini -->
@extends('layouts.app')

@section('title', 'Ranking Buku')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Ranking Buku Terpopuler <small>Diurutkan dari jumlah paling sering dipinjam</small></div>
        </div>

        <form method="GET" action="{{ route('ranking.index') }}" class="filter-bar">
            <select name="kategori_id" data-auto-submit>
                <option value="">Semua Kategori</option>
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}" {{ (string) $kategoriId === (string) $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </form>

        @if ($ranking->isEmpty())
            <div class="empty-state">
                <div class="icon">🏆</div>
                <p>Belum ada data untuk ditampilkan.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th style="width:56px;">Cover</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Total Dipinjam</th>
                            <th style="min-width:140px;">Popularitas</th>
                            <th style="width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $max = $ranking->max('jumlah_dipinjam') ?: 1; @endphp
                        @foreach ($ranking as $i => $b)
                            @php
                                $peringkat = $mulaiDari + $i + 1;
                                $medalClass = match(true) {
                                    $peringkat === 1 => 'gold',
                                    $peringkat === 2 => 'silver',
                                    $peringkat === 3 => 'bronze',
                                    default => '',
                                };
                            @endphp
                            <tr>
                                <td><span class="rank-badge {{ $medalClass }}">{{ $peringkat }}</span></td>
                                <td>
                                    @if ($b->cover)
                                        <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}"
                                             style="width:40px; height:54px; object-fit:cover; border-radius:6px; border:1px solid var(--gray-300);">
                                    @else
                                        <div style="width:40px; height:54px; border-radius:6px; background:var(--gray-100); display:flex; align-items:center; justify-content:center; font-size:16px;">📕</div>
                                    @endif
                                </td>
                                <td><b>{{ $b->judul }}</b></td>
                                <td>{{ $b->penulis }}</td>
                                <td><span class="badge badge-purple">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                                <td>{{ $b->jumlah_dipinjam }}x</td>
                                <td>
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width: {{ ($b->jumlah_dipinjam / $max) * 100 }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex gap-8">
                                        <button class="btn btn-outline btn-sm"
                                            data-open-modal="modalEditRanking"
                                            data-fill='{"kategori_id":"{{ $b->kategori_id }}","judul":"{{ addslashes($b->judul) }}","penulis":"{{ addslashes($b->penulis) }}","penerbit":"{{ addslashes($b->penerbit) }}","tahun_terbit":"{{ $b->tahun_terbit }}","isbn":"{{ $b->isbn }}","stok":"{{ $b->stok }}","cover":"{{ $b->cover ? asset('storage/' . $b->cover) : '' }}","deskripsi":"{{ addslashes($b->deskripsi) }}","formAction":"{{ route('buku.update', $b) }}"}'>
                                            Edit
                                        </button>
                                        @if (auth()->user()->role === 'admin')
                                            <form action="{{ route('buku.destroy', $b) }}" method="POST" data-confirm-delete="Hapus buku '{{ $b->judul }}' dari daftar ranking?">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $ranking->links() }}</div>
        @endif
    </div>

    {{-- MODAL EDIT BUKU (dipakai bersama dari halaman Ranking) --}}
    <div class="modal-backdrop" id="modalEditRanking" data-modal>
        <div class="modal-box">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('buku._form', ['judulModal' => 'Edit Buku'])
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection