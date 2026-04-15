@extends('layouts.app-admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($order->isPaid())
                        <i class="mdi mdi-check-circle text-success" style="font-size: 64px;"></i>
                        <h3 class="text-success mt-2">Pembayaran Berhasil!</h3>
                    @else
                        <i class="mdi mdi-clock-outline text-warning" style="font-size: 64px;"></i>
                        <h3 class="text-warning mt-2">Menunggu Pembayaran</h3>
                    @endif
                    <p class="text-muted">Order #{{ $order->order_number }}</p>
                </div>

                <hr>

                <!-- Vendor Info -->
                <div class="mb-4">
                    <h5>{{ $order->vendor->name }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="mdi mdi-map-marker"></i> {{ $order->vendor->address }}
                    </p>
                    @if($order->vendor->phone)
                        <p class="text-muted small">
                            <i class="mdi mdi-phone"></i> {{ $order->vendor->phone }}
                        </p>
                    @endif
                </div>

                <!-- Order Info -->
                <div class="row mb-4">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Tanggal Pesanan</p>
                        <p class="mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Status Pembayaran</p>
                        <p class="mb-0">
                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">Lunas</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="badge badge-danger">Gagal</span>
                            @else
                                <span class="badge badge-secondary">{{ $order->payment_status }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($order->paid_at)
                <div class="row mb-4">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Dibayar Pada</p>
                        <p class="mb-0">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Metode Pembayaran</p>
                        <p class="mb-0 text-capitalize">{{ $order->payment_method ?? '-' }}</p>
                    </div>
                </div>
                @endif

                <hr>

                <!-- Order Items -->
                <h5 class="mb-3">Detail Pesanan</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $detail)
                            <tr>
                                <td>
                                    {{ $detail->menu->name }}
                                    @if($detail->notes)
                                        <br><small class="text-muted">{{ $detail->notes }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-right">{{ $detail->formatted_price }}</td>
                                <td class="text-right">{{ $detail->formatted_subtotal }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                                <td class="text-right"><strong>{{ $order->formatted_total }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($order->notes)
                <div class="alert alert-info">
                    <strong>Catatan:</strong> {{ $order->notes }}
                </div>
                @endif

                <!-- Actions -->
                <div class="mt-4 text-center">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="mdi mdi-printer"></i> Cetak Nota
                    </button>
                    <a href="{{ route('customer.order') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-cart"></i> Pesan Lagi
                    </a>
                </div>

                <!-- Customer Info -->
                <div class="mt-4 pt-4 border-top text-center text-muted small">
                    <p class="mb-0">Terima kasih atas pesanan Anda!</p>
                    <p class="mb-0">Customer: {{ $order->user->name }}</p>
                    @if($order->transaction_id)
                        <p class="mb-0">Transaction ID: {{ $order->transaction_id }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .card, .card * {
        visibility: visible;
    }
    .card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .btn {
        display: none !important;
    }
}
</style>
@endsection