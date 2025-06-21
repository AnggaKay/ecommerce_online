<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'starts_at',
        'expires_at',
        'is_active',
        'usage_limit',
        'usage_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'float',
        'min_order_amount' => 'float',
        'max_discount_amount' => 'float',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
    ];

    /**
     * Determine if the coupon is valid.
     */
    public function isValid()
    {
        return $this->is_active &&
            (!$this->usage_limit || $this->usage_count < $this->usage_limit) &&
            (!$this->starts_at || $this->starts_at <= now()) &&
            (!$this->expires_at || $this->expires_at >= now());
    }

    /**
     * Calculate discount amount for a given total.
     */
    public function calculateDiscount($total)
    {
        if ($this->min_order_amount && $total < $this->min_order_amount) {
            return 0;
        }

        $discount = $this->type === 'percentage'
            ? $total * $this->value / 100
            : $this->value;

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }
} 