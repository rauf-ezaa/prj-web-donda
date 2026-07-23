<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanApprovalController extends Controller
{
    public function index(Request $request)
    {
			$status_permintaan = $request->query('status_permintaan','menunggu_spv');

			$query = Permintaan::with('requestedBy','approvedBy')->latest();

			if ($status_permintaan !== 'semua') {
        $query->where('status_permintaan', $status_permintaan);
    } else {
        $query->where('status_permintaan', '!=', 'draft'); // "semua" tetap exclude draft
    }

		$permintaan = $query->get();

		$counts = [
        'pending' => Permintaan::where('status_permintaan', 'pending')->count(),
        'menunggu_spv' => Permintaan::where('status_permintaan', 'menunggu_spv')->count(),
        'approved' => Permintaan::where('status_permintaan', 'approved')->count(),
        'rejected' => Permintaan::where('status_permintaan', 'rejected')->count(),
    ];

						// dd($riwayat);

        return view('pages.approval-permintaan.index-approval', compact('permintaan','status_permintaan','counts'));
    }

    public function show(Permintaan $permintaan)
    {
        abort_unless($permintaan->status_permintaan === 'menunggu_spv', 404);

        $permintaan->load('details.barang', 'requestedBy');

        return view('spv.permintaan.show', compact('permintaan'));
    }

    public function approve(Permintaan $permintaan, Request $request)
    {
        abort_unless($permintaan->status_permintaan === 'menunggu_spv', 404);

        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.detail_id' => ['required', 'exists:permintaan_details,id'],
            'items.*.jumlah_disetujui' => ['required', 'integer', 'min:0'],
            'catatan_approval' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($permintaan, $validated) {
            foreach ($validated['items'] as $item) {
                $detail = $permintaan->details()->findOrFail($item['detail_id']);

                if ($item['jumlah_disetujui'] > $detail->barang->stok_tersedia) {
                    throw new \Exception("Stok {$detail->barang->nama_barang} tidak mencukupi");
                }

                $detail->update(['jumlah_disetujui' => $item['jumlah_disetujui']]);

                if ($item['jumlah_disetujui'] > 0) {
                    $detail->barang->decrement('stok_tersedia', $item['jumlah_disetujui']);
                }
            }

            $permintaan->update([
                'status_permintaan' => 'approved',
                'approved_by' => auth()->id(),
                'approved_date' => now(),
                'kerperluan' => $validated['catatan_approval'] ?? null,
            ]);
        });

        return redirect()
            ->route('spv.permintaan.index')
            ->with('success', "Permintaan {$permintaan->kode_permintaan} berhasil disetujui");
    }

    public function reject(Permintaan $permintaan, Request $request)
    {
        abort_unless($permintaan->status === 'menunggu_spv', 404);

        $validated = $request->validate([
            'catatan_approval' => ['required', 'string', 'max:500'],
        ]);

        $permintaan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return redirect()
            ->route('spv.permintaan.index')
            ->with('success', "Permintaan {$permintaan->kode_permintaan} ditolak");
    }
}
