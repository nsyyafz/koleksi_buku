<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Carbon\Carbon;

class PosController extends Controller
{
    public function indexAjax()
    {
        return view('pos.ajax');
    }

    public function indexAxios()
    {
        return view('pos.axios');
    }

    public function getBarang(Request $req)
    {
        $barang = Barang::where('id_barang', $req->kode_barang)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'code'    => 404,
                'message' => 'Barang tidak ditemukan',
                'data'    => null
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Barang ditemukan',
            'data'    => $barang
        ]);
    }

    public function bayar(Request $req)
    {
        $items = $req->items; // array of {id_barang, nama, harga, jumlah, subtotal}
        $total = $req->total;

        if (!$items || count($items) === 0) {
            return response()->json([
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Tidak ada item di keranjang',
                'data'    => null
            ], 400);
        }

        $penjualan = Penjualan::create([
            'timestamp' => Carbon::now(),
            'total'     => $total
        ]);

        foreach ($items as $item) {
            PenjualanDetail::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang'    => $item['id_barang'],
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $item['subtotal']
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Transaksi berhasil disimpan',
            'data'    => ['id_penjualan' => $penjualan->id_penjualan]
        ]);
    }
}