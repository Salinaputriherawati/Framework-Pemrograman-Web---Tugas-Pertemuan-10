<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Stok Produk</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 30px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .header img {
            height: 50px;
        }

        .company-info {
            text-align: center;
            font-size: 13px;
        }

        h1 {
            text-align: center;
            margin: 0;
            font-size: 27px;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        h3 {
            text-align: center;
            color: darkred;
            margin-top: 5px;
            margin-bottom: 0;
        }

        .periode {
            text-align: center;
            margin-top: 5px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .footer-note {
            font-size: 11px;
            margin-top: 10px;
            font-style: italic;
        }

        .signature {
            margin-top: 40px;
            width: 100%;
            text-align: left;
            font-size: 13px;
        }

        .signature div {
            display: inline-block;
            width: 30%;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <!-- <div>
            <img src="{{ public_path('images/logo.png') }}" alt="Logo"> 
        </div> -->
        <div class="company-info">
            <h1>PT. MILKO</h1>
            <h2>Rekap Mutasi Stok Bulanan</h2>
            <h3>Periode : {{ $startDate }} s/d {{ $endDate }}</h3>
        </div>
    </div>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Unit</th>
                <th>Tipe</th>
                <th>Qty</th>
                <th>Produsen</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->unit }}</td>
                <td>{{ $product->type }}</td>
                <td>{{ $product->qty }}</td>
                <td>{{ $product->producer }}</td>
                <td>{{ $product->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Catatan kaki -->
    <p class="footer-note">
        Data ini bersifat rahasia. Dicetak pada: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
    </p>

    <!-- Tanda tangan -->
    <div class="signature">
        <div>Diketahui<br><br><br><br>
            <br>Kepala Logistik<br> Salina Putri Herawati
        </div>
    </div>
</body>

</html>