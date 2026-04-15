@extends('layouts.app-admin')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">Dashboard Vendor</h4>
                <p class="text-muted">Selamat datang, {{ $vendor->name }}!</p>
            </div>
            <div>
                <a href="{{ route('vendor.menu.index') }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-silverware-fork-knife"></i> Kelola Menu
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title text-md-center text-xl-left">Total Menu</p>
                <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{ $totalMenus }}</h3>
                    <i class="mdi mdi-silverware-variant icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                </div>
                <p class="mb-0 mt-2 text-success">
                    <span class="text-black ml-1"><small>{{ $activeMenus }} tersedia</small></span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title text-md-center text-xl-left">Pesanan Hari Ini</p>
                <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{ $todayOrders }}</h3>
                    <i class="mdi mdi-cart icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                </div>
                <p class="mb-0 mt-2 text-muted">
                    <span class="text-black ml-1"><small>{{ now()->format('d M Y') }}</small></span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title text-md-center text-xl-left">Pendapatan Hari Ini</p>
                <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    <i class="mdi mdi-cash-multiple icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                </div>
                <p class="mb-0 mt-2 text-success">
                    <span class="text-black ml-1"><small>Pesanan yang sudah dibayar</small></span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Pesanan Terbaru (Hari Ini)</h4>
                    <a href="{{ route('vendor.orders') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="mdi mdi-arrow-right"></i>
                    </a>
                </div>

                @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Waktu</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('vendor.orders.detail', $order->order_number) }}">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->created_at->format('H:i') }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    <small>
                                        @foreach($order->details->take(2) as $detail)
                                            {{ $detail->menu->name }} ({{ $detail->quantity }}x)
                                            @if(!$loop->last), @endif
                                        @endforeach
                                        @if($order->details->count() > 2)
                                            <span class="text-muted">+{{ $order->details->count() - 2 }} lainnya</span>
                                        @endif
                                    </small>
                                </td>
                                <td><strong>{{ $order->formatted_total }}</strong></td>
                                <td>
                                    <span class="badge badge-success">Lunas</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="mdi mdi-cart-off text-muted" style="font-size: 64px;"></i>
                    <p class="text-muted mt-3">Belum ada pesanan hari ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection