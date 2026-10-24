# 📚 Aplikasi Perpustakaan (Laravel) — Tema Ungu & Hijau

Paket ini berisi **file aplikasi** (migration, model, controller, view, CSS, JS) untuk
dipasang ke dalam project Laravel. Ini bukan project Laravel utuh (karena tidak ada akses
Composer/Packagist di lingkungan pembuatan file ini), jadi ikuti langkah di bawah untuk menyambungkannya.

## Struktur folder paket ini

```
app/Http/Controllers/   -> DashboardController, KategoriController, BukuController,
                            PeminjamanController, RankingController, SettingController
app/Models/              -> Kategori, Buku, Peminjaman, Setting
app/Providers/           -> catatan tambahan untuk AppServiceProvider (bukan file utuh)
database/migrations/     -> 4 file migration
database/seeders/        -> DatabaseSeeder.php (data contoh)
routes/web.php           -> semua route aplikasi
resources/views/         -> layouts/app.blade.php + halaman dashboard, kategori, buku, peminjaman, ranking, setting
public/css/app.css       -> seluruh styling (tema ungu-hijau)
public/js/app.js         -> seluruh interaktivitas (modal, sidebar, filter, dsb)
```

## 1. Buat project Laravel baru (jika belum punya)

```bash
composer create-project laravel/laravel perpustakaan
cd perpustakaan
```

## 2. Salin file dari paket ini ke project Laravel

Salin/timpa folder-folder berikut ke dalam project Laravel kamu (struktur nama & lokasi sudah sama persis):

| Dari paket ini                          | Ke project Laravel                          |
|------------------------------------------|----------------------------------------------|
| `app/Models/*`                           | `app/Models/`                                |
| `app/Http/Controllers/*`                 | `app/Http/Controllers/`                      |
| `database/migrations/*`                  | `database/migrations/`                       |
| `database/seeders/DatabaseSeeder.php`    | `database/seeders/` (timpa file yang ada)    |
| `routes/web.php`                         | `routes/` (timpa file yang ada)               |
| `resources/views/*`                      | `resources/views/`                           |
| `public/css/app.css`                     | `public/css/`                                |
| `public/js/app.js`                       | `public/js/`                                 |

> Catatan: file `app/Providers/AppServiceProvider_SNIPPET.php` **bukan** file yang perlu disalin apa
> adanya — buka isinya dan ikuti instruksinya untuk menambahkan 2 baris ke `AppServiceProvider.php`
> milikmu (supaya tampilan pagination memakai style Bootstrap yang sudah kita desain).

## 3. Atur koneksi database

Buka file `.env` di project Laravel, sesuaikan:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `perpustakaan` di MySQL (via phpMyAdmin/HeidiSQL/CLI).

## 4. Aktifkan style pagination Bootstrap

Buka `app/Providers/AppServiceProvider.php`, tambahkan:

```php
use Illuminate\Pagination\Paginator;

public function boot(): void
{
    Paginator::useBootstrap();
}
```

## 5. Jalankan migration + seeder (data contoh)

```bash
php artisan migrate:fresh --seed
```

Ini akan membuat tabel `kategoris`, `bukus`, `peminjaman`, `settings`, sekaligus mengisi
beberapa data contoh (kategori, buku, dan peminjaman) supaya dashboard & ranking langsung
terlihat isinya.

## 6. Jalankan server

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser. Sidebar akan menampilkan menu:
**Dashboard, Data Buku, Kategori, Peminjaman, Ranking Buku, Pengaturan**.

## Ringkasan fitur

- **Dashboard** — kartu statistik (total buku, stok, kategori, peminjaman aktif/terlambat),
  grafik distribusi buku per kategori (Chart.js via CDN), daftar peminjaman terbaru, dan
  daftar buku terpopuler.
- **Data Buku** — tabel dengan **pagination**, pencarian (judul/penulis/ISBN), filter kategori,
  tambah/edit lewat modal, hapus dengan konfirmasi (buku yang sedang dipinjam tidak bisa dihapus).
- **Kategori** — tabel dengan **pagination**, pencarian, tambah/edit lewat modal, hapus
  (ditolak otomatis jika kategori masih memiliki buku).
- **Peminjaman** — tampilan **kartu (card)**, filter status (Dipinjam/Terlambat/Dikembalikan),
  pencarian nama/no. identitas, form pinjam lewat modal (tanggal kembali dihitung otomatis
  sesuai pengaturan lama peminjaman), tombol "Kembalikan" yang otomatis menghitung denda jika telat
  dan mengembalikan stok buku.
- **Ranking Buku** — tabel dengan **pagination**, diurutkan dari jumlah dipinjam terbanyak,
  badge emas/perak/perunggu untuk 3 besar, filter kategori, bar popularitas.
- **Pengaturan** — nama perpustakaan, alamat, kontak, jam operasional, lama peminjaman (hari),
  besar denda per hari keterlambatan (rupiah).

## Cara kerja interaktivitas (public/js/app.js)

- **Sidebar responsif**: tombol ☰ membuka/menutup sidebar di layar kecil (mobile/tablet).
- **Modal generik**: tombol dengan `data-open-modal="idModal"` akan membuka modal terkait;
  atribut `data-fill='{...}'` (JSON) dipakai untuk mengisi otomatis form edit + mengubah `action`
  form ke route update yang benar — jadi satu modal bisa dipakai untuk banyak baris data.
- **Konfirmasi hapus**: form dengan `data-confirm-delete="pesan"` akan menampilkan dialog
  konfirmasi sebelum submit.
- **Filter otomatis**: elemen `<select data-auto-submit>` langsung submit form saat nilainya
  berubah (tanpa perlu klik tombol "Cari").
- **Live search**: input dengan `data-live-search` otomatis submit form 600ms setelah user
  berhenti mengetik (debounce).
- **Alert otomatis hilang**: notifikasi sukses/gagal otomatis hilang setelah 5 detik, atau bisa
  ditutup manual lewat tombol ×.
- **Grafik dashboard**: dibaca dari tag `<script type="application/json" id="chartData">` yang
  di-generate blade, lalu dirender pakai Chart.js (doughnut chart kategori).

## Warna tema

Semua warna diatur lewat CSS variables di bagian atas `public/css/app.css` (`:root { ... }`),
jadi kalau mau mengganti nuansa ungu/hijau tinggal ubah value `--purple-*` dan `--green-*` di satu
tempat saja.

## Catatan tambahan

- Kolom "cover" dan "logo" memakai **link URL gambar** (bukan upload file) supaya setup tetap
  sederhana tanpa konfigurasi storage/symlink. Jika nanti ingin upload file asli, tinggal ubah
  input tersebut menjadi `type="file"` dan sesuaikan controller memakai `Storage::disk('public')`.
- Field peminjam (nama, no. identitas, kontak) disimpan langsung di tabel `peminjaman` (tanpa
  tabel anggota terpisah) supaya alur peminjaman tetap ringkas. Bisa dikembangkan lebih lanjut
  jadi tabel `anggota` + relasi jika dibutuhkan sistem login anggota.
