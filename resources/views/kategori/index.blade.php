@extends('layouts.app')

@section('title', 'Data Kategori')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Daftar Kategori <small>{{ $kategori->total() }} kategori terdaftar</small></div>
            <button class="btn btn-primary" data-open-modal="modalTambahKategori">+ Tambah Kategori</button>
        </div>

        <form method="GET" action="{{ route('kategori.index') }}" class="filter-bar">
            <div class="search-box">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama kategori..." data-live-search>
            </div>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Buku</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $k)
                        <tr>
                            <td><b>{{ $k->nama_kategori }}</b></td>
                            <td class="text-muted">{{ $k->deskripsi ?: '-' }}</td>
                            <td><span class="badge badge-green">{{ $k->buku_count }} buku</span></td>
                            <td>
                                <div class="flex gap-8">
                                    <button class="btn btn-outline btn-sm"
                                        data-open-modal="modalEditKategori"
                                        data-fill='{"nama_kategori":"{{ addslashes($k->nama_kategori) }}","deskripsi":"{{ addslashes($k->deskripsi) }}","formAction":"{{ route('kategori.update', $k) }}"}'>
                                        Edit
                                    </button>
                                    <form action="{{ route('kategori.destroy', $k) }}" method="POST" data-confirm-delete="Hapus kategori '{{ $k->nama_kategori }}'?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="table-empty">Belum ada kategori. Klik "Tambah Kategori" untuk memulai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $kategori->links() }}</div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal-backdrop" id="modalTambahKategori" data-modal>
        <div class="modal-box">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-head">
                    <h3>Tambah Kategori</h3>
                    <button type="button" class="modal-close" data-close-modal>&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" required maxlength="100" placeholder="Misal: Fiksi">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi (opsional)</label>
                        <textarea name="deskripsi" maxlength="500" placeholder="Deskripsi singkat kategori"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT (form action diisi otomatis via JS) --}}
    <div class="modal-backdrop" id="modalEditKategori" data-modal>
        <div class="modal-box">
            <form action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-head">
                    <h3>Edit Kategori</h3>
                    <button type="button" class="modal-close" data-close-modal>&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi (opsional)</label>
                        <textarea name="deskripsi" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection
