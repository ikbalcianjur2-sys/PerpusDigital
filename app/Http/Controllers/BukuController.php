<?php
// CONTOH app/Http/Controllers/BukuController.php dengan upload cover.
// Kalau kamu sudah punya BukuController sendiri, JANGAN ditimpa semua —
// cukup salin bagian store(), update(), dan destroy() ke controller kamu,
// lalu sesuaikan nama variabel/relasi kalau berbeda.

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', '');
        $kategoriId = $request->get('kategori_id', '');

        $buku = Buku::with('kategori')
            ->when($search, function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->when($kategoriId, fn ($query) => $query->where('kategori_id', $kategoriId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoriList = Kategori::orderBy('nama_kategori')->get();

        return view('buku.index', compact('buku', 'kategoriList', 'search', 'kategoriId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:255'],
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'penerbit'     => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1500', 'max:' . (date('Y') + 1)],
            'isbn'         => ['nullable', 'string', 'max:32'],
            'stok'         => ['required', 'integer', 'min:0'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'deskripsi'    => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->hasFile('cover')) {
            // Disimpan di storage/app/public/covers, path relatif disimpan di DB
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, Buku $buku)
    {
        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:255'],
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'penerbit'     => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1500', 'max:' . (date('Y') + 1)],
            'isbn'         => ['nullable', 'string', 'max:32'],
            'stok'         => ['required', 'integer', 'min:0'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'deskripsi'    => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->hasFile('cover')) {
            // Hapus cover lama biar tidak numpuk file yang tidak terpakai
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}