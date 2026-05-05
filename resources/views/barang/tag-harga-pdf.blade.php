<!DOCTYPE html>
<html>
<head>
    <title>Tag Harga - {{ $barang->nama_barang }}</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 20px; }
        .tag { border: 2px solid #000; padding: 20px; display: inline-block; }
        .barcode { margin: 20px 0; }
        .price { font-size: 24px; font-weight: bold; color: #d63031; }
        .name { font-size: 18px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="tag">
        <h2>{{ $barang->nama_barang }}</h2>
        
        <!-- Barcode -->
        <div class="barcode">
            <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
        </div>
        
        <!-- ID Barang -->
        <div>{{ $barang->id_barang }}</div>
        
        <!-- Harga -->
        <div class="price">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>
    </div>
</body>
</html>