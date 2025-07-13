<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * PERBAIKAN: Accessor untuk subtotal yang sudah dioptimalkan.
     */
    public function getSubtotalAttribute()
    {
        // Cek apakah relasi 'cartItems' sudah dimuat. Jika belum, muat sekarang.
        if (! $this->relationLoaded('cartItems')) {
            $this->load('cartItems');
        }

        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * PERBAIKAN: Accessor untuk total item yang sudah dioptimalkan.
     */
    public function getTotalItemsAttribute()
    {
        // Cek apakah relasi 'cartItems' sudah dimuat.
        if (! $this->relationLoaded('cartItems')) {
            $this->load('cartItems');
        }

        return $this->cartItems->sum('quantity');
    }

    /**
     * PERBAIKAN: Accessor untuk total berat yang sudah dioptimalkan.
     */
    public function getTotalWeightAttribute()
    {
        // Cek apakah relasi 'cartItems.product' sudah dimuat.
        if (! $this->relationLoaded('cartItems.product')) {
            $this->load('cartItems.product');
        }

        return $this->cartItems->sum(function ($item) {
            return ($item->product->weight ?? 0) * $item->quantity;
        });
    }
}
