<?php
// Simpan sebagai: app/Http/Controllers/AnggotaController.php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', '');

        $anggota = Anggota::withCount('peminjaman')
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_identitas', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('anggota.index', compact('anggota', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'no_identitas' => ['required', 'string', 'max:50', 'unique:anggotas,no_identitas'],
            'kontak'       => ['nullable', 'string', 'max:50'],
            'alamat'       => ['nullable', 'string', 'max:255'],
        ]);

        Anggota::create($data);

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, Anggota $anggota)
    {
        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'no_identitas' => ['required', 'string', 'max:50', 'unique:anggotas,no_identitas,' . $anggota->id],
            'kontak'       => ['nullable', 'string', 'max:50'],
            'alamat'       => ['nullable', 'string', 'max:255'],
        ]);

        $anggota->update($data);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->peminjaman()->where('status', '!=', 'Dikembalikan')->exists()) {
            return back()->with('error', 'Anggota ini masih punya peminjaman aktif, tidak bisa dihapus.');
        }

        $anggota->delete();

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}