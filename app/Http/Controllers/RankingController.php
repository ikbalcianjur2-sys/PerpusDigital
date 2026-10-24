<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($kategoriId = $request->get('kategori_id')) {
            $query->where('kategori_id', $kategoriId);
        }

        $ranking = $query->orderByDesc('jumlah_dipinjam')->paginate(10)->withQueryString();

        // Nomor urut ranking yang konsisten walau berpindah halaman
        $mulaiDari = ($ranking->currentPage() - 1) * $ranking->perPage();

        $kategoriList = \App\Models\Kategori::orderBy('nama_kategori')->get();

        return view('ranking.index', compact('ranking', 'mulaiDari', 'kategoriList', 'kategoriId'));
    }
}
