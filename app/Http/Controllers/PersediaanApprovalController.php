<?php

namespace App\Http\Controllers;

use App\Models\Persedian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersediaanApprovalController extends Controller
{
    public function index()
    {
        $persediaan = Persedian::with('barang')
            ->where('approval_status', 'menunggu')
            ->latest()
            ->get();

        $riwayat = Persedian::with('barang')
            ->whereIn('approval_status', ['diterima', 'ditolak'])
            ->latest()
            ->take(10)
            ->get();

        return view('spv.persediaan.index', compact('persediaan', 'riwayat'));
    }

    public function show(Persedian $persedian)
    {
        abort_unless($persedian->approval_status === 'menunggu', 404);

        $persedian->load('barang');

        return view('spv.persediaan.show', compact('persedian'));
    }

    public function approve(Persedian $persedian, Request $request)
    {
        abort_unless($persedian->approval_status === 'menunggu', 404);

        $validated = $request->validate([
            'catatan_approval' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($persedian, $validated) {
            $persedian->barang->increment('stok_tersedia', $persedian->qty);

            $persedian->update([
                'approval_status' => 'diterima',
                'catatan_approval' => $validated['catatan_approval'] ?? null,
            ]);
        });

        return redirect()
            ->route('spv.persediaan.index')
            ->with('success', "Persediaan untuk {$persedian->barang->nama_barang} berhasil disetujui");
    }

    public function reject(Persedian $persedian, Request $request)
    {
        abort_unless($persedian->approval_status === 'menunggu', 404);

        $validated = $request->validate([
            'catatan_approval' => ['required', 'string', 'max:500'],
        ]);

        $persedian->update([
            'approval_status' => 'ditolak',
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return redirect()
            ->route('spv.persediaan.index')
            ->with('success', "Persediaan untuk {$persedian->barang->nama_barang} ditolak");
    }
}
