<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        $totalMenus  = Menu::where('vendor_id', $vendor->id)->count();
        $activeMenus = Menu::where('vendor_id', $vendor->id)
                           ->where('is_available', true)->count();

        $todayOrders = Order::where('vendor_id', $vendor->id)
                            ->whereDate('created_at', today())->count();

        $todayRevenue = Order::where('vendor_id', $vendor->id)
                             ->whereDate('created_at', today())
                             ->where('payment_status', 'paid')
                             ->sum('total_amount');

        $recentOrders = Order::where('vendor_id', $vendor->id)
                             ->whereDate('created_at', today())
                             ->where('payment_status', 'paid')
                             ->with(['user', 'details.menu'])
                             ->orderBy('created_at', 'desc')
                             ->limit(10)->get();

        return view('vendor.dashboard', compact(
            'vendor', 'totalMenus', 'activeMenus',
            'todayOrders', 'todayRevenue', 'recentOrders'
        ));
    }

    public function orders(Request $request)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        $query = Order::where('vendor_id', $vendor->id)
                      ->where('payment_status', 'paid')
                      ->with(['user', 'details.menu']);

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('vendor.orders', compact('vendor', 'orders'));
    }

    public function orderDetail($orderNumber)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        $order = Order::where('order_number', $orderNumber)
                      ->where('vendor_id', $vendor->id)
                      ->with(['user', 'details.menu'])
                      ->firstOrFail();

        return view('vendor.order-detail', compact('vendor', 'order'));
    }

    /**
     * Halaman QR Code Reader
     */
    public function qrReader()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        return view('vendor.qr-reader');
    }

    /**
     * API: Ambil detail order dari hasil scan QR (hanya order milik vendor ini)
     */
    public function getOrderByQr($orderNumber)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda bukan vendor.'
            ], 403);
        }

        $order = Order::where('order_number', $orderNumber)
                      ->where('vendor_id', $vendor->id)
                      ->with(['details.menu'])
                      ->first();

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan atau bukan milik vendor ini.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'order_number'   => $order->order_number,
                'payment_status' => $order->payment_status,
                'total_amount'   => $order->total_amount,
                'details'        => $order->details->map(fn($d) => [
                    'menu_name' => $d->menu->name,
                    'quantity'  => $d->quantity,
                    'subtotal'  => $d->subtotal,
                ]),
            ]
        ]);
    }
}