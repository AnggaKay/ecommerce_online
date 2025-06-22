<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Ganti $fillable menjadi $guarded untuk kemudahan,
     * atau tambahkan semua kolom dari migrasi ke $fillable.
     * Menggunakan $guarded lebih mudah untuk saat ini.
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime', // Menggunakan 'paid_at' dari migrasi
        'payment_details' => 'array', // Cast JSON ke array
    ];

    /**
     * Get the order associated with the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}