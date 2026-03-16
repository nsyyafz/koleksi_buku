<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;

class WilayahController extends Controller
{
    /**
     * Tampilkan halaman wilayah dengan jQuery AJAX
     */
    public function indexAjax()
    {
        $provinsi = Wilayah::getProvinsi();
        return view('wilayah.ajax', compact('provinsi'));
    }
    
    /**
     * Tampilkan halaman wilayah dengan Axios
     */
    public function indexAxios()
    {
        $provinsi = Wilayah::getProvinsi();
        return view('wilayah.axios', compact('provinsi'));
    }
    
    /**
     * API: Get Kota berdasarkan Provinsi Kode
     */
    public function getKota(Request $request)
    {
        $provinsiKode = $request->provinsi_kode;
        
        $kota = Wilayah::getKota($provinsiKode);
        
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Data kota berhasil diambil',
            'data' => $kota
        ]);
    }
    
    /**
     * API: Get Kecamatan berdasarkan Kota Kode
     */
    public function getKecamatan(Request $request)
    {
        $kotaKode = $request->kota_kode;
        
        $kecamatan = Wilayah::getKecamatan($kotaKode);
        
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Data kecamatan berhasil diambil',
            'data' => $kecamatan
        ]);
    }
    
    /**
     * API: Get Kelurahan berdasarkan Kecamatan Kode
     */
    public function getKelurahan(Request $request)
    {
        $kecamatanKode = $request->kecamatan_kode;
        
        $kelurahan = Wilayah::getKelurahan($kecamatanKode);
        
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Data kelurahan berhasil diambil',
            'data' => $kelurahan
        ]);
    }
}