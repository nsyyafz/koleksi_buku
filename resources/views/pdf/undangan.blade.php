<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Undangan Resmi - Fakultas Teknik</title>
  <style>
    @page {
      size: 210mm 297mm;
      margin: 0;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: "Times New Roman", Times, serif;
      font-size: 12pt;
      color: #000;
      width: 210mm;
      min-height: 297mm;
    }

    /* ===== WRAPPER dengan padding sebagai margin ===== */
   .page {
    width: 170mm;              /* lebih kecil dari 210mm supaya ada ruang kiri-kanan */
    min-height: 297mm;
    margin: 0 auto;            /* otomatis center di halaman */
    padding: 18mm 20mm;        /* atas-bawah 18mm, kiri-kanan 20mm */
    }

    /* ===== KOP SURAT ===== */
    .kop-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 4px;
    }
    .kop-table td {
      padding: 0;
      vertical-align: middle;
    }
    .kop-logo-cell {
      width: 65px;
      text-align: center;
      vertical-align: middle;
    }
    .kop-logo {
      width: 58px;
      height: 58px;
      border: 2px solid #333;
      text-align: center;
      font-size: 7pt;
      font-weight: bold;
      color: #333;
      padding-top: 16px;
      line-height: 1.4;
    }
    .kop-teks-cell {
      text-align: center;
      padding: 0 6px;
      vertical-align: middle;
    }
    .kop-universitas {
      font-size: 11pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .kop-fakultas {
      font-size: 18pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      line-height: 1.2;
    }
    .kop-alamat {
      font-size: 9pt;
      margin-top: 3px;
    }

    .garis-tebal {
      border: none;
      border-top: 3px solid #000;
      margin: 6px 0 2px 0;
    }
    .garis-tipis {
      border: none;
      border-top: 1px solid #000;
      margin: 0 0 16px 0;
    }

    /* ===== ISI SURAT ===== */
    .judul-surat {
      text-align: center;
      font-size: 13pt;
      font-weight: bold;
      text-decoration: underline;
      text-transform: uppercase;
      margin-bottom: 16px;
      letter-spacing: 1px;
    }

    .penerima {
      margin-bottom: 14px;
      line-height: 1.8;
      font-size: 12pt;
    }

    .isi {
      line-height: 1.8;
      margin-bottom: 10px;
      font-size: 12pt;
    }

    /* ===== DETAIL ACARA ===== */
    .detail-table {
      width: 90%;
      border-collapse: collapse;
      margin: 10px 0 10px 20px;
    }
    .detail-table td {
      padding: 4px 5px;
      font-size: 12pt;
      vertical-align: top;
      line-height: 1.7;
    }
    .col-label {
      width: 115px;
      white-space: nowrap;
    }
    .col-sep {
      width: 14px;
      text-align: center;
    }

    /* ===== PENUTUP ===== */
    .penutup {
      line-height: 1.8;
      margin-top: 14px;
      font-size: 12pt;
    }

    /* ===== TANDA TANGAN ===== */
    .ttd-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }
    .ttd-table td {
      padding: 0;
      vertical-align: top;
    }
    .ttd-kanan {
      width: 210px;
      text-align: center;
      font-size: 12pt;
      line-height: 1.8;
    }
    .ttd-spasi {
      height: 60px;
      display: block;
    }
    .ttd-nama {
      font-weight: bold;
      text-decoration: underline;
      display: block;
    }
    .ttd-nip {
      font-size: 11pt;
      display: block;
    }

    /* ===== CATATAN ===== */
    .catatan {
      margin-top: 30px;
      padding-top: 8px;
      border-top: 1px solid #000;
      font-size: 10pt;
      font-style: italic;
    }
  </style>
</head>
<body>
<div class="page">

  <!-- KOP SURAT -->
  <table class="kop-table">
    <tr>
      <td class="kop-logo-cell">
        <div class="kop-logo">LOGO<br>UNIV</div>
      </td>
      <td class="kop-teks-cell">
        <div class="kop-universitas">Universitas XXXXX</div>
        <div class="kop-fakultas">Fakultas Teknik</div>
        <div class="kop-alamat">Jl. Contoh No. 123, Kota, Indonesia &nbsp;|&nbsp; Telp. (021) 000-0000 &nbsp;|&nbsp; www.ft.univ.ac.id</div>
      </td>
    </tr>
  </table>
  <hr class="garis-tebal">
  <hr class="garis-tipis">

  <!-- JUDUL -->
  <div class="judul-surat">Surat Undangan</div>

  <!-- PENERIMA -->
  <div class="penerima">
    Kepada Yth.<br>
    <strong>{{ $nama }}</strong><br>
    Di Tempat
  </div>

  <!-- ISI -->
  <div class="isi">
    Dengan hormat,<br><br>
    Dalam rangka meningkatkan kompetensi mahasiswa di bidang teknologi informasi, kami mengundang
    Bapak/Ibu untuk menghadiri acara:
  </div>

  <!-- DETAIL ACARA -->
  <table class="detail-table">
    <tr>
      <td class="col-label"><strong>Acara</strong></td>
      <td class="col-sep">:</td>
      <td><strong>{{ $acara }}</strong></td>
    </tr>
    <tr>
      <td class="col-label">Hari/Tanggal</td>
      <td class="col-sep">:</td>
      <td>{{ $tanggal }}</td>
    </tr>
    <tr>
      <td class="col-label">Waktu</td>
      <td class="col-sep">:</td>
      <td>{{ $waktu }}</td>
    </tr>
    <tr>
      <td class="col-label">Tempat</td>
      <td class="col-sep">:</td>
      <td>{{ $tempat }}</td>
    </tr>
  </table>

  <!-- PENUTUP -->
  <div class="penutup">
    Demikian undangan ini kami sampaikan. Atas perhatian dan kehadirannya, kami ucapkan terima kasih.
  </div>

  <!-- TANDA TANGAN -->
  <table class="ttd-table">
    <tr>
      <td></td>
      <td class="ttd-kanan">
        Hormat kami,<br>
        Dekan Fakultas Teknik
        <span class="ttd-spasi"></span>
        <span class="ttd-nama">Nama Dekan</span>
        <span class="ttd-nip">NIP. 987654321</span>
      </td>
    </tr>
  </table>

  <!-- CATATAN -->
  <div class="catatan">
    <strong>Catatan:</strong> Undangan ini bersifat resmi dan tidak dapat dipindahtangankan.
  </div>

</div>
</body>
</html>