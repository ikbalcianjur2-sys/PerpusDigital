<!-- Ganti isi resources/views/buku/index.blade.php dengan ini -->
@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Data Buku <small>{{ $buku->total() }} judul buku</small></div>
            @if ($kategoriList->isEmpty())
                <button class="btn btn-primary" disabled title="Tambahkan kategori terlebih dahulu">+ Tambah Buku</button>
            @else
                <button class="btn btn-primary" data-open-modal="modalTambahBuku">+ Tambah Buku</button>
            @endif
        </div>

        <form method="GET" action="{{ route('buku.index') }}" class="filter-bar">
            <div class="search-box">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul, penulis, atau ISBN..." data-live-search>
            </div>
            <select name="kategori_id" data-auto-submit>
                <option value="">Semua Kategori</option>
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}" {{ (string) $kategoriId === (string) $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:56px;">Cover</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Dipinjam</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($buku as $b)
                        <tr>
                            <td>
                                @if ($b->cover)
                                    <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}"
                                         style="width:40px; height:54px; object-fit:cover; border-radius:6px; border:1px solid var(--gray-300);">
                                @else
                                    <div style="width:40px; height:54px; border-radius:6px; background:var(--gray-100); display:flex; align-items:center; justify-content:center; font-size:16px;">📕</div>
                                @endif
                            </td>
                            <td><b>{{ $b->judul }}</b><div class="text-sm text-muted">{{ $b->isbn ?: 'Tanpa ISBN' }}</div></td>
                            <td>{{ $b->penulis }}</td>
                            <td><span class="badge badge-purple">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                            <td>{{ $b->tahun_terbit ?: '-' }}</td>
                            <td>
                                @if ($b->stok > 0)
                                    <span class="badge badge-green">{{ $b->stok }} tersedia</span>
                                @else
                                    <span class="badge badge-red">Habis</span>
                                @endif
                            </td>
                            <td>{{ $b->jumlah_dipinjam }}x</td>
                            <td>
                                <div class="flex gap-8">
                                    <button class="btn btn-outline btn-sm"
                                        data-open-modal="modalEditBuku"
                                        data-fill='{"kategori_id":"{{ $b->kategori_id }}","judul":"{{ addslashes($b->judul) }}","penulis":"{{ addslashes($b->penulis) }}","penerbit":"{{ addslashes($b->penerbit) }}","tahun_terbit":"{{ $b->tahun_terbit }}","isbn":"{{ $b->isbn }}","stok":"{{ $b->stok }}","cover":"{{ $b->cover ? asset('storage/' . $b->cover) : '' }}","deskripsi":"{{ addslashes($b->deskripsi) }}","formAction":"{{ route('buku.update', $b) }}"}'>
                                        Edit
                                    </button>
                                    @if (auth()->user()->role === 'admin')
                                        <form action="{{ route('buku.destroy', $b) }}" method="POST" data-confirm-delete="Hapus buku '{{ $b->judul }}'?">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="table-empty">Belum ada data buku yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $buku->links() }}</div>
    </div>

    {{-- MODAL TAMBAH BUKU --}}
    <div class="modal-backdrop" id="modalTambahBuku" data-modal>
        <div class="modal-box">
            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('buku._form')
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT BUKU --}}
    <div class="modal-backdrop" id="modalEditBuku" data-modal>
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