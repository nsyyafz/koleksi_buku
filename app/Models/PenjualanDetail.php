<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'penjualan_detail';  // ← ini yang kurang!
    protected $primaryKey = 'idpenjualan_detail';
    public $timestamps = false;

    protected $fillable = ['id_penjualan', 'id_barang', 'jumlah', 'subtotal'];
}