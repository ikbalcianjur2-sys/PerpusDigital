<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Setting default (satu baris konfigurasi)
        Setting::create([
            'nama_perpustakaan' => 'Perpustakaan Digital Nusantara',
            'alamat'            => 'Jl. Pendidikan No. 1, Cianjur, Jawa Barat',
            'telp'              => '0263-123456',
            'email'             => 'info@perpusnusantara.test',
            'jam_operasional'   => 'Senin - Sabtu, 08.00 - 16.00',
            'lama_peminjaman'   => 7,
            'denda_per_hari'    => 1000,
        ]);

        // Kategori
        $kategoris = [
            ['nama_kategori' => 'Fiksi', 'deskripsi' => 'Novel dan cerita fiksi'],
            ['nama_kategori' => 'Non-Fiksi', 'deskripsi' => 'Buku berbasis fakta dan pengetahuan'],
            ['nama_kategori' => 'Teknologi', 'deskripsi' => 'Buku seputar teknologi & pemrograman'],
            ['nama_kategori' => 'Sejarah', 'deskripsi' => 'Buku tentang sejarah dunia dan nasional'],
            ['nama_kategori' => 'Anak & Remaja', 'deskripsi' => 'Bacaan untuk anak dan remaja'],
        ];
        foreach ($kategoris as $k) {
            Kategori::create($k);
        }

        // Buku contoh
        $bukus = [
            ['kategori_id' => 1, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun_terbit' => 2005, 'isbn' => '9789793062792', 'stok' => 5, 'jumlah_dipinjam' => 24],
            ['kategori_id' => 1, 'judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra', 'tahun_terbit' => 1980, 'isbn' => '9789799731234', 'stok' => 3, 'jumlah_dipinjam' => 31],
            ['kategori_id' => 2, 'judul' => 'Sapiens', 'penulis' => 'Yuval Noah Harari', 'penerbit' => 'Pustaka Alvabet', 'tahun_terbit' => 2011, 'isbn' => '9786021637434', 'stok' => 4, 'jumlah_dipinjam' => 40],
            ['kategori_id' => 3, 'judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'penerbit' => 'Prentice Hall', 'tahun_terbit' => 2008, 'isbn' => '9780132350884', 'stok' => 2, 'jumlah_dipinjam' => 18],
            ['kategori_id' => 3, 'judul' => 'Belajar Laravel dari Nol', 'penulis' => 'Budi Santoso', 'penerbit' => 'Elex Media', 'tahun_terbit' => 2021, 'isbn' => '9786230012345', 'stok' => 6, 'jumlah_dipinjam' => 12],
            ['kategori_id' => 4, 'judul' => 'Sejarah Indonesia Modern', 'penulis' => 'M.C. Ricklefs', 'penerbit' => 'Serambi', 'tahun_terbit' => 2005, 'isbn' => '9789793019012', 'stok' => 3, 'jumlah_dipinjam' => 9],
            ['kategori_id' => 5, 'judul' => 'Petualangan Si Kancil', 'penulis' => 'Anonim', 'penerbit' => 'Gramedia', 'tahun_terbit' => 2015, 'isbn' => '9786020312345', 'stok' => 8, 'jumlah_dipinjam' => 5],
        ];
        foreach ($bukus as $b) {
            Buku::create($b);
        }

        // Peminjaman contoh
        Peminjaman::create([
            'buku_id' => 1,
            'nama_peminjam' => 'Ahmad Fauzi',
            'no_identitas' => '2201001',
            'kontak' => '081234567890',
            'tanggal_pinjam' => now()->subDays(3),
            'tanggal_kembali' => now()->addDays(4),
            'status' => 'Dipinjam',
        ]);

        Peminjaman::create([
            'buku_id' => 3,
            'nama_peminjam' => 'Siti Aisyah',
            'no_identitas' => '2201002',
            'kontak' => '081298765432',
            'tanggal_pinjam' => now()->subDays(10),
            'tanggal_kembali' => now()->subDays(3),
            'status' => 'Terlambat',
        ]);

        Peminjaman::create([
            'buku_id' => 4,
            'nama_peminjam' => 'Rudi Hartono',
            'no_identitas' => '2201003',
            'kontak' => '081211112222',
            'tanggal_pinjam' => now()->subDays(14),
            'tanggal_kembali' => now()->subDays(7),
            'tanggal_pengembalian' => now()->subDays(6),
            'status' => 'Dikembalikan',
        ]);
    }
}
