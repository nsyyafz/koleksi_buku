<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'vendor_id',
        'total_amount',
        'payment_status',
        'payment_method',
        'payment_token',
        'snap_token',
        'transaction_id',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relasi ke OrderDetail
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Generate order number otomatis
     * Format: ORDER-YYYYMMDD-XXXX
     */
    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now()->toDateString())
                         ->orderBy('id', 'desc')
                         ->first();
        
        $sequence = $lastOrder ? ((int) substr($lastOrder->order_number, -4)) + 1 : 1;
        
        return 'ORDER-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope untuk order dengan status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope untuk order yang sudah dibayar
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope untuk order pending
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Check apakah order sudah dibayar
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check apakah order masih pending
     */
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Format total amount ke Rupiah
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}