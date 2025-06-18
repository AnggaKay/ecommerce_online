<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'otp',
        'expire_at',
        'verified',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expire_at' => 'datetime',
        'verified' => 'boolean',
    ];
    
    /**
     * Get the user that owns the OTP.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
