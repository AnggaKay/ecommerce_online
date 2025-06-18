<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Generate a new OTP for the user
     *
     * @param User $user
     * @return string
     */
    public function generate(User $user)
    {
        // Delete any existing OTPs for this user
        Otp::where('user_id', $user->id)->delete();
        
        // Generate a random 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Create a new OTP record
        Otp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expire_at' => Carbon::now()->addMinutes(10), // OTP expires in 10 minutes
            'verified' => false,
        ]);
        
        return $otp;
    }
    
    /**
     * Verify the OTP for a user
     *
     * @param User $user
     * @param string $otp
     * @return bool
     */
    public function verify(User $user, string $otp)
    {
        $otpRecord = Otp::where('user_id', $user->id)
            ->where('otp', $otp)
            ->where('verified', false)
            ->where('expire_at', '>', Carbon::now())
            ->first();
            
        if (!$otpRecord) {
            return false;
        }
        
        // Mark OTP as verified
        $otpRecord->update(['verified' => true]);
        
        return true;
    }
    
    /**
     * Send OTP to user's email
     *
     * @param User $user
     * @param string $otp
     * @return void
     */
    public function sendOtpEmail(User $user, string $otp)
    {
        $data = [
            'user' => $user,
            'otp' => $otp,
        ];
        
        Mail::send('emails.otp', $data, function($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Your Login OTP Code');
        });
    }
} 