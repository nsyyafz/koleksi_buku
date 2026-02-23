<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #333;
            margin-top: 0;
        }
        .otp-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ $details['title'] }}</h1>
        </div>
        
        <div class="email-body">
            <h2>Halo!</h2>
            <p>{{ $details['body'] }}</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $details['otp'] }}</div>
            </div>
            
            <div class="note">
                <strong>⚠️ Penting:</strong><br>
                • Kode OTP ini berlaku selama 10 menit<br>
                • Jangan bagikan kode ini kepada siapapun<br>
                • Jika Anda tidak melakukan login, abaikan email ini
            </div>
            
            <p>Terima kasih,<br><strong>Tim Koleksi Buku</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sistem Koleksi Buku. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas.</p>
        </div>
    </div>
</body>
</html>