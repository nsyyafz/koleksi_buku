<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false; // Karena ID custom (bukan auto-increment)
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_barang',
        'nama_barang',
        'harga',
        'stok',
        'deskripsi',
    ];
    
    // Cast untuk tipe data
    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];
    
    // Accessor untuk format harga
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}