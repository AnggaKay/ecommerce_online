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
     *  Accessor untuk menghitung subtotal
     */
    public function getSubtotalAttribute()
    {
        // Menjumlahkan (kuantitas * harga) untuk setiap item
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     *  Accessor untuk menghitung total item
     */
    public function getTotalItemsAttribute()
    {
        // Menjumlahkan semua kuantitas item
        return $this->cartItems->sum('quantity');
    }
}