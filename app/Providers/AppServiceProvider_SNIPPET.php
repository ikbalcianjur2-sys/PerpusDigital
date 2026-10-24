<?php
/**
 * INI BUKAN FILE UTUH, HANYA CONTOH TAMBAHAN.
 *
 * Buka file app/Providers/AppServiceProvider.php pada project Laravel-mu,
 * lalu tambahkan baris berikut di dalam method boot():
 *
 *     use Illuminate\Pagination\Paginator;
 *
 *     public function boot(): void
 *     {
 *         Paginator::useBootstrap();
 *     }
 *
 * Ini membuat hasil ->links() memakai markup Bootstrap
 * (ul.pagination > li.page-item > a.page-link) yang sudah
 * disiapkan gaya ungu-hijaunya di public/css/app.css.
 */
