<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategori Item - {{ $data->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin-bottom: 50px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .items-table th, .items-table td {
            border: 1px solid #777;
            padding: 6px 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Data Master Kategori Item</h2>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 15%;"><strong>Kode Kategori</strong></td>
            <td style="width: 3%;">:</td>
            <td>{{ $data->kode }}</td>
        </tr>
        <tr>
            <td><strong>Nama Kategori</strong></td>
            <td>:</td>
            <td>{{ $data->nama }}</td>
        </tr>
    </table>

    <h4>Daftar Item pada Kategori Ini</h4>
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Jenis</th>
                <th>Supplier</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-center">Laba</th>
                <th class="text-right">Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data->masterItems as $index => $item)
            @php
                $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->supplier }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->laba }}%</td>
                <td class="text-right">Rp {{ number_format($harga_jual, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada item dalam kategori ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada tanggal dan waktu: {{ $waktu_cetak }} WIB
    </div>

</body>
</html>

