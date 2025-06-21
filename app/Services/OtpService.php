<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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
            'verified' => app()->environment('local'), // Auto-verify in local environment
        ]);

        // Log the OTP in development environment
        if (app()->environment('local')) {
            Log::info("OTP for user {$user->email}: {$otp}");
        }
        
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
        // Auto-verify in local environment
        if (app()->environment('local')) {
            return true;
        }
        
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
        // In local environment, just log the OTP instead of sending email
        if (app()->environment('local')) {
            Log::info("OTP email for {$user->email}: {$otp}");
            return;
        }
        
        $isRegistration = Session::has('register_verification');
        
        $data = [
            'user' => $user,
            'otp' => $otp,
            'isRegistration' => $isRegistration,
        ];
        
        $subject = $isRegistration ? 'Verify Your Account' : 'Your Login OTP Code';
        
        Mail::send('emails.otp', $data, function($message) use ($user, $subject) {
            $message->to($user->email, $user->name)
                    ->subject($subject);
        });
    }
} 