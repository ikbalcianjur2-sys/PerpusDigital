<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- 1. Tambahkan ini
use App\Models\Peminjaman;           // <-- 2. Pastikan Model terkait di-import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 3. PASTE KODE VIEW COMPOSER DARI SNIPPET DI SINI
        // Contoh kodenya akan terlihat seperti ini:
        
        // Ganti 'layouts.sidebar' dengan path view sidebar Anda yang sebenarnya
        View::composer('layouts.sidebar', function ($view) {
            $notifJatuhTempo = Peminjaman::where('status', 'Dipinjam')
                                ->whereDate('tanggal_kembali', '<=', now()->addDays(3))
                                ->count();

            // Variabel $notifJatuhTempo sekarang bisa dipakai langsung di sidebar.blade.php
            $view->with('notifJatuhTempo', $notifJatuhTempo);
        });
    }
}