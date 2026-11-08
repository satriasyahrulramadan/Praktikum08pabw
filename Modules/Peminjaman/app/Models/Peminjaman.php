<?php

namespace Modules\Peminjaman\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'nama_peminjam',
        'judul_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'bukti',
    ];

    protected $appends = ['bukti_url'];

    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti ? Storage::url($this->bukti) : null;
    }
}