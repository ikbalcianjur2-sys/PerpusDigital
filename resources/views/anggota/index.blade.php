<!-- Simpan sebagai: resources/views/anggota/index.blade.php -->
@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Data Anggota <small>{{ $anggota->total() }} anggota terdaftar</small></div>
            <button class="btn btn-primary" data-open-modal="modalTambahAnggota">+ Tambah Anggota</button>
        </div>

        <form method="GET" action="{{ route('anggota.index') }}" class="filter-bar">
            <div class="search-box">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau no. identitas..." data-live-search>
            </div>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No. Identitas</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Total Pinjam</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggota as $a)
                        <tr>
                            <td><b>{{ $a->nama }}</b></td>
                            <td>{{ $a->no_identitas }}</td>
                            <td>{{ $a->kontak ?: '-' }}</td>
                            <td class="text-muted">{{ $a->alamat ?: '-' }}</td>
                            <td><span class="badge badge-purple">{{ $a->peminjaman_count }}x</span></td>
                            <td>
                                <div class="flex gap-8">
                                    <button class="btn btn-outline btn-sm"
                                        data-open-modal="modalEditAnggota"
                                        data-fill='{"nama":"{{ addslashes($a->nama) }}","no_identitas":"{{ $a->no_identitas }}","kontak":"{{ $a->kontak }}","alamat":"{{ addslashes($a->alamat) }}","formAction":"{{ route('anggota.update', $a) }}"}'>
                                        Edit
                                    </button>
                                    @if (auth()->user()->role === 'admin')
                                        <form action="{{ route('anggota.destroy', $a) }}" method="POST" data-confirm-delete="Hapus anggota '{{ $a->nama }}'?">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="table-empty">Belum ada anggota terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $anggota->links() }}</div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal-backdrop" id="modalTambahAnggota" data-modal>
        <div class="modal-box">
            <form action="{{ route('anggota.store') }}" method="POST">
                @csrf
                <div class="modal-head">
                    <h3>Tambah Anggota</h3>
                    <button type="button" class="modal-close" data-close-modal>&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label>No. Identitas</label>
                        <input type="text" name="no_identitas" required maxlength="50" placeholder="NIM/NIP/NIK">
                    </div>
                    <div class="form-group">
                        <label>Kontak (opsional)</label>
                        <input type="text" name="kontak" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label>Alamat (opsional)</label>
                        <input type="text" name="alamat" maxlength="255">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal-backdrop" id="modalEditAnggota" data-modal>
        <div class="modal-box">
            <form action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-head">
                    <h3>Edit Anggota</h3>
                    <button type="button" class="modal-close" data-close-modal>&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label>No. Identitas</label>
                        <input type="text" name="no_identitas" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label>Kontak (opsional)</label>
                        <input type="text" name="kontak" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label>Alamat (opsional)</label>
                        <input type="text" name="alamat" maxlength="255">
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