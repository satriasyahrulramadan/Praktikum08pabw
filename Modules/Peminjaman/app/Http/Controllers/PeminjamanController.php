<?php

namespace Modules\Peminjaman\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Peminjaman\Http\Requests\PeminjamanRequest;
use Modules\Peminjaman\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index(): View
    {
        return view('peminjaman::index');
    }

    public function data(): JsonResponse
    {
        return response()->json(Peminjaman::latest()->get());
    }

    public function store(PeminjamanRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
        }

        $p = Peminjaman::create($data);

        return response()->json(['message' => 'Data berhasil ditambahkan.', 'data' => $p], 201);
    }

    public function update(PeminjamanRequest $request, Peminjaman $peminjaman): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('bukti')) {
            if ($peminjaman->bukti) {
                Storage::disk('public')->delete($peminjaman->bukti);
            }
            $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
        }

        $peminjaman->update($data);

        return response()->json(['message' => 'Data berhasil diubah.', 'data' => $peminjaman]);
    }

    public function destroy(Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->bukti) {
            Storage::disk('public')->delete($peminjaman->bukti);
        }

        $peminjaman->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}