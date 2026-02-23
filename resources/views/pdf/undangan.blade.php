<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
        }
        .header .subtitle {
            margin-top: 5px;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .invitation-box {
            background-color: #f8f9fa;
            border-left: 5px solid #667eea;
            padding: 20px;
            margin: 30px 0;
        }
        .invitation-box h2 {
            color: #667eea;
            margin-top: 0;
        }
        .details {
            margin: 20px 0;
        }
        .detail-item {
            display: flex;
            margin: 10px 0;
        }
        .detail-label {
            font-weight: bold;
            width: 100px;
            color: #666;
        }
        .detail-value {
            flex: 1;
        }
        .footer-note {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-line {
            width: 200px;
            border-top: 2px solid #333;
            margin: 50px 0 10px auto;
        }
    </style>
</head>
<body>
    <!-- Header dengan Logo/Kop Surat -->
    <div class="header">
        <h1>FAKULTAS TEKNIK</h1>
        <div class="subtitle">UNIVERSITAS XXXXX</div>
        <div class="subtitle">Jl. Contoh No. 123, Kota, Indonesia</div>
    </div>
    
    <!-- Content -->
    <div class="content">
        <div class="greeting">
            Kepada Yth.<br>
            <strong>{{ $nama }}</strong><br>
            Di Tempat
        </div>
        
        <p style="text-align: justify; line-height: 1.8;">
            Dengan hormat,<br>
            Dalam rangka meningkatkan kompetensi mahasiswa di bidang teknologi informasi, 
            kami mengundang Bapak/Ibu untuk menghadiri acara:
        </p>
        
        <div class="invitation-box">
            <h2>{{ $acara }}</h2>
            
            <div class="details">
                <div class="detail-item">
                    <div class="detail-label">Hari/Tanggal</div>
                    <div class="detail-value">: {{ $tanggal }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Waktu</div>
                    <div class="detail-value">: {{ $waktu }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tempat</div>
                    <div class="detail-value">: {{ $tempat }}</div>
                </div>
            </div>
        </div>
        
        <p style="text-align: justify; line-height: 1.8;">
            Demikian undangan ini kami sampaikan. Atas perhatian dan kehadirannya, 
            kami ucapkan terima kasih.
        </p>
        
        <div class="signature">
            <p>Hormat kami,</p>
            <div class="signature-line"></div>
            <strong>Dekan Fakultas Teknik</strong><br>
            NIP. 987654321
        </div>
        
        <div class="footer-note">
            <strong>Catatan:</strong> Undangan ini bersifat resmi dan tidak dapat dipindahtangankan.
        </div>
    </div>
</body>
</html>