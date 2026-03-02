<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga</title>
    <style>
        @page {
            margin: 8mm 4mm; /* Top/Bottom, Left/Right margin */
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        
        /* Container grid 5x8 */
        .label-container {
            width: 100%;
            display: table;
        }
        
        /* Baris label (8 baris) */
        .label-row {
            display: table-row;
            height: 21mm; /* Tinggi per label */
        }
        
        /* Cell label (5 kolom) */
        .label-cell {
            display: table-cell;
            width: 38mm; /* Lebar per label */
            height: 21mm;
            padding: 2mm;
            vertical-align: middle;
            text-align: center;
            border: 1px dashed #ddd; /* Border untuk debug, bisa dihapus */
        }
        
        /* Label kosong (sebelum koordinat mulai) */
        .label-empty {
            /* Kosong, tidak ada konten */
        }
        
        /* Label berisi data */
        .label-content {
            border: 1px solid #333;
            height: 100%;
            display: table;
            width: 100%;
        }
        
        .label-inner {
            display: table-cell;
            vertical-align: middle;
            padding: 1mm;
        }
        
        .label-nama {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 1mm;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .label-harga {
            font-size: 11pt;
            font-weight: bold;
            color: #000;
        }
        
        .label-id {
            font-size: 6pt;
            color: #666;
            margin-top: 1mm;
        }
    </style>
</head>
<body>
    <div class="label-container">
        @php
            // Konfigurasi kertas label
            $cols = 5; // Jumlah kolom
            $rows = 8; // Jumlah baris
            $totalCells = $cols * $rows; // 40 cells
            
            // Koordinat mulai (dari input user)
            $startX = $koordinat_x; // 1-5
            $startY = $koordinat_y; // 1-8
            
            // Hitung posisi mulai (0-based index)
            // Formula: posisi = (Y-1) * cols + (X-1)
            // Contoh: X=3, Y=2 → posisi = (2-1)*5 + (3-1) = 5 + 2 = 7 (cell ke-8, karena 0-based)
            $startPosition = ($startY - 1) * $cols + ($startX - 1);
            
            // Index barang yang akan dicetak
            $barangIndex = 0;
            $totalBarangs = count($barangs);
        @endphp
        
        {{-- Loop 8 baris --}}
        @for($row = 0; $row < $rows; $row++)
            <div class="label-row">
                {{-- Loop 5 kolom --}}
                @for($col = 0; $col < $cols; $col++)
                    @php
                        // Posisi cell saat ini (0-based)
                        $currentPosition = $row * $cols + $col;
                        
                        // Cek apakah cell ini harus diisi atau kosong
                        $shouldFill = $currentPosition >= $startPosition && $barangIndex < $totalBarangs;
                    @endphp
                    
                    <div class="label-cell">
                        @if($shouldFill)
                            {{-- Cell berisi data barang --}}
                            @php
                                $barang = $barangs[$barangIndex];
                                $barangIndex++;
                            @endphp
                            
                            <div class="label-content">
                                <div class="label-inner">
                                    <div class="label-nama">
                                        {{ Str::limit($barang->nama_barang, 30) }}
                                    </div>
                                    <div class="label-harga">
                                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                    </div>
                                    <div class="label-id">
                                        {{ $barang->id_barang }}
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Cell kosong --}}
                            <div class="label-empty"></div>
                        @endif
                    </div>
                @endfor
            </div>
        @endfor
    </div>
</body>
</html>