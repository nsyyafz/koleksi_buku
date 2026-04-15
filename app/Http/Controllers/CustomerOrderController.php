<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerOrderController extends Controller
{
    /**
     * Tampilkan halaman pemesanan
     */
    public function index()
    {
        $vendors = Vendor::active()->get();
        return view('customer.order', compact('vendors'));
    }

    /**
     * API: Get menu berdasarkan vendor
     */
    public function getMenus(Request $request)
    {
        $vendorId = $request->vendor_id;
        
        $menus = Menu::where('vendor_id', $vendorId)
                     ->where('is_available', true)
                     ->orderBy('category')
                     ->orderBy('name')
                     ->get();
        
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Data menu berhasil diambil',
            'data' => $menus
        ]);
    }

    /**
     * Create order dan redirect ke payment
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            // 1. Generate atau get guest user
            $user = $this->getOrCreateGuestUser();

            // 2. Calculate total
            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $quantity = $item['quantity'];
                $subtotal = $menu->price * $quantity;
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            // 3. Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'vendor_id' => $request->vendor_id,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // 4. Create order details
            foreach ($orderItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'notes' => $item['notes'],
                ]);
            }

            // 5. Generate Midtrans Snap Token
            $snapToken = $this->createMidtransTransaction($order);
            
            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Order berhasil dibuat',
                'data' => [
                    'order_number' => $order->order_number,
                    'snap_token' => $snapToken,
                    'total_amount' => $order->total_amount,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal membuat order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get atau create guest user
     */
    private function getOrCreateGuestUser()
    {
        // Cari guest user terakhir
        $lastGuest = User::where('name', 'LIKE', 'Guest_%')
                         ->orderBy('id', 'desc')
                         ->first();

        if ($lastGuest) {
            // Extract nomor dari Guest_XXXXXXX
            $lastNumber = (int) substr($lastGuest->name, 6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $guestName = 'Guest_' . str_pad($newNumber, 7, '0', STR_PAD_LEFT);
        $guestEmail = strtolower(str_replace('_', '', $guestName)) . '@kantin.local';

        $user = User::create([
            'name' => $guestName,
            'email' => $guestEmail,
            'password' => Hash::make(Str::random(16)),
            'role' => 'customer',
        ]);

        return $user;
    }

    /**
     * Create Midtrans transaction dan return Snap Token
     */
    private function createMidtransTransaction($order)
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // Prepare item details
        $itemDetails = [];
        foreach ($order->details as $detail) {
            $itemDetails[] = [
                'id' => $detail->menu_id,
                'price' => $detail->price,
                'quantity' => $detail->quantity,
                'name' => $detail->menu->name,
            ];
        }

        // Transaction params
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan receipt/nota setelah pembayaran
     */
    public function receipt($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                      ->with(['vendor', 'details.menu', 'user'])
                      ->firstOrFail();

        return view('customer.receipt', compact('order'));
    }
}