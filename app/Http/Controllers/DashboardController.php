<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Modules\Buku\Models\Buku;
use Modules\Peminjaman\Models\Peminjaman;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totalBuku' => Buku::count(),
            'totalStok' => (int) Buku::sum('stok'),
            'sedangDipinjam' => Peminjaman::where('status', 'Dipinjam')->count(),
            'totalUser' => User::count(),
            'terbaru' => Peminjaman::latest()->take(5)->get(),
        ]);
    }
}
