<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ping Başarısız Uyarısı</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #dc3545;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        .alert-box {
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .alert-box strong {
            display: inline-block;
            min-width: 80px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            padding: 15px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        ⚠️ Ping Başarısız
    </div>
    <div class="content">
        <p>Merhaba,</p>
        <p>Aşağıdaki IP adresine ping gönderilemedi. Lütfen gerekli kontrolleri yapınız:</p>
        <div class="alert-box">
            <strong>IP:</strong> {{ $ip }}<br>
            <strong>İsim:</strong> {{ $name ?? '-' }}<br>
            <strong>Zaman:</strong> {{ now()->format('d.m.Y H:i:s') }}
        </div>

        <p>İyi çalışmalar,<br><strong>{{ config('app.name') }}</strong></p>
    </div>
    <div class="footer">
        Bu e-posta sistem tarafından otomatik olarak gönderilmiştir. Lütfen yanıtlamayınız.
    </div>
</div>
</body>
</html>
