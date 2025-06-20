<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone_number',
        'address_line',
        'city',
        'province',
        'postal_code',
        'notes',
        'is_default',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        // Sesuaikan 'App\Models\AppUser' jika nama model User Anda berbeda
        return $this->belongsTo(AppUser::class, 'user_id');
    }
}
