<?php

namespace App\Http\Controllers;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;

class DetailPdfController extends Controller
{
    // tambahkan di PeminjamanController

public function cetakDetailPeminjaman(Peminjaman $peminjaman)
{
    abort_unless(
        $peminjaman->requested_by === auth()->id() || auth()->user()->hasAnyRole(['admin', 'spv']),
        403
    );

    $peminjaman->load('items.barang', 'requestedBy', 'approvedBy');

    $pdf = Pdf::loadView('peminjaman.pdf.detail', compact('peminjaman'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream("Peminjaman-{$peminjaman->kode_peminjaman}.pdf");
}
}
