<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $modulInfo['label'] }} - {{ $row->{$kolomKode} ?? "#{$row->id}" }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1f2937; }
        .kop { text-align: center; border-bottom: 2px solid #1f2937; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h1 { font-size: 15px; margin: 0; text-transform: uppercase; }
        .judul { text-align: center; margin-bottom: 20px; }
        .judul h2 { font-size: 13px; text-decoration: underline; margin: 0 0 2px 0; text-transform: uppercase; }
        table.info { width: 100%; margin-bottom: 16px; font-size: 11px; }
        table.info td { padding: 2px 0; vertical-align: top; }
        table.info td:first-child { width: 160px; }
        table.barang { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.barang th, table.barang td { border: 1px solid #1f2937; padding: 6px 8px; font-size: 10px; }
        table.barang th { background: #f3f4f6; text-align: center; }
        .center { text-align: center; }
        .status-badge { display: inline-block; padding: 3px 10px; border: 1px solid #1f2937; font-size: 10px; }
    </style>
</head>
<body>
    <div class="kop"><h1>Sistem Inventaris Sekolah</h1></div>
    <div class="judul">
        <h2>Detail Transaksi {{ $modulInfo['label'] }}</h2>
        <p>{{ $row->{$kolomKode} ?? "#{$row->id}" }}</p>
    </div>

    <table class="info">
        <tr><td>Status</td><td>: <span class="status-badge">{{ str_replace('_', ' ', ucfirst($row->status ?? $row->status_permintaan ?? $row->status_pemintaan ?? '-')) }}</span></td></tr>
        <tr><td>Tanggal Dibuat</td><td>: {{ $row->created_at->translatedFormat('d F Y, H:i') }}</td></tr>

        {{-- info tambahan spesifik per modul, ditampilkan kalau kolomnya ada --}}
        @if(isset($row->keperluan))
            <tr><td>Keperluan</td><td>: {{ $row->keperluan }}</td></tr>
        @endif
        @if(isset($row->nama_supplier))
            <tr><td>Supplier</td><td>: {{ $row->nama_supplier }}</td></tr>
        @endif
        @if(isset($row->catatan) && $row->catatan)
            <tr><td>Catatan</td><td>: {{ $row->catatan }}</td></tr>
        @endif
    </table>

    <table class="barang">
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                @foreach($modulInfo['kolom_item'] as $kolom)
                    <th>{{ $kolom['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($items as $i => $item)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    @foreach($modulInfo['kolom_item'] as $kolom)
                        <td class="center">{{ $service->ambilNilaiField($item, $kolom['field']) }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($modulInfo['kolom_item']) + 1 }}" class="center">Tidak ada item.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
