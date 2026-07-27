<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanApprovalAdminController extends Controller
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
        'menunggu_spv' => Pengajuan::where('status', 'menunggu_spv')->count(),

    ];

						// dd($pengajuan);

        return view('pages.admin.approval.approval-pengajuan.index', compact('pengajuan','query','status','counts'));
    }

		 public function show(Pengajuan $pengajuan)
    {
        abort_if($pengajuan->status === 'draft' && $pengajuan->requested_by != auth()->id(), 403);

    	$pengajuan->load('details', 'requestedBy', 'approvedBy');

        return view('pages.admin.approval.approval-pengajuan.show', compact('pengajuan'));
    }

		public function approve(Pengajuan $pengajuan, Request $request)
    {
        abort_unless($pengajuan->status === 'pending', 404);

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        $pengajuan->update([
            'status' => 'menunggu_spv',
            'verified_by_admin' => auth()->id(),
            'verified_at_admin' => now(),
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', "Pengajuan {$pengajuan->kode_pengajuan} disetujui, siap diproses pembelian");
    }

		public function reject(Pengajuan $pengajuan, Request $request)
    {
        abort_unless($pengajuan->status === 'pending', 404);

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ]);

        $pengajuan->update([
            'status' => 'rejected',
            'verified_by_admin' => auth()->id(),
            'verified_at_admin' => now(),
            'catatan_approval' => $validated['catatan_admin'],
        ]);

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', "Pengajuan {$pengajuan->kode_pengajuan} ditolak oleh admin");
    }

}
