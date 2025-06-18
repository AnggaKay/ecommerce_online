<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'is_featured',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'discount_price' => 'float',
        'stock' => 'integer',
        'weight' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'preparation_time' => 'integer',
        'expiry_date' => 'date',
        'requires_refrigeration' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the wishlists for the product.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Calculate the average rating of the product.
     */
    public function getAverageRatingAttribute()
    {
        if ($this->reviews->isEmpty()) {
            return 0;
        }
        
        return $this->reviews->avg('rating');
    }

    /**
     * Get the final price after discount.
     */
    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }
}