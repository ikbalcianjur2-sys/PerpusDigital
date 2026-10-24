<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::current();

        return view('setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_perpustakaan' => 'required|string|max:150',
            'alamat'            => 'nullable|string|max:255',
            'telp'              => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:150',
            'jam_operasional'   => 'nullable|string|max:150',
            'lama_peminjaman'   => 'required|integer|min:1|max:90',
            'denda_per_hari'    => 'required|integer|min:0',
            'logo'              => 'nullable|url|max:500',
        ]);

        $setting = Setting::current();
        $setting->update($validated);

        return redirect()->route('setting.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
