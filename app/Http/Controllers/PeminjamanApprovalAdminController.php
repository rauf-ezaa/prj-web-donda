<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Alert;

class PeminjamanApprovalAdminController extends Controller
{
    public function index(Request $request)
    {
			$status = $request->query('status', 'pending'); // default kalau nggak ada filter

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
        'approved' => Peminjaman::where('status', 'approved')->count(),
        'rejected' => Peminjaman::where('status', 'rejected')->count(),
    ];

						// dd($peminjaman);

        return view('pages.admin.approval.approval-peminjaman.index', compact('peminjaman','query','status','counts'));
    }

    public function show(Peminjaman $peminjaman)
    {
       abort_if($peminjaman->status === 'draft' && $peminjaman->requested_by != auth()->id(), 403);

				abort_unless(
					$peminjaman->status !== 'draft' || $peminjaman->requested_by == auth()->id(),
					403
			);

        $peminjaman->load('details.barang', 'requestedBy');

        return view('pages.admin.approval.approval-peminjaman.show', compact('peminjaman'));
    }

    public function approve(Peminjaman $peminjaman, Request $request)
    {
        abort_unless($peminjaman->status === 'pending', 404);

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        $peminjaman->update([
            'status' => 'menunggu_spv',
            'verified_by_admin' => auth()->id(),
            'verified_at_admin' => now(),
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', "Peminjaman {$peminjaman->kode_peminjaman} diteruskan ke SPV");
    }


    public function reject(Peminjaman $peminjaman, Request $request)
    {
        abort_unless($peminjaman->status === 'pending', 404);

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ]);

        $peminjaman->update([
            'status' => 'rejected',
            'verified_by_admin' => auth()->id(),
            'verified_at_admin' => now(),
            'catatan_admin' => $validated['catatan_admin'],
        ]);

				Alert::sucess('Berhasil','Peminjaman Berhasil Ditolak');

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', "Peminjaman {$peminjaman->kode_peminjaman} ditolak oleh admin");
    }
}
