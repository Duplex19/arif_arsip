<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Arsip</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #4F46E5;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        /* ✅ Tambahan — kolom halaman center */
        .col-halaman {
            text-align: center;
            width: 60px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #999;
        }

        .page-number {
            text-align: right;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN ARSIP</h1>
        <p>Dinas Ketahanan Pangan Kabupaten Way Kanan</p>
        <p>Periode: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 120px;">Nomor Arsip</th>
                <th>Judul Arsip</th>
                <th style="width: 100px;">Tanggal Dilegalkan</th>
                <th style="width: 100px;">Jenis Arsip</th>
                <th style="width: 100px;">Bidang</th>
                <th class="col-halaman">Jml. Halaman</th> {{-- ✅ Tambahan --}}
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $item['no'] }}</td>
                    <td>{{ $item['nomor_arsip'] }}</td>
                    <td>{{ $item['judul'] }}</td>
                    <td>{{ $item['tgl_dilegalkan'] }}</td>
                    <td>{{ $item['jenis_arsip'] }}</td>
                    <td>{{ $item['bidang'] }}</td>
                    <td class="col-halaman"> {{-- ✅ Tambahan --}}
                        {{ $item['jumlah_halaman'] > 0 ? $item['jumlah_halaman'] : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data arsip.</td> {{-- colspan +1 --}}
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ date('d/m/Y H:i:s') }} | E-Arsip Dinas Ketahanan Pangan Kab. Way Kanan
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->getFont("DejaVu Sans");
                $pdf->text(270, 820, "Halaman $PAGE_NUM dari $PAGE_COUNT", $font, 8, array(0.6,0.6,0.6));
            ');
        }
    </script>
</body>

</html>
