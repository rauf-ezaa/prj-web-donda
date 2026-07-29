<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $peminjaman->kode_peminjaman }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1f2937; }
        .kop { text-align: center; border-bottom: 2px solid #1f2937; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h1 { font-size: 15px; margin: 0; text-transform: uppercase; }
        .judul { text-align: center; margin-bottom: 20px; }
        .judul h2 { font-size: 13px; text-decoration: underline; margin: 0 0 2px 0; text-transform: uppercase; }
        table.info { width: 100%; margin-bottom: 16px; font-size: 11px; }
        table.info td { padding: 2px 0; }
        table.info td:first-child { width: 160px; }
        table.barang { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.barang th, table.barang td { border: 1px solid #1f2937; padding: 6px 8px; font-size: 10px; }
        table.barang th { background: #f3f4f6; text-align: center; }
        .center { text-align: center; }
        .status-badge { display: inline-block; padding: 3px 10px; border: 1px solid #1f2937; font-size: 10px; }
        .ttd { width: 100%; display: table; margin-top: 40px; }
        .ttd-kolom { display: table-cell; width: 50%; text-align: center; }
        .ttd-space { height: 70px; }
    </style>
</head>
<body>
    <div class="kop"><h1>Sistem Inventaris Sekolah</h1></div>
    <div class="judul">
        <h2>Detail Transaksi Peminjaman</h2>
        <p>{{ $peminjaman->kode_peminjaman }}</p>
    </div>

    <table class="info">
        <tr><td>Peminjam</td><td>: {{ $peminjaman->requestedBy->nama_karyawan ?? '-' }}</td></tr>
        <tr><td>Keperluan</td><td>: {{ $peminjaman->keperluan }}</td></tr>
        <tr><td>Tanggal Pinjam</td><td>: {{ $peminjaman->tanggal_pinjam->translatedFormat('d F Y') }}</td></tr>
        <tr><td>Wajib Kembali</td><td>: {{ $peminjaman->tanggal_wajib_kembali->translatedFormat('d F Y') }}</td></tr>
        <tr><td>Status</td><td>: <span class="status-badge">{{ str_replace('_', ' ', ucfirst($peminjaman->status)) }}</span></td></tr>
        @if($peminjaman->approvedBy)
            <tr><td>Disetujui Oleh</td><td>: {{ $peminjaman->approvedBy->nama_karyawan ?? $peminjaman->approvedBy->name }}</td></tr>
        @endif
    </table>

    <table class="barang">
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th>Barang</th>
                <th style="width:80px;">Qty Pinjam</th>
                <th style="width:70px;">Kembali Baik</th>
                <th style="width:70px;">Rusak Ringan</th>
                <th style="width:70px;">Rusak Berat</th>
                <th style="width:60px;">Hilang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->items as $i => $item)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td class="center">{{ $item->qty_pinjam }}</td>
                    <td class="center">{{ $item->qty_kembali_baik }}</td>
                    <td class="center">{{ $item->qty_kembali_rusak_ringan }}</td>
                    <td class="center">{{ $item->qty_kembali_rusak_berat }}</td>
                    <td class="center">{{ $item->qty_kembali_hilang }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd">
        <div class="ttd-kolom"><p>Peminjam,</p><div class="ttd-space"></div><p>{{ $peminjaman->requestedBy->nama_karyawan ?? '-' }}</p></div>
        <div class="ttd-kolom"><p>Mengetahui,</p><div class="ttd-space"></div><p>( _________________ )</p></div>
    </div>
</body>
</html>
