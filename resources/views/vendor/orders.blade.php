@extends('layouts.app-admin')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pesanan</h4>
                <p class="card-description">Pesanan yang sudah dibayar (Lunas)</p>

                <!-- Filter -->
                <form method="GET" action="{{ route('vendor.orders') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Dari Tanggal</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('vendor.orders') }}" class="btn btn-light">
                                        <i class="mdi mdi-refresh"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    <small>
                                        @foreach($order->details->take(2) as $detail)
                                            {{ $detail->menu->name }} ({{ $detail->quantity }}x)
                                            @if(!$loop->last), @endif
                                        @endforeach
                                        @if($order->details->count() > 2)
                                            <br><span class="text-muted">+{{ $order->details->count() - 2 }} item lainnya</span>
                                        @endif
                                    </small>
                                </td>
                                <td><strong>{{ $order->formatted_total }}</strong></td>
                                <td>
                                    <span class="badge badge-success">Lunas</span>
                                    <br>
                                    <small class="text-muted">{{ $order->paid_at->format('d M, H:i') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('vendor.orders.detail', $order->order_number) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="mdi mdi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="mdi mdi-information-outline text-muted" style="font-size: 48px;"></i>
                                    <p class="text-muted mt-2">Tidak ada pesanan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection