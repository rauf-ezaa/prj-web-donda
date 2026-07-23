<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'menunggu_spv'); // default kalau nggak ada filter

    $query = Peminjaman::with('requestedBy', 'approvedBy')->latest();

    if ($status !== 'semua') {
        $query->where('status', $status);
    } else {
        $query->where('status', '!=', 'draft'); // "semua" tetap exclude draft
    }

    $peminjaman = $query->get();

    // hitung jumlah per status, buat badge di dropdown (opsional tapi berguna)
    $counts = [
				'menunggu_spv' => Peminjaman::where('status','menunggu_spv')->count(),
        'pending' => Peminjaman::where('status', 'pending')->count(),
        'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
        'rejected' => Peminjaman::where('status', 'rejected')->count(),
    ];

        return view('spv.peminjaman.index', compact('peminjaman','query','status','counts'));
    }

   public function show(Peminjaman $peminjaman)
{
    abort_unless(
        in_array($peminjaman->status, ['pending', 'dipinjam', 'menunggu_konfirmasi_kembali','menunggu_spv']),
        404
    );

    $peminjaman->load('details.barang', 'requestedBy');

    return view('spv.peminjaman.show1', compact('peminjaman'));
}


    public function approve(Peminjaman $peminjaman, Request $request)
{
    abort_unless($peminjaman->status === 'menunggu_spv' && auth()->user()->hasRole('admin'), 403);

    $validated = $request->validate([
        'catatan_approval' => ['nullable', 'string', 'max:500'],
    ]);

    DB::transaction(function () use ($peminjaman, $validated) {
        $details = $peminjaman->details()->with('barang')->get();

        // validasi ulang stok untuk semua item, pakai qty_pinjam langsung
        foreach ($details as $detail) {
            $barang = $detail->barang()->lockForUpdate()->first();

            if ($detail->qty_pinjam > $barang->stok_tersedia) {
                throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi (tersisa: {$barang->stok_tersedia})");
            }
        }

        // baru kurangi stok setelah semua item lolos validasi
        foreach ($details as $detail) {
            $detail->barang()->lockForUpdate()->first()->decrement('stok_tersedia', $detail->qty_pinjam);
        }

        $peminjaman->update([
            'status'            => 'dipinjam',
            'approved_by'       => auth()->id(),
            'approved_at'       => now(),
            'catatan_approval'  => $validated['catatan_approval'] ?? null,
        ]);
    });

    return redirect()->route('spv.peminjaman.index')
        ->with('success', "Peminjaman {$peminjaman->kode_peminjaman} disetujui, barang siap diambil");
}

    public function reject(Peminjaman $peminjaman, Request $request)
    {
        abort_unless($peminjaman->status === 'pending', 404);

        $validated = $request->validate([
            'catatan_approval' => ['required', 'string', 'max:500'],
        ]);

        $peminjaman->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return redirect()->route('spv.peminjaman.index')
            ->with('success', "Peminjaman {$peminjaman->kode_peminjaman} ditolak");
    }

    public function konfirmasiKembali(Peminjaman $peminjaman, Request $request)
    {
        abort_unless(in_array($peminjaman->status, ['dipinjam', 'menunggu_konfirmasi_kembali']), 404);

        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.detail_id' => ['required', 'exists:peminjaman_detail,id'],
            'items.*.kondisi_kembali' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang'],
            'items.*.catatan_kembali' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($peminjaman, $validated) {
            foreach ($validated['items'] as $item) {
                $detail = $peminjaman->details()->findOrFail($item['detail_id']);

                $detail->update([
                    'kondisi_kembali' => $item['kondisi_kembali'],
                    'catatan_kembali' => $item['catatan_kembali'] ?? null,
                ]);

                // hanya barang dengan kondisi baik/rusak_ringan yang kembali ke stok bisa dipakai
                if (in_array($item['kondisi_kembali'], ['baik', 'rusak_ringan'])) {
                    $detail->barang->increment('stok_tersedia', $detail->jumlah_disetujui);
                }
                // rusak_berat & hilang TIDAK dikembalikan ke stok_tersedia
                // (bisa dicatat lebih lanjut ke tabel penghapusan aset kalau dibutuhkan nanti)
            }

            $peminjaman->update([
                'status' => 'dikembalikan',
                'dikembalikan_at' => now(),
            ]);
        });

        return redirect()->route('spv.peminjaman.index')
            ->with('success', "Peminjaman {$peminjaman->kode_peminjaman} dikonfirmasi kembali");
    }
}
