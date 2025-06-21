# Konfigurasi Email untuk Sistem OTP

Untuk menggunakan sistem OTP melalui email, Anda perlu mengkonfigurasi pengaturan email di file `.env` Anda. Berikut adalah dua opsi yang dapat Anda gunakan:

## Opsi 1: Menggunakan Mailtrap (Disarankan untuk Development)

1. Buat akun di [Mailtrap](https://mailtrap.io/) jika Anda belum memilikinya
2. Buat inbox baru atau gunakan yang sudah ada
3. Pada dashboard inbox, klik "SMTP Settings" dan pilih "Laravel" dari dropdown
4. Salin kredensial yang ditampilkan ke file `.env` Anda:

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@eccomerce-online.com"
MAIL_FROM_NAME="Eccomerce Online"
```

## Opsi 2: Menggunakan Gmail

1. Pastikan Anda memiliki akun Gmail
2. Aktifkan "Less secure app access" atau buat "App Password" jika Anda menggunakan 2FA
3. Konfigurasi `.env` Anda dengan:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_gmail@gmail.com
MAIL_FROM_NAME="Eccomerce Online"
```

## Pengujian Konfigurasi Email

Setelah mengkonfigurasi email, Anda dapat mengujinya dengan perintah:

```
php artisan make:mail TestEmail
```

Kemudian edit file `app/Mail/TestEmail.php` dan lakukan pengujian dengan:

```
php artisan tinker
Mail::to('your_email@example.com')->send(new App\Mail\TestEmail());
```

## Mendebug Email

Jika email tidak terkirim:

1. Periksa log Laravel (`storage/logs/laravel.log`)
2. Pastikan kredensial SMTP Anda benar
3. Periksa firewall/pengaturan jaringan Anda
4. Untuk Gmail, pastikan pengaturan keamanan mengizinkan akses aplikasi kurang aman 