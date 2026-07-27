<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{


public function __construct(
    private \App\Services\OpnameLockService $opnameLock
) {}

public function index(Request $request)
{

		$draftAktif = Peminjaman::where('requested_by', auth()->id())
        ->where('status', 'draft')
        ->latest()
        ->first();

    if (!$request->filled('sort')) {
        return view('peminjaman.index-peminjaman', [
            'peminjaman' => null,
            'draftAktif' => $draftAktif,
        ]);
    }

    // Kalau belum pilih sort, jangan query apa-apa
    if (!$request->filled('sort')) {
        return view('peminjaman.index-peminjaman',compact('draftAktif'), ['peminjaman' => null]);
    }

    $query = Peminjaman::with('requestedBy')
        ->where('requested_by', auth()->id());

    match ($request->sort) {
        'draft'     => $query->where('status', 'draft'),
        'pending'  => $query->whereIn('status', ['pending','menunggu_spv']),
        'approved' => $query->where('status', 'approved'),
        'rejected'   => $query->where('status', 'rejected'),
    };

    $peminjaman = $query->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('peminjaman.index-peminjaman', compact('peminjaman', 'draftAktif'));
    }

    public function startDraft()
    {
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => $this->generateKode(),
            'requested_by' => auth()->id(),
            'keperluan' => '',
            'tanggal_pinjam' => now(),
            'tanggal_wajib_kembali' => now()->addDays(3),
            'status' => 'draft',
        ]);

        return redirect()->route('peminjaman.draft', $peminjaman->id);
    }

    public function showDraft(Peminjaman $peminjaman)
    {
    	abort_unless( $peminjaman->requested_by == auth()->id(), 403);

				$dataBarang = Barang::select('id', 'nama_barang', 'stok_tersedia', 'klasifikasi_kib')->get();
        $peminjaman->load('details.barang');

        return view('peminjaman.draft-peminjaman', compact('peminjaman', 'dataBarang'));
    }

public function addItem(Peminjaman $peminjaman, Request $request)
{
    abort_unless(
        in_array($peminjaman->status, ['draft', 'pending']) && $peminjaman->requested_by === auth()->id(),
        403
    );

    $validated = $request->validate([
        'barang_id'  => 'required|exists:barangs,id',
        'qty_pinjam' => 'required|integer|min:1',
    ]);

    return DB::transaction(function () use ($peminjaman, $validated) {
        $barang = Barang::lockForUpdate()->findOrFail($validated['barang_id']);

        if ($validated['qty_pinjam'] > $barang->stok_tersedia) {
            return response()->json([
                'message' => "Jumlah melebihi stok tersedia (tersedia: {$barang->stok_tersedia})",
            ], 422);
        }

        $detail = $peminjaman->details()->updateOrCreate(
            ['barang_id' => $validated['barang_id']],
            ['qty_pinjam' => $validated['qty_pinjam']]
        );

        return response()->json([
            'detail' => [
                'id'          => $detail->id,
                'barang_id'   => $detail->barang_id,
                'nama_barang' => $barang->nama_barang,
                'qty_pinjam'  => $detail->qty_pinjam,
            ],
        ]);
    });
}

public function removeItem(Peminjaman $peminjaman, PeminjamanDetail $detail)
{
    abort_unless(
        in_array($peminjaman->status, ['draft', 'pending']) && $peminjaman->requested_by === auth()->id(),
        403
    );
    abort_unless($detail->peminjaman_id == $peminjaman->id, 404);

    $detail->delete();

    return response()->json(['success' => true]);
}

