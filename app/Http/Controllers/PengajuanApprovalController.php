<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanApprovalController extends Controller
{
   public function index(Request $request)
    {

			$status = $request->query('status', 'pending'); // default kalau nggak ada filter

    $query = Pengajuan::with('requestedBy', 'approvedBy')->latest();

    if ($status !== 'semua') {
        $query->where('status', $status);
    } else {
        $query->where('status', '!=', 'draft'); // "semua" tetap exclude draft
    }

    $pengajuan = $query->get();

    // hitung jumlah per status, buat badge di dropdown (opsional tapi berguna)
    $counts = [
        'pending' => Pengajuan::where('status', 'pending')->count(),
        'approved' => Pengajuan::where('status', 'approved')->count(),
        'rejected' => Pengajuan::where('status', 'rejected')->count(),
    ];

        return view('spv.pengajuan.index', compact('pengajuan','query','status','counts'));
    }

    public function show(Pengajuan $pengajuan)
    {
        abort_unless($pengajuan->status === 'pending', 404);

        $pengajuan->load('details.barang', 'requestedBy');

        return view('spv.pengajuan.show', compact('pengajuan'));
    }

    public function approve(Pengajuan $pengajuan, Request $request)
    {
        abort_unless($pengajuan->status === 'pending', 404);

        $validated = $request->validate([
            'catatan_approval' => ['nullable', 'string', 'max:500'],
        ]);

        $pengajuan->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $validated['catatan_approval'] ?? null,
        ]);

        return redirect()
            ->route('spv.pengajuan.index')
            ->with('success', "Pengajuan {$pengajuan->kode_pengajuan} disetujui, siap diproses pembelian");
    }

    public function reject(Pengajuan $pengajuan, Request $request)
    {
        abort_unless($pengajuan->status === 'pending', 404);

        $validated = $request->validate([
            'catatan_approval' => ['required', 'string', 'max:500'],
        ]);

        $pengajuan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return redirect()
            ->route('spv.pengajuan.index')
            ->with('success', "Pengajuan {$pengajuan->kode_pengajuan} ditolak");
    }
}
