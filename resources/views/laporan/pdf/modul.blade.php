<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ $modulLabel }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1f2937;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 12px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 4px 0;
        }
        .header p {
            font-size: 10px;
            color: #6b7280;
            margin: 0;
        }
        .info-cetak {
            margin-bottom: 16px;
            font-size: 10px;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        .footer .kolom {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN {{ strtoupper($modulLabel) }}</h1>
        <p>Sistem Inventaris Sekolah</p>
    </div>

    <div class="info-cetak">
        <p>Dicetak oleh: {{ $dicetakOleh }}</p>
        <p>Tanggal cetak: {{ $dicetakPada->translatedFormat('d F Y, H:i') }} WIB</p>
        @if(!empty($filter['dari_tanggal']) || !empty($filter['sampai_tanggal']))
            <p>
                Periode:
                {{ !empty($filter['dari_tanggal']) ? \Carbon\Carbon::parse($filter['dari_tanggal'])->translatedFormat('d F Y') : 'Awal' }}
                s/d
                {{ !empty($filter['sampai_tanggal']) ? \Carbon\Carbon::parse($filter['sampai_tanggal'])->translatedFormat('d F Y') : 'Sekarang' }}
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Kode Transaksi</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->{$kolomKode} ?? "#{$row->id}" }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($row->status ?? $row->status_permintaan ?? $row->status_pemintaan ?? '-')) }}</td>
                    <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data untuk periode/filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 10px; font-size: 10px; color: #6b7280;">
        Total: {{ $data->count() }} transaksi
    </p>

    <div class="footer">
        <div class="kolom"></div>
        <div class="kolom">
            <p>Mengetahui,</p>
            <br><br><br>
            <p>( ___________________ )</p>
        </div>
    </div>

</body>
</html>
