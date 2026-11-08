@extends('layouts.app')

@section('title', 'Dashboard - SiPustaka')

@section('content')
<div class="card shadow-sm mb-4 text-white" style="background: linear-gradient(120deg, #1e3a8a, #3b82f6);">
    <div class="card-body p-4">
        <h3 class="mb-1">Selamat datang, {{ auth()->user()->name }}</h3>
        <p class="mb-0 opacity-75">
            Anda masuk sebagai <strong class="text-uppercase">{{ auth()->user()->role }}</strong>.
            Kelola data buku dan peminjaman perpustakaan kampus dari sini.
        </p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 fs-4"><i class="bi bi-journal-bookmark"></i></div>
                <div>
                    <div class="text-muted small">Judul Buku</div>
                    <div class="fs-4 fw-bold">{{ $totalBuku }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success bg-opacity-10 text-success p-3 fs-4"><i class="bi bi-stack"></i></div>
                <div>
                    <div class="text-muted small">Total Stok</div>
                    <div class="fs-4 fw-bold">{{ $totalStok }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3 fs-4"><i class="bi bi-arrow-left-right"></i></div>
                <div>
                    <div class="text-muted small">Sedang Dipinjam</div>
                    <div class="fs-4 fw-bold">{{ $sedangDipinjam }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-danger bg-opacity-10 text-danger p-3 fs-4"><i class="bi bi-people"></i></div>
                <div>
                    <div class="text-muted small">Pengguna</div>
                    <div class="fs-4 fw-bold">{{ $totalUser }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Peminjaman Terbaru</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr><th>Peminjam</th><th>Buku</th><th>Tanggal Pinjam</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($terbaru as $p)
                        <tr>
                            <td>{{ $p->nama_peminjam }}</td>
                            <td>{{ $p->judul_buku }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $p->status === 'Dipinjam' ? 'bg-warning text-dark' : 'bg-success' }}">{{ $p->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data peminjaman.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Akses Cepat</div>
            <div class="list-group list-group-flush">
                @if (Route::has('buku.index'))
                    <a href="{{ route('buku.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-journal-bookmark me-2"></i> Daftar Buku</a>
                @endif
                @if (Route::has('peminjaman.index'))
                    <a href="{{ route('peminjaman.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-arrow-left-right me-2"></i> Data Peminjaman</a>
                @endif
                @if (auth()->user()->role === 'admin' && Route::has('users.index'))
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-people me-2"></i> Manajemen User</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
