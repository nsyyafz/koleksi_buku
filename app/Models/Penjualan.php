<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'penjualan';        // ← ini yang kurang!
    protected $primaryKey = 'id_penjualan';
    public $timestamps = false;

    protected $fillable = ['timestamp', 'total'];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan');
    }
}