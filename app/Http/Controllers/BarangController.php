<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    /**
     * Halaman index - tampil semua barang
     */
    public function index(Request $request)
    {
        $barang = Barang::when($request->search, function ($query, $search) {
                        $query->where('nama_barang', 'like', "%{$search}%")
                              ->orWhere('id_barang', 'like', "%{$search}%");
                    })
                    ->get();

        return view('barang.index', compact('barang'));
    }

    /**
     * Halaman cetak - pilih barang
     */
    public function cetakIndex()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('barang.cetak', compact('barang'));
    }

    /**
     * Halaman create
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:200',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Halaman edit
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update barang
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:200',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diupdate!');
    }

    /**
     * Hapus barang
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Generate PDF Label
     */
    public function cetak(Request $request)
    {
        $request->validate([
            'selected_ids'   => 'required|array|min:1',
            'selected_ids.*' => 'exists:barang,id_barang',
            'koordinat_x'    => 'required|integer|min:1|max:5',
            'koordinat_y'    => 'required|integer|min:1|max:8',
        ], [
            'selected_ids.required' => 'Pilih minimal 1 barang untuk dicetak!',
            'selected_ids.min'      => 'Pilih minimal 1 barang untuk dicetak!',
        ]);

        $barangs = Barang::whereIn('id_barang', $request->selected_ids)
                         ->orderBy('id_barang')
                         ->get();

        if ($barangs->isEmpty()) {
            return back()->with('error', 'Tidak ada barang yang dipilih!');
        }

        $data = [
            'barangs'     => $barangs,
            'koordinat_x' => $request->koordinat_x,
            'koordinat_y' => $request->koordinat_y,
        ];

        $mm  = 2.8346;
        $pdf = Pdf::loadView('barang.label-pdf', $data)
                  ->setPaper([0, 0, 210 * $mm, 165 * $mm], 'landscape');

        return $pdf->stream('label-harga-' . date('Y-m-d-His') . '.pdf');
    }
}