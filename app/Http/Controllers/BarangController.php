<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        // Data akan diambil via Ajax DataTables
        return view('barang.index');
    }
    
    public function create()
    {
        return view('barang.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|max:200',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable',
        ]);
        
        // ID otomatis dari trigger, jadi tidak perlu diisi
        Barang::create($request->all());
        
        return redirect()->route('barang.index')
                       ->with('success', 'Barang berhasil ditambahkan!');
    }
    
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }
    
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|max:200',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable',
        ]);
        
        $barang->update($request->all());
        
        return redirect()->route('barang.index')
                       ->with('success', 'Barang berhasil diupdate!');
    }
    
    public function destroy(Barang $barang)
    {
        $barang->delete();
        
        return redirect()->route('barang.index')
                       ->with('success', 'Barang berhasil dihapus!');
    }
    
    // Method untuk DataTables Ajax
    public function getData()
    {
        $barangs = Barang::orderBy('id_barang', 'asc')->get();
        
        return response()->json([
            'data' => $barangs
        ]);
    }

    /**
     * Cetak label barang
     */
    public function cetak(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
            'koordinat_x' => 'required|integer|min:1|max:5',
            'koordinat_y' => 'required|integer|min:1|max:8',
        ]);
        
        // Parse selected IDs
        $ids = explode(',', $request->selected_ids);
        
        // Ambil data barang yang dipilih
        $barangs = Barang::whereIn('id_barang', $ids)
                        ->orderBy('id_barang')
                        ->get();
        
        if ($barangs->isEmpty()) {
            return back()->with('error', 'Tidak ada barang yang dipilih!');
        }
        
        // Data untuk PDF
        $data = [
            'barangs' => $barangs,
            'koordinat_x' => $request->koordinat_x,
            'koordinat_y' => $request->koordinat_y,
            'total_labels' => $barangs->count(),
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('barang.label-pdf', $data);
        
        // Ukuran kertas A4
        $pdf->setPaper('A4', 'portrait');
        
        // Download
        return $pdf->download('label-harga-' . date('Y-m-d-His') . '.pdf');
    }
}