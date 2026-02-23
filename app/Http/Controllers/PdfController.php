<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Halaman pilih PDF
     */
    public function index()
    {
        return view('pdf.index');
    }
    
    /**
     * Generate Sertifikat (Landscape)
     */
    public function generateSertifikat()
    {
        $data = [
            'nama' => auth()->user()->name,
            'tanggal' => now()->format('d F Y'),
            'nomor' => 'CERT-' . strtoupper(uniqid()),
        ];
        
        $pdf = Pdf::loadView('pdf.sertifikat', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('sertifikat-' . auth()->user()->name . '.pdf');
    }
    
    /**
     * Generate Undangan (Portrait dengan Header)
     */
    public function generateUndangan()
    {
        $data = [
            'nama' => auth()->user()->name,
            'tanggal' => now()->addDays(7)->format('d F Y'),
            'waktu' => '09:00 WIB',
            'tempat' => 'Aula Fakultas Teknik',
            'acara' => 'Seminar Nasional Teknologi Informasi',
        ];
        
        $pdf = Pdf::loadView('pdf.undangan', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('undangan-' . auth()->user()->name . '.pdf');
    }
}