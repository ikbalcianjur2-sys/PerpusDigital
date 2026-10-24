<!-- Ganti isi resources/views/dashboard/index.blade.php dengan ini -->
@extends('layouts.app')

@section('title', 'Dashboard')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
@endpush

@section('content')

    <div class="dashboard-greeting">
        <h2>Halo, {{ auth()->user()->name ?? 'Admin' }} 👋</h2>
        <p>Berikut ringkasan aktivitas perpustakaan hari ini.</p>
    </div>
    <!-- TAMBAHKAN blok panel ini di resources/views/dashboard/index.blade.php,
     taruh persis SETELAH .dashboard-greeting dan SEBELUM .stat-grid -->

@if (isset($akanJatuhTempo) && $akanJatuhTempo->isNotEmpty())
    <div class="panel" style="border-left:4px solid var(--amber-500);">
        <div class="panel-header">
            <div class="panel-title">🔔 Segera Jatuh Tempo <small>Batas kembali dalam 2 hari ke depan</small></div>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Peminjam</th>
                        <th>Batas Kembali</th>
                        <th>Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($akanJatuhTempo as $p)
                        @php $sisaHari = now()->startOfDay()->diffInDays($p->tanggal_kembali->startOfDay(), false); @endphp
                        <tr>
                            <td>{{ $p->buku->judul ?? '-' }}</td>
                            <td>{{ $p->nama_peminjam }}</td>
                            <td>{{ $p->tanggal_kembali->format('d M Y') }}</td>
                            <td>
                                @if ($sisaHari <= 0)
                                    <span class="badge badge-red">Jatuh tempo hari ini</span>
                                @else
                                    <span class="badge badge-amber">{{ $sisaHari }} hari lagi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon">📗</div>
            <div>
                <div class="stat-value">{{ $totalBuku }}</div>
                <div class="stat-label">Judul Buku</div>
            </div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">📦</div>
            <div>
                <div class="stat-value">{{ $totalStok }}</div>
                <div class="stat-label">Total Stok Buku</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div>
                <div class="stat-value">{{ $totalKategori }}</div>
                <div class="stat-label">Kategori</div>
            </div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon">🔄</div>
            <div>
                <div class="stat-value">{{ $peminjamanAktif }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon">⏰</div>
            <div>
                <div class="stat-value">{{ $peminjamanTerlambat }}</div>
                <div class="stat-label">Terlambat</div>
            </div>
        </div>
    </div>

    <div class="two-col-grid">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Peminjaman Terbaru <small>6 transaksi terakhir</small></div>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
            </div>

            @if ($peminjamanTerbaru->isEmpty())
                <div class="empty-state">
                    <div class="icon">🗂️</div>
                    <p>Belum ada data peminjaman.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Peminjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peminjamanTerbaru as $p)
                                <tr>
                                    <td>{{ $p->buku->judul ?? '-' }}</td>
                                    <td>{{ $p->nama_peminjam }}</td>
                                    <td>{{ $p->tanggal_kembali->format('d M Y') }}</td>
                                    <td>
                                        @if ($p->status === 'Dipinjam')
                                            <span class="badge badge-purple">Dipinjam</span>
                                        @elseif ($p->status === 'Terlambat')
                                            <span class="badge badge-red">Terlambat</span>
                                        @else
                                            <span class="badge badge-green">Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Distribusi Buku per Kategori</div>
            </div>
            @if ($kategoriChart->isEmpty())
                <div class="empty-state">
                    <div class="icon">📊</div>
                    <p>Belum ada kategori.</p>
                </div>
            @else
                <div style="height:230px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
                <script type="application/json" id="chartData">{!! $kategoriChart->toJson() !!}</script>
            @endif
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Buku Terpopuler <small>Berdasarkan jumlah dipinjam</small></div>
            <a href="{{ route('ranking.index') }}" class="btn btn-outline btn-sm">Lihat Ranking Lengkap</a>
        </div>

        @if ($bukuTerpopuler->isEmpty())
            <div class="empty-state">
                <div class="icon">📚</div>
                <p>Belum ada data buku.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Dipinjam</th>
                            <th>Popularitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $max = $bukuTerpopuler->max('jumlah_dipinjam') ?: 1; @endphp
                        @foreach ($bukuTerpopuler as $i => $b)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><b>{{ $b->judul }}</b></td>
                                <td><span class="badge badge-purple">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                                <td>{{ $b->jumlah_dipinjam }}x</td>
                                <td style="min-width:120px;">
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width: {{ ($b->jumlah_dipinjam / $max) * 100 }}%;"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection