<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::withCount('buku');

        if ($search = $request->get('q')) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        $kategori = $query->orderBy('nama_kategori')->paginate(8)->withQueryString();

        return view('kategori.index', compact('kategori', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori',
            'deskripsi'     => 'nullable|string|max:500',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $kategori->id,
            'deskripsi'     => 'nullable|string|max:500',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->buku()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki buku.');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
