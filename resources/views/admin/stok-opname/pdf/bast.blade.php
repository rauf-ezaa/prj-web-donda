<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BAST {{ $stokOpname->no_bast }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.6;
        }
        .kop {
            text-align: center;
            border-bottom: 3px double #1f2937;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        .kop h1 {
            font-size: 15px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }
        .kop p {
            font-size: 10px;
            color: #4b5563;
            margin: 0;
        }
        .judul {
            text-align: center;
            margin-bottom: 24px;
        }
        .judul h2 {
            font-size: 14px;
            text-decoration: underline;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }
        .judul p {
            font-size: 11px;
            margin: 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 16px;
            font-size: 11px;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 150px;
        }
        .isi {
            text-align: justify;
            margin-bottom: 20px;
            font-size: 11px;
        }
        table.barang {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.barang th, table.barang td {
            border: 1px solid #1f2937;
            padding: 6px 8px;
            font-size: 10px;
        }
        table.barang th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }
        table.barang td.center { text-align: center; }
        .rekap {
            margin-bottom: 24px;
            font-size: 10px;
        }
        .rekap-item {
            display: inline-block;
            margin-right: 20px;
        }
        .penutup {
            font-size: 11px;
            margin-bottom: 40px;
            text-align: justify;
        }
        .ttd {
            width: 100%;
            display: table;
        }
        .ttd-kolom {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 11px;
        }
        .ttd-space {
            height: 70px;
        }
        .selisih-negatif { color: #dc2626; font-weight: bold; }
        .selisih-positif { color: #16a34a; font-weight: bold; }
    </style>
</head>
<body>

    <div class="kop">
        <h1>Sistem Inventaris Sekolah</h1>
        <p>Jl. Contoh Alamat Sekolah No. 123, Kota, Provinsi</p>
    </div>

    <div class="judul">
        <h2>Berita Acara Serah Terima</h2>
        <p>Hasil Stok Opname Barang Inventaris</p>
        <p>Nomor: {{ $stokOpname->no_bast }}</p>
    </div>

    <div class="isi">
        Pada hari ini, {{ $stokOpname->tanggal_bast->translatedFormat('l') }}, tanggal
        {{ $stokOpname->tanggal_bast->translatedFormat('d F Y') }}, telah dilaksanakan kegiatan
        pemeriksaan dan pencocokan stok fisik barang inventaris untuk periode
        <strong>{{ $stokOpname->periode->nama }}</strong>, dengan hasil sebagaimana tercantum
        pada tabel di bawah ini.
    </div>

    <table class="info-table">
        <tr>
            <td>Periode</td>
            <td>: {{ $stokOpname->periode->nama }}</td>
        </tr>
        <tr>
            <td>Diperiksa oleh (Admin)</td>
            <td>: {{ $stokOpname->dibuatOleh->nama_karyawan ?? $stokOpname->dibuatOleh->name }}</td>
        </tr>
        <tr>
            <td>Disahkan oleh (Supervisor)</td>
            <td>: {{ $stokOpname->diverifikasiOleh->nama_karyawan ?? $stokOpname->diverifikasiOleh->name }}</td>
        </tr>
        <tr>
            <td>Tanggal Disahkan</td>
            <td>: {{ $stokOpname->diverifikasi_at->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

    <table class="barang">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Barang</th>
                <th style="width: 70px;">Stok Sistem</th>
                <th style="width: 70px;">Stok Fisik</th>
                <th style="width: 60px;">Selisih</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokOpname->items as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $item->barang->nama_barang }} ({{ $item->barang->satuan }})</td>
                    <td class="center">{{ $item->stok_sistem }}</td>
                    <td class="center">{{ $item->stok_fisik }}</td>
                    <td class="center {{ $item->selisih < 0 ? 'selisih-negatif' : ($item->selisih > 0 ? 'selisih-positif' : '') }}">
                        {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }}
                    </td>
                    <td>{{ $item->keterangan ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rekap">
        <div class="rekap-item"><strong>Total Barang Diperiksa:</strong> {{ $stokOpname->items->count() }}</div>
        <div class="rekap-item"><strong>Barang Sesuai:</strong> {{ $stokOpname->items->where('selisih', 0)->count() }}</div>
        <div class="rekap-item"><strong>Barang Selisih:</strong> {{ $stokOpname->items->where('selisih', '!=', 0)->count() }}</div>
    </div>

    @if($stokOpname->catatan)
        <div class="isi">
            <strong>Catatan:</strong> {{ $stokOpname->catatan }}
        </div>
    @endif

    <div class="penutup">
        Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya untuk dapat dipergunakan
        sebagaimana mestinya. Hasil opname ini telah disesuaikan ke dalam sistem inventaris pada
        tanggal {{ $stokOpname->diverifikasi_at->translatedFormat('d F Y') }}.
    </div>

    <div class="ttd">
        <div class="ttd-kolom">
            <p>Diperiksa oleh,</p>
            <div class="ttd-space"></div>
            <p><strong>{{ $stokOpname->dibuatOleh->nama_karyawan ?? $stokOpname->dibuatOleh->name }}</strong></p>
            <p>Admin</p>
        </div>
        <div class="ttd-kolom">
            <p>Disahkan oleh,</p>
            <div class="ttd-space"></div>
            <p><strong>{{ $stokOpname->diverifikasiOleh->nama_karyawan ?? $stokOpname->diverifikasiOleh->name }}</strong></p>
            <p>Supervisor</p>
        </div>
    </div>

</body>
</html>
