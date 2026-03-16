<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = ['kode', 'nama'];
    protected $connection = 'pgsql_wilayah';  // Pakai koneksi database kedua
    
    /**
     * Get semua provinsi (kode 2 digit, tanpa titik)
     */
    public static function getProvinsi()
    {
        return self::whereRaw("kode ~ '^[0-9]{2}$'")
                   ->orderBy('nama')
                   ->get();
    }
    
    /**
     * Get kota berdasarkan provinsi (format: XX.XX)
     */
    public static function getKota($provinsiKode)
    {
        return self::whereRaw("kode ~ '^{$provinsiKode}\.[0-9]{2}$'")
                   ->orderBy('nama')
                   ->get();
    }
    
    /**
     * Get kecamatan berdasarkan kota (format: XX.XX.XX)
     */
    public static function getKecamatan($kotaKode)
    {
        $escaped = str_replace('.', '\.', $kotaKode);
        return self::whereRaw("kode ~ '^{$escaped}\.[0-9]{2}$'")
                   ->orderBy('nama')
                   ->get();
    }
    
    /**
     * Get kelurahan berdasarkan kecamatan (format: XX.XX.XX.XXXX)
     */
    public static function getKelurahan($kecamatanKode)
    {
        $escaped = str_replace('.', '\.', $kecamatanKode);
        return self::whereRaw("kode ~ '^{$escaped}\.[0-9]{4}$'")
                   ->orderBy('nama')
                   ->get();
    }
}