<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Buku\Models\Buku;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'User Biasa',
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $bukus = [
            ['Pemrograman Web dengan Laravel', 'Budi Santoso', 'Informatika', 2022, 10],
            ['Basis Data Relasional', 'Siti Aminah', 'Andi Offset', 2020, 8],
            ['Algoritma dan Struktur Data', 'Rudi Hartono', 'Erlangga', 2019, 12],
            ['Jaringan Komputer', 'Dewi Lestari', 'Graha Ilmu', 2021, 6],
            ['Rekayasa Perangkat Lunak', 'Agus Wijaya', 'Informatika', 2023, 9],
        ];

        foreach ($bukus as [$judul, $penulis, $penerbit, $tahun, $stok]) {
            Buku::create([
                'judul' => $judul,
                'penulis' => $penulis,
                'penerbit' => $penerbit,
                'tahun_terbit' => $tahun,
                'stok' => $stok,
            ]);
        }
    }
}
