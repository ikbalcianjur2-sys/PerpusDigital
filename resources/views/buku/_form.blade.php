<!-- Ganti isi resources/views/buku/_form.blade.php dengan ini -->
<div class="modal-head">
    <h3>{{ $judulModal ?? 'Tambah Buku' }}</h3>
    <button type="button" class="modal-close" data-close-modal>&times;</button>
</div>
<div class="modal-body">
    <div class="form-group">
        <label>Judul Buku</label>
        <input type="text" name="judul" required maxlength="255" placeholder="Judul buku">
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" required maxlength="255">
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" maxlength="255">
        </div>
        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" min="1500" max="{{ date('Y') + 1 }}">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>ISBN</label>
            <input type="text" name="isbn" maxlength="32">
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" min="0" required value="0">
        </div>
    </div>

    <div class="form-group">
        <label>Cover Buku (opsional)</label>

        {{-- Preview cover yang sudah ada, khusus mode edit --}}
        <div id="coverPreviewWrap" style="display:none; margin-bottom:10px;">
            <img id="coverPreviewImg" src="" alt="Cover saat ini"
                 style="width:90px; height:120px; object-fit:cover; border-radius:8px; border:1px solid var(--gray-300);">
            <p class="text-sm text-muted" style="margin:6px 0 0 0;">Cover saat ini. Pilih file baru untuk menggantinya.</p>
        </div>

        <input type="file" name="cover" accept="image/png, image/jpeg, image/webp">
        <p class="text-sm text-muted" style="margin-top:6px;">Format JPG/PNG/WEBP, maksimal 2MB.</p>
    </div>

    <div class="form-group">
        <label>Deskripsi (opsional)</label>
        <textarea name="deskripsi" maxlength="1000"></textarea>
    </div>
</div>