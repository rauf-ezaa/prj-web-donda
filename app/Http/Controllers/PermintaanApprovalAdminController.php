<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use Illuminate\Http\Request;
use Alert;

class PermintaanApprovalAdminController extends Controller
{
    public function index(Request $request){

			$status_permintaan = $request->query('status_permintaan','pending');

			$query = Permintaan::with('requestedBy','approvedBy')->latest();

			if ($status_permintaan !== 'semua') {
        $query->where('status_permintaan', $status_permintaan);
    } else {
        $query->where('status_permintaan', '!=', 'draft'); // "semua" tetap exclude draft
    }

		$permintaan = $query->get();

		$counts = [
        'pending' => Permintaan::where('status_permintaan', 'pending')->count(),
        'approved' => Permintaan::where('status_permintaan', 'approved')->count(),
        'rejected' => Permintaan::where('status_permintaan', 'rejected')->count(),
    ];


				// dd($permintaan);

        return view('pages.admin.approval.approval-permintaan.index', compact('permintaan','status_permintaan','counts'));
		}

		public function show(Permintaan $permintaan){
			  abort_if($permintaan->status_pemintaan === 'draft' && $permintaan->request_by != auth()->id(), 403);

				abort_unless(
					$permintaan->status_pemintaan !== 'draft' || $permintaan->request_by == auth()->id(),
					403
			);
        $permintaan->load('details.barang', 'requestedBy');

      	return view('pages.admin.approval.approval-permintaan.show', compact('permintaan'));

		}

		public function approve(Permintaan $permintaan, Request $request){

			abort_unless($permintaan->status_permintaan === 'pending', 404);

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        $permintaan->update([
            'status_permintaan' => 'menunggu_spv',
            'verified_by_admin' => auth()->id(),
            'verified_at_admin' => now(),
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return redirect()
            ->route('admin.permintaan.index')
            ->with('success', "Permintaan {$permintaan->kode_permintaan} diteruskan ke SPV");
		}

		public function reject(Permintaan $permintaan, Request $request){
			abort_unless($permintaan->status_permintaan === 'pending', 404);

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ]);

        $permintaan->update([
            'status_permintaan' => 'rejected',
            'verified_by_admin' => auth()->id(),
            'verified_at_admin' => now(),
            'catatan_admin' => $validated['catatan_admin'],
        ]);

				Alert::success('Berhasil','Permintaan Berhasil Ditolak.');

        return redirect()
            ->route('admin.permintaan.index')
            ->with('success', "Permintaan {$permintaan->kode_permintaan} ditolak oleh admin");
		}
}
