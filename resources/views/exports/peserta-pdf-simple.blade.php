<!-- resources/views/exports/peserta-pdf-simple.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Peserta - {{ $exportDate }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 9px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        .header h1 { 
            margin: 0; 
            color: #333;
            font-size: 16px;
        }
        .header p { 
            margin: 2px 0; 
            color: #666;
            font-size: 9px;
        }
        .summary {
            background: #28a745;
            color: white;
            padding: 6px;
            border-radius: 3px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 8px;
            font-size: 8px;
            table-layout: fixed;
        }
        .table th { 
            background-color: #343a40; 
            color: white;
            padding: 6px 3px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }
        .table td { 
            padding: 5px 3px;
            border: 1px solid #ddd;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 7px;
            color: #666;
            word-break: break-all;
        }
        .barcode-image {
            text-align: center;
            padding: 2px !important;
        }
        .barcode-image img {
            width: 70px;
            height: 70px;
            border: 1px solid #ddd;
            display: block;
            margin: 0 auto;
        }
        .no-barcode {
            text-align: center;
            color: #999;
            font-style: italic;
            font-size: 7px;
            padding: 10px 0;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            color: #666;
            font-size: 7px;
            border-top: 1px solid #ddd;
            padding-top: 6px;
        }
        .kelompok-A { background-color: #e3f2fd; }
        .kelompok-B { background-color: #e8f5e8; }
        .kelompok-C { background-color: #f3e5f5; }
        .text-center { text-align: center; }
        .text-small { font-size: 7px; }
        
        /* Pastikan kolom tidak terlalu sempit */
        .col-no { width: 4%; }
        .col-nama { width: 16%; }
        .col-jabatan { width: 12%; }
        .col-kelompok { width: 6%; }
        .col-kelas { width: 6%; }
        .col-kode { width: 18%; }
        .col-barcode { width: 12%; }
        .col-cara { width: 26%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA PESERTA DAN BARCODE</h1>
        <p>Sistem Absensi Barcode</p>
        <p>Tanggal Export: {{ $exportDate }}</p>
    </div>

    <div class="summary">
        TOTAL PESERTA: {{ $totalPeserta }} ORANG
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Lengkap</th>
                <th class="col-jabatan">Jabatan</th>
                <th class="col-kelompok">Kelompok</th>
                <th class="col-kelas">Kelas</th>
                <th class="col-kode">Kode Barcode</th>
                <th class="col-barcode">QR Code</th>
                <th class="col-cara">Cara Scan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peserta as $index => $item)
            <tr class="kelompok-{{ $item->kelompok }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->nama }}</strong></td>
                <td>{{ $item->jabatan ?? '-' }}</td>
                <td class="text-center">
                    @if($item->kelompok)
                        <strong>{{ $item->kelompok }}</strong>
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $item->kelas ?? '-' }}</td>
                <td class="barcode">{{ $item->barcode_data }}</td>
                <td class="barcode-image">
                    @if($item->barcode_image)
                        <img src="{{ $item->barcode_image }}" alt="QR Code">
                    @else
                        <div class="no-barcode">
                            QR Code tidak tersedia
                        </div>
                    @endif
                </td>
                <td class="text-small">
                    <strong>Scan dengan:</strong><br>
                    1. Buka Aplikasi Scanner<br>
                    2. Arahkan kamera ke QR Code<br>
                    3. Tunggu konfirmasi absensi<br>
                    <em>Kode: {{ $item->barcode_data }}</em>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} | 
        Total Data: {{ $peserta->count() }} peserta
    </div>
</body>
</html>