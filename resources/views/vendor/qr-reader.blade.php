@extends('layouts.app-admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="mdi mdi-qrcode-scan"></i> Scan QR Code Customer
                </h4>
            </div>
            <div class="card-body">

                {{-- Area Scanner --}}
                <div class="text-center mb-3">
                    <div id="reader" style="width:100%;"></div>
                </div>

                {{-- Tombol Scan Ulang --}}
                <div class="text-center mb-4" id="btn-rescan" style="display:none;">
                    <button class="btn btn-outline-primary" onclick="startScan()">
                        <i class="mdi mdi-refresh"></i> Scan Ulang
                    </button>
                </div>

                {{-- Loading --}}
                <div id="loading" class="text-center py-4" style="display:none;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Memuat data pesanan...</p>
                </div>

                {{-- Error --}}
                <div id="error-box" class="alert alert-danger mt-3" style="display:none;"></div>

                {{-- Hasil Scan --}}
                <div id="scan-result" style="display:none;">
                    <hr>
                    <h5 class="mb-3">Hasil Pesanan</h5>
                    <div id="result-content"></div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Audio Beep --}}
<audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts-page')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrCode = null;

    function startScan() {
        document.getElementById('scan-result').style.display = 'none';
        document.getElementById('error-box').style.display   = 'none';
        document.getElementById('btn-rescan').style.display  = 'none';
        document.getElementById('reader').innerHTML           = '';

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            function (decodedText) {
                // 1. Beep
                document.getElementById('beep-sound').play();

                // 2. Stop scanner
                html5QrCode.stop().then(() => {
                    document.getElementById('btn-rescan').style.display = 'block';
                    // 3. Fetch data order
                    fetchOrder(decodedText);
                });
            },
            function (_errorMsg) { /* diabaikan tiap frame */ }
        ).catch(err => {
            document.getElementById('error-box').textContent    = 'Tidak bisa akses kamera: ' + err;
            document.getElementById('error-box').style.display  = 'block';
        });
    }

    function fetchOrder(orderNumber) {
        document.getElementById('loading').style.display      = 'block';
        document.getElementById('scan-result').style.display  = 'none';

        fetch(`/vendor/order-detail/${orderNumber}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            if (data.status === 'success') {
                renderResult(data.data);
            } else {
                showError(data.message || 'Order tidak ditemukan.');
            }
        })
        .catch(() => {
            document.getElementById('loading').style.display = 'none';
            showError('Terjadi kesalahan saat mengambil data.');
        });
    }

    function renderResult(order) {
        const statusMap = {
            paid:    '<span class="badge badge-success">Lunas</span>',
            pending: '<span class="badge badge-warning">Pending</span>',
            failed:  '<span class="badge badge-danger">Gagal</span>',
        };
        const statusBadge = statusMap[order.payment_status]
            ?? `<span class="badge badge-secondary">${order.payment_status}</span>`;

        const itemsHtml = order.details.map(d => `
            <tr>
                <td>${d.menu_name}</td>
                <td class="text-center">${d.quantity}</td>
                <td class="text-right">Rp ${Number(d.subtotal).toLocaleString('id-ID')}</td>
            </tr>
        `).join('');

        document.getElementById('result-content').innerHTML = `
            <div class="row mb-3">
                <div class="col-6">
                    <small class="text-muted">No. Pesanan</small>
                    <p class="font-weight-bold mb-0">${order.order_number}</p>
                </div>
                <div class="col-6">
                    <small class="text-muted">Status Bayar</small>
                    <p class="mb-0">${statusBadge}</p>
                </div>
            </div>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Menu</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>${itemsHtml}</tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right font-weight-bold">TOTAL</td>
                        <td class="text-right font-weight-bold">
                            Rp ${Number(order.total_amount).toLocaleString('id-ID')}
                        </td>
                    </tr>
                </tfoot>
            </table>
        `;

        document.getElementById('scan-result').style.display = 'block';
    }

    function showError(msg) {
        document.getElementById('error-box').textContent   = msg;
        document.getElementById('error-box').style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', startScan);
</script>
@endpush