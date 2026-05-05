@extends('layouts.app-admin')

@section('title', 'Barcode Reader')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="mdi mdi-barcode-scan"></i> Barcode Reader
                </h4>
            </div>
            <div class="card-body text-center">

                {{-- Area Kamera --}}
                <div id="reader" style="width:100%; max-width:480px; margin:0 auto;"></div>

                {{-- Tombol --}}
                <div class="mt-3">
                    <button id="btn-start" class="btn btn-primary btn-sm" onclick="startScanner()">
                        <i class="mdi mdi-camera"></i> Mulai Scan
                    </button>
                    <button id="btn-stop" class="btn btn-danger btn-sm d-none" onclick="stopScanner()">
                        <i class="mdi mdi-stop"></i> Stop Scan
                    </button>
                </div>

                {{-- Hasil Scan --}}
                <div id="hasil-scan" class="d-none mt-4">
                    <hr>
                    <h5 class="text-success">
                        <i class="mdi mdi-check-circle"></i> Barcode Terbaca!
                    </h5>
                    <table class="table table-bordered mt-3 text-left" style="max-width:400px; margin:0 auto;">
                        <tr>
                            <th style="width:45%">ID Barang</th>
                            <td id="result-id">-</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td id="result-nama">-</td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td id="result-harga">-</td>
                        </tr>
                    </table>
                    <button class="btn btn-secondary btn-sm mt-3" onclick="resetScanner()">
                        <i class="mdi mdi-refresh"></i> Scan Lagi
                    </button>
                </div>

                {{-- Barang Tidak Ditemukan --}}
                <div id="tidak-ditemukan" class="d-none mt-4">
                    <hr>
                    <h5 class="text-danger">
                        <i class="mdi mdi-alert-circle"></i> Barang tidak ditemukan!
                    </h5>
                    <p class="text-muted" id="result-raw-id"></p>
                    <button class="btn btn-secondary btn-sm mt-2" onclick="resetScanner()">
                        <i class="mdi mdi-refresh"></i> Scan Lagi
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Audio beep --}}
<audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts-page')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrCode = null;
    let sudahScan   = false; // flag biar tidak dobel trigger

    function startScanner() {
        sudahScan = false;
        document.getElementById('btn-start').classList.add('d-none');
        document.getElementById('btn-stop').classList.remove('d-none');
        document.getElementById('hasil-scan').classList.add('d-none');
        document.getElementById('tidak-ditemukan').classList.add('d-none');

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 300, height: 100 } },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            alert('Tidak bisa akses kamera. Pastikan akses via localhost dan izinkan kamera di browser.');
            resetTombol();
        });
    }

    function onScanSuccess(decodedText) {
        if (sudahScan) return; // cegah dobel trigger
        sudahScan = true;

        // 1. Beep
        document.getElementById('beep-sound').play();

        // 2. Stop scanner
        html5QrCode.stop().then(() => {
            resetTombol();
        });

        // 3. Fetch data barang
        fetch(`/barcode-reader/cari?id=${encodeURIComponent(decodedText)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('result-id').innerText    = data.barang.id_barang;
                    document.getElementById('result-nama').innerText  = data.barang.nama_barang;
                    document.getElementById('result-harga').innerText = 'Rp ' + Number(data.barang.harga).toLocaleString('id-ID');
                    document.getElementById('hasil-scan').classList.remove('d-none');
                } else {
                    document.getElementById('result-raw-id').innerText = 'ID terbaca: ' + decodedText;
                    document.getElementById('tidak-ditemukan').classList.remove('d-none');
                }
            })
            .catch(() => {
                alert('Gagal mengambil data dari server.');
            });
    }

    function onScanFailure(error) {
        // diam saja, terus scan
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => resetTombol());
        }
    }

    function resetTombol() {
        document.getElementById('btn-stop').classList.add('d-none');
        document.getElementById('btn-start').classList.remove('d-none');
    }

    function resetScanner() {
        document.getElementById('hasil-scan').classList.add('d-none');
        document.getElementById('tidak-ditemukan').classList.add('d-none');
        startScanner();
    }
</script>
@endpush