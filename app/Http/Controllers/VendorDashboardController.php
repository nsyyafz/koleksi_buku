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
    /**
     * Constructor - require auth vendor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard vendor
     */
    public function index()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        // Statistics
        $totalMenus = Menu::where('vendor_id', $vendor->id)->count();
        $activeMenus = Menu::where('vendor_id', $vendor->id)
                           ->where('is_available', true)
                           ->count();
        
        $todayOrders = Order::where('vendor_id', $vendor->id)
                            ->whereDate('created_at', today())
                            ->count();
        
        $todayRevenue = Order::where('vendor_id', $vendor->id)
                             ->whereDate('created_at', today())
                             ->where('payment_status', 'paid')
                             ->sum('total_amount');
        
        // Recent paid orders (today)
        $recentOrders = Order::where('vendor_id', $vendor->id)
                             ->whereDate('created_at', today())
                             ->where('payment_status', 'paid')
                             ->with(['user', 'details.menu'])
                             ->orderBy('created_at', 'desc')
                             ->limit(10)
                             ->get();

        return view('vendor.dashboard', compact(
            'vendor',
            'totalMenus',
            'activeMenus',
            'todayOrders',
            'todayRevenue',
            'recentOrders'
        ));
    }

    /**
     * List all paid orders
     */
    public function orders(Request $request)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'Anda bukan vendor!');
        }

        // Query orders
        $query = Order::where('vendor_id', $vendor->id)
                      ->where('payment_status', 'paid')
                      ->with(['user', 'details.menu']);

        // Filter by date range (optional)
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('vendor.orders', compact('vendor', 'orders'));
    }

    /**
     * Detail order
     */
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
}