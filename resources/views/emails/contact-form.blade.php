<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Form Kontak</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h2 { color: #007bff; }
        .field { margin-bottom: 10px; }
        .field strong { display: inline-block; width: 80px; }
        .message { margin-top: 20px; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pesan Baru dari Website Anda</h2>
        <p>Anda telah menerima pesan baru melalui form kontak.</p>
        <hr>
        <div class="field">
            <strong>Nama:</strong> {{ $data['name'] }}
        </div>
        <div class="field">
            <strong>Email:</strong> {{ $data['email'] }}
        </div>
        <div class="field">
            <strong>Subjek:</strong> {{ $data['subject'] }}
        </div>
        <div class="message">
            <p><strong>Isi Pesan:</strong></p>
            <p>{{ $data['message'] }}</p>
        </div>
    </div>
</body>
</html>
