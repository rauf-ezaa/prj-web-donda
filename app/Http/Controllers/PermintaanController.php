<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\Barang;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
     public function index(Request $request)
{
	$hitung = [
						'pending' => Permintaan::whereIn('status_permintaan', ['pending'])->where('request_by',auth()->id())->count(),
						'approved' => Permintaan::where('status_permintaan', 'approved')->where('request_by',auth()->id())->count(),
						'rejected' => Permintaan::where('status_permintaan', 'rejected')->where('request_by',auth()->id())->count(),
						'draft' => Permintaan::where('status_permintaan', 'draft')->where('request_by',auth()->id())->count(),
				];
		$draftAktif = Permintaan::where('request_by', auth()->id())
        ->where('status_permintaan', 'draft')
        ->latest()
        ->first();

    if (!$request->filled('sort')) {
        return view('pages.admin.permintaan-barang.index-permintaan', [
            'permintaan' => null,
						'hitung' => $hitung,
            'draftAktif' => $draftAktif,
        ]);
    }

    // Kalau belum pilih sort, jangan query apa-apa
    if (!$request->filled('sort')) {
        return view('pages.admin.permintaan-barang.index-permintaan',compact('draftAktif','hitung'), ['pengajuan' => null]);
    }


    $query = Permintaan::with('requestedBy')
        ->where('request_by', auth()->id());

    match ($request->sort) {
        'draft'     => $query->where('status_permintaan', 'draft'),
        'pending'  => $query->where('status_permintaan', 'pending'),
        'approved' => $query->where('status_permintaan', 'approved'),
        'rejected'   => $query->where('status_permintaan', 'rejected'),
    };

    $permintaan = $query->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('pages.admin.permintaan-barang.index-permintaan', compact('permintaan', 'draftAktif','hitung'));
    }

    public function startDraft()
    {
        $permintaan = Permintaan::create([
            'kode_permintaan' => $this->generateKodePermintaan(),
            'request_by' => auth()->id(),
						'keperluan' => '',
            'status_permintaan' => 'draft',
        ]);

        return redirect()->route('permintaan.draft', $permintaan->id);
    }

    public function showDraft(Permintaan $permintaan)
    {
        abort_unless($permintaan->status_permintaan === 'draft' || $permintaan->status_permintaan === 'pending' && $permintaan->request_by === auth()->id(), 403);

        $dataBarang = Barang::whereHas('kib', fn ($q) => $q->where('kode_kib', '!=', 'KIB-B'))
											->select('id', 'nama_barang', 'stok_tersedia', 'klasifikasi_kib')
											->get();
				$permintaan->load('details.barang');

        return view('pages.admin.permintaan-barang.draft', compact('permintaan', 'dataBarang'));
    }

public function addItem(Permintaan $permintaan, Request $request)
{
    abort_unless(
        $permintaan->status_permintaan === 'draft' && $permintaan->request_by === auth()->id(),
        403
    );

    $validated = $request->validate([
        'barang_id'       => 'required|exists:barangs,id',
        'jumlah_diminta'  => 'required|integer|min:1',
    ]);

    $barang = Barang::with('kib')->findOrFail($validated['barang_id']);

    // titik validasi baru: KIB-B gak boleh diminta, cuma boleh dipinjam
    if ($barang->kib->kode_kib === 'KIB-B') {
        return response()->json([
            'message' => "{$barang->nama_barang} adalah aset tetap (KIB-B) dan tidak dapat diminta, hanya dapat dipinjam.",
        ], 422);
    }

    $detail = $permintaan->details()->updateOrCreate(
        ['barang_id' => $validated['barang_id']],
        ['jumlah_diminta' => $validated['jumlah_diminta']]
    );

    return response()->json([
        'detail' => [
            'id'              => $detail->id,
            'barang_id'       => $detail->barang_id,
            'nama_barang'     => $barang->nama_barang,
            'jumlah_diminta'  => $detail->jumlah_diminta,
        ],
    ]);
}

    public function removeItem(Permintaan $permintaan, PermintaanDetail $detail)
    {
        abort_unless($permintaan->status_permintaan === 'draft' || $permintaan->status_permintaan === 'pending' && $permintaan->request_by === auth()->id(), 403);
        abort_unless($detail->permintaan_id === $permintaan->id, 404);

        $detail->delete();

        return response()->json(['success' => true]);
    }

    public function verifikasi(Permintaan $permintaan, Request $request)
    {
        abort_unless($permintaan->status_permintaan === 'draft' || $permintaan->status_permintaan === 'pending' && $permintaan->request_by === auth()->id(), 403);

        if ($permintaan->details()->count() === 0) {
            return response()->json(['success' => false, 'message' => 'Belum ada barang yang dipilih'], 422);
        }

				  $validated = $request->validate([
            'keperluan' => 'required|string|max:500',
        ]);

        $permintaan->update([
					 'keperluan' => $validated['keperluan'],
            'status_permintaan' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('permintaan.show', $permintaan->id),
        ]);
    }

    public function show(Permintaan $permintaan)
    {
        abort_unless($permintaan->request_by === auth()->id() || auth()->user()->hasAnyRole(['admin', 'spv']), 403);

        $permintaan->load('details.barang', 'requestedBy', 'approvedBy');

        return view('pages.admin.permintaan-barang.show', compact('permintaan'));
    }


			public function batalkan(Permintaan $permintaan)
		{
				abort_unless($permintaan->is_editable && $permintaan->request_by == auth()->id(), 403);
				$permintaan->update(['status' => 'dibatalkan']);

				return redirect()
						->route('permintaan.index')
						->with('success', 'Permintaan dibatalkan');
		}

    protected function generateKodePermintaan(): string
    {
        $prefix = 'PMT-' . now()->format('Ymd') . '-';
        $lastNumber = Permintaan::where('kode_permintaan', 'like', $prefix . '%')->count();

        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
