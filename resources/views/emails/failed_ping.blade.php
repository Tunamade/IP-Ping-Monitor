<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .panel {
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<h2>⚠️ Ping Başarısız</h2>
<p>Aşağıdaki IP adresine ping gönderilemedi:</p>
<div class="panel">
    <strong>IP:</strong> {{ $ip }}<br>
    <strong>Zaman:</strong> {{ now()->format('d.m.Y H:i:s') }}
</div>
<p>İyi çalışmalar,<br>{{ config('app.name') }}</p>
</body>
</html>
