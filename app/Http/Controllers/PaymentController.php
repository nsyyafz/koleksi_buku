<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * Handle Midtrans notification callback
     */
    public function notification(Request $request)
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            // Get notification dari Midtrans
            $notification = new \Midtrans\Notification();

            $orderNumber = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status;

            // Cari order berdasarkan order_number
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found'
                ], 404);
            }

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($fraudStatus == 'accept') {
                        // Payment success
                        $order->update([
                            'payment_status' => 'paid',
                            'payment_method' => $paymentType,
                            'transaction_id' => $notification->transaction_id,
                            'paid_at' => now(),
                        ]);
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                // Payment success (non credit card)
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => $paymentType,
                    'transaction_id' => $notification->transaction_id,
                    'paid_at' => now(),
                ]);
            } elseif ($transactionStatus == 'pending') {
                // Payment pending
                $order->update([
                    'payment_status' => 'pending',
                    'payment_method' => $paymentType,
                    'transaction_id' => $notification->transaction_id,
                ]);
            } elseif ($transactionStatus == 'deny') {
                // Payment denied
                $order->update([
                    'payment_status' => 'failed',
                    'payment_method' => $paymentType,
                    'transaction_id' => $notification->transaction_id,
                ]);
            } elseif ($transactionStatus == 'expire') {
                // Payment expired
                $order->update([
                    'payment_status' => 'expired',
                    'payment_method' => $paymentType,
                    'transaction_id' => $notification->transaction_id,
                ]);
            } elseif ($transactionStatus == 'cancel') {
                // Payment cancelled
                $order->update([
                    'payment_status' => 'failed',
                    'payment_method' => $paymentType,
                    'transaction_id' => $notification->transaction_id,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payment notification processed'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status (untuk AJAX dari frontend)
     */
    public function checkStatus(Request $request)
    {
        $orderNumber = $request->order_number;
        
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'order_number' => $order->order_number,
                'payment_status' => $order->payment_status,
                'is_paid' => $order->isPaid(),
            ]
        ]);
    }
}