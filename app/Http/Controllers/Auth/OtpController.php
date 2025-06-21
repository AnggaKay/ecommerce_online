<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    protected $otpService;
    
    /**
     * Create a new controller instance.
     *
     * @param OtpService $otpService
     * @return void
     */
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    
    /**
     * Show the OTP verification form.
     *
     * @return \Illuminate\View\View
     */
    public function showOtpForm()
    {
        if (!Session::has('auth_user')) {
            return redirect()->route('login');
        }
        
        $isRegistration = Session::has('register_verification');
        
        return view('auth.otp', compact('isRegistration'));
    }
    
    /**
     * Verify the OTP entered by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);
        
        if (!Session::has('auth_user')) {
            return redirect()->route('login');
        }
        
        $user = Session::get('auth_user');
        $isRegistration = Session::has('register_verification');
        
        if ($this->otpService->verify($user, $request->otp)) {
            // OTP is valid
            
            // If this was registration verification
            if ($isRegistration) {
                // Clear the session data
                Session::forget(['auth_user', 'register_verification']);
                
                // Redirect to login
                return redirect()->route('login')
                    ->with('status', 'Email verified successfully! You can now log in with your credentials.');
            } else {
                // This was login verification
                // Log the user in
            Auth::login($user, Session::get('remember_me', false));
            
            // Clear the session data
            Session::forget(['auth_user', 'remember_me']);
            
            return redirect()->intended('/');
            }
        }
        
        return back()->withErrors([
            'otp' => 'The OTP code is invalid or has expired.',
        ]);
    }
    
    /**
     * Resend OTP to the user's email.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendOtp()
    {
        if (!Session::has('auth_user')) {
            return redirect()->route('login');
        }
        
        $user = Session::get('auth_user');
        $otp = $this->otpService->generate($user);
        $this->otpService->sendOtpEmail($user, $otp);
        
        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}
