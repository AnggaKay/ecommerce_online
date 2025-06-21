<!DOCTYPE html>
<html>
<head>
    <title>{{ isset($isRegistration) ? 'Account Verification' : 'Login Verification' }} Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            color: #FF6B35;
            margin: 20px 0;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ isset($isRegistration) ? 'Account Verification Code' : 'Login Verification Code' }}</h2>
        </div>
        
        <p>Hello {{ $user->name }},</p>
        
        @if(isset($isRegistration) && $isRegistration)
            <p>Thank you for registering! To complete your account setup, please use the following verification code:</p>
        @else
            <p>You are receiving this email because you requested to login to your account. Please use the following OTP code to verify your identity:</p>
        @endif
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p>This code will expire in 10 minutes.</p>
        
        <p>If you did not request this code, please ignore this email or contact support if you believe this is suspicious activity.</p>
        
        <p>Thank you,<br>
        {{ config('app.name') }} Team</p>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 