<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga</title>
    <style>
        @page { size: 210mm 163mm; margin: 0; }
        body { margin:0; font-family: Arial, sans-serif; background-color: #fff951; }

        table.label-sheet {
            border-spacing: 3mm 2mm;
            table-layout: fixed;
            width: 210mm;
            height: 163mm;
        }
        table.label-sheet td {
            width: 38mm;
            height: 18mm;
            padding: 1px 0 0 0;
            text-align: center;
            vertical-align: middle;
            background-color: #fff;
        }
        .label-barcode img {
            width: 34mm;
            height: 8mm;
            display: block;
            margin: 0 auto;
        }
        .label-nama  { font-size: 5pt;   font-weight: bold; line-height: 1.1; }
        .label-harga { font-size: 6.5pt; font-weight: bold; }
        .label-id    { font-size: 4pt;   color: #888; }
    </style>
</head>
<body>
@php
    $cols          = 5;
    $rows          = 8;
    $startPosition = ($koordinat_y - 1) * $cols + ($koordinat_x - 1);
    $barangIndex   = 0;
    $totalBarangs  = count($barangs);
@endphp

<table class="label-sheet">
    <colgroup>
        <col style="width:38mm">
        <col style="width:38mm">
        <col style="width:38mm">
        <col style="width:38mm">
        <col style="width:38mm">
    </colgroup>
    @for ($row = 0; $row < $rows; $row++)
    <tr style="height:18mm">
        @for ($col = 0; $col < $cols; $col++)
        @php
            $i          = $row * $cols + $col;
            $shouldFill = $i >= $startPosition && $barangIndex < $totalBarangs;
        @endphp
        <td>
            @if ($shouldFill)
                @php $b = $barangs[$barangIndex]; $barangIndex++; @endphp

                {{-- Barcode di atas id_barang --}}
                <div class="label-barcode">
                    <img src="{{ $barcodes[$b->id_barang] }}" alt="{{ $b->id_barang }}">
                </div>

                <div class="label-id">{{ $b->id_barang }}</div>
                <div class="label-nama">{{ Str::limit($b->nama_barang, 25) }}</div>
                <div class="label-harga">Rp {{ number_format($b->harga, 0, ',', '.') }}</div>
            @endif
        </td>
        @endfor
    </tr>
    @endfor
</table>
</body>
</html>