public function verifikasi(Peminjaman $peminjaman, Request $request)
{
	$this->opnameLock->assertNotLocked();

	abort_unless(
		in_array($peminjaman->status, ['draft', 'pending']) && $peminjaman->requested_by === auth()->id(),
		403
    );

    if ($peminjaman->details()->count() === 0) {
        return response()->json(['success' => false, 'message' => 'Belum ada barang yang dipilih'], 422);
    }

    $validated = $request->validate([
        'keperluan'             => 'required|string|max:500',
        'tanggal_pinjam'        => 'required|date|after_or_equal:today',
        'tanggal_wajib_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
    ]);

    return DB::transaction(function () use ($peminjaman, $validated) {

        // load ulang details + lock barang terkait, karena stok bisa saja
        // sudah berubah sejak staff pertama kali menambahkan barang ke draft
        $details = $peminjaman->details()->with('barang')->get();

        foreach ($details as $detail) {
            $barang = Barang::lockForUpdate()->find($detail->barang_id);

            if (!$barang || $detail->qty_pinjam > $barang->stok_tersedia) {
                $namaBarang = $barang->nama_barang ?? $detail->barang->nama_barang ?? 'barang';
                $stokSekarang = $barang->stok_tersedia ?? 0;

                return response()->json([
                    'success' => false,
                    'message' => "Stok {$namaBarang} tidak lagi mencukupi (tersisa: {$stokSekarang}). Silakan sesuaikan jumlah.",
                ], 422);
            }
        }

        $peminjaman->update([
            'keperluan'             => $validated['keperluan'],
            'tanggal_pinjam'        => $validated['tanggal_pinjam'],
            'tanggal_wajib_kembali' => $validated['tanggal_wajib_kembali'],
            'status'                => 'pending',
        ]);

        return response()->json([
            'success'  => true,
            'redirect' => route('peminjaman.show', $peminjaman->id),
        ]);
    });
}

    public function show(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->requested_by == auth()->id() || auth()->user()->hasAnyRole(['admin', 'spv']), 403);

        $peminjaman->load('details.barang', 'requestedBy', 'approvedBy');

        return view('peminjaman.show-peminjaman', compact('peminjaman'));
    }


		public function showKembalikan(Peminjaman $peminjaman)
{
    abort_unless($peminjaman->status === 'dipinjam' && $peminjaman->requested_by == auth()->id(), 403);

    $peminjaman->load('details.barang');

    return view('peminjaman.kembalikan', compact('peminjaman'));
}

public function prosesKembalikan(Peminjaman $peminjaman, Request $request)
{
    abort_unless($peminjaman->status === 'dipinjam' && $peminjaman->requested_by == auth()->id(), 403);

    $validated = $request->validate([
        'items' => ['required', 'array'],
        'items.*.detail_id' => ['required', 'exists:peminjaman_detail,id'],
        'items.*.kondisi_kembali' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang'],
        'items.*.catatan_kembali' => ['nullable', 'string', 'max:500'],
    ]);

    foreach ($validated['items'] as $item) {
        if ($item['kondisi_kembali'] !== 'baik' && empty($item['catatan_kembali'])) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan wajib diisi untuk barang dengan kondisi tidak baik',
            ], 422);
        }
    }

    foreach ($validated['items'] as $item) {
        $detail = $peminjaman->details()->findOrFail($item['detail_id']);
        $detail->update([
            'kondisi_kembali' => $item['kondisi_kembali'],
            'catatan_kembali' => $item['catatan_kembali'] ?? null,
        ]);
    }

    $peminjaman->update(['status' => 'menunggu_konfirmasi_kembali']);

    return redirect()
        ->route('peminjaman.show', $peminjaman->id)
        ->with('success', 'Pengembalian diajukan, menunggu konfirmasi SPV');
}

		public function batalkan(Peminjaman $peminjaman)
		{
				abort_unless($peminjaman->is_editable && $peminjaman->requested_by == auth()->id(), 403);
				$peminjaman->update(['status' => 'dibatalkan']);

				return redirect()
						->route('peminjaman.index')
						->with('success', 'peminjaman dibatalkan');
		}

    protected function generateKode(): string
    {
        $prefix = 'PJM-' . now()->format('Ymd') . '-';
        $lastNumber = Peminjaman::where('kode_peminjaman', 'like', $prefix . '%')->count();

        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
