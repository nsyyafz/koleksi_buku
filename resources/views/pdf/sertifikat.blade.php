<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        .container {
            width: 100%;
            height: 100vh;
            display: table;
            text-align: center;
            position: relative;
        }
        .content {
            display: table-cell;
            vertical-align: middle;
            padding: 50px;
        }
        .border {
            border: 15px solid #fff;
            padding: 40px;
            margin: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
        }
        h1 {
            font-size: 60px;
            margin: 20px 0;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        h2 {
            font-size: 24px;
            margin: 10px 0;
            color: #666;
        }
        .nama {
            font-size: 40px;
            font-weight: bold;
            color: #764ba2;
            margin: 30px 0;
            text-decoration: underline;
        }
        .deskripsi {
            font-size: 18px;
            margin: 20px 0;
            line-height: 1.6;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .signature {
            text-align: center;
            flex: 1;
        }
        .signature-line {
            width: 200px;
            border-top: 2px solid #333;
            margin: 50px auto 10px;
        }
        .tanggal {
            text-align: left;
            flex: 1;
        }
        .nomor {
            position: absolute;
            top: 30px;
            left: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nomor">No: {{ $nomor }}</div>
        <div class="content">
            <div class="border">
                <h1>SERTIFIKAT</h1>
                <h2>Certificate of Achievement</h2>
                
                <div class="deskripsi">
                    Diberikan kepada:
                </div>
                
                <div class="nama">{{ $nama }}</div>
                
                <div class="deskripsi">
                    Atas partisipasinya dalam menyelesaikan<br>
                    <strong>Modul 2: Login Google dan HTML to PDF Generator</strong><br>
                    pada Praktikum Pemrograman Web 2
                </div>
                
                <div class="footer">
                    <div class="tanggal">
                        Tanggal: {{ $tanggal }}
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <strong>Dosen Pengampu</strong><br>
                        NIP. 123456789
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>