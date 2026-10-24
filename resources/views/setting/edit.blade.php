@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')

    <div class="panel" style="max-width:680px;">
        <div class="panel-header">
            <div class="panel-title">Pengaturan Perpustakaan <small>Informasi umum & kebijakan peminjaman</small></div>
        </div>

        <form action="{{ route('setting.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Nama Perpustakaan</label>
                <input type="text" name="nama_perpustakaan" value="{{ old('nama_perpustakaan', $setting->nama_perpustakaan) }}" required maxlength="150">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat', $setting->alamat) }}" maxlength="255">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="telp" value="{{ old('telp', $setting->telp) }}" maxlength="30">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $setting->email) }}" maxlength="150">
                </div>
            </div>

            <div class="form-group">
                <label>Jam Operasional</label>
                <input type="text" name="jam_operasional" value="{{ old('jam_operasional', $setting->jam_operasional) }}" maxlength="150" placeholder="Contoh: Senin - Sabtu, 08.00 - 16.00">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Lama Peminjaman (hari)</label>
                    <input type="number" name="lama_peminjaman" value="{{ old('lama_peminjaman', $setting->lama_peminjaman) }}" min="1" max="90" required>
                </div>
                <div class="form-group">
                    <label>Denda per Hari Telat (Rp)</label>
                    <input type="number" name="denda_per_hari" value="{{ old('denda_per_hari', $setting->denda_per_hari) }}" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Link Logo (opsional)</label>
                <input type="url" name="logo" value="{{ old('logo', $setting->logo) }}" placeholder="https://...">
            </div>

            <button type="submit" class="btn btn-primary">💾 Simpan Pengaturan</button>
        </form>
    </div>

@endsection
