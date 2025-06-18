# Email Configuration for OTP System

To enable the OTP verification via Gmail, you need to configure your `.env` file with the proper SMTP settings.

## Step 1: Create or Update Your .env File

Make sure your `.env` file contains the following email configuration:

```
# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="Your App Name"
```

## Step 2: Generate an App Password for Gmail

Since Gmail has enhanced security, you'll need to create an App Password:

1. Go to your Google Account settings (https://myaccount.google.com/)
2. Select "Security" from the left menu
3. Under "Signing in to Google," select "2-Step Verification" (enable it if not already enabled)
4. At the bottom of the page, select "App passwords"
5. Select "Mail" as the app and "Other" as the device (name it "Laravel App")
6. Click "Generate"
7. Copy the 16-character password that appears
8. Paste this password in your `.env` file as the value for `MAIL_PASSWORD`

## Step 3: Update Your Email Configuration

Replace the following values in your `.env` file:

- `your-gmail@gmail.com` with your actual Gmail address
- `your-app-password` with the app password you generated
- `"Your App Name"` with your actual application name

## Step 4: Clear Configuration Cache

After updating your `.env` file, run the following command to clear the configuration cache:

```
php artisan config:clear
```

## Step 5: Test Email Sending

You can test if your email configuration is working by using Laravel's built-in tinker:

```
php artisan tinker
```

Then run:

```php
Mail::raw('Test email from Laravel', function($message) { $message->to('your-test-email@example.com')->subject('Test Email'); });
```

Replace `your-test-email@example.com` with an email address where you want to receive the test email.

## Troubleshooting

If you encounter issues:

1. Make sure your Gmail account has "Less secure app access" enabled or you're using an App Password
2. Check if your firewall or antivirus is blocking the SMTP connection
3. Verify that your Gmail account doesn't have any restrictions
4. Try using port 465 with SSL encryption instead of port 587 with TLS 