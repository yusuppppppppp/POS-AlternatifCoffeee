<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data (dengan customer_name)
        $validator = Validator::make($request->all(), [
            'customer_name' => 'nullable|string|max:100',
            'order_type' => 'required|in:Dine in,Take Away',
            'total_amount' => 'required|numeric',
            'cash_paid' => 'required|numeric',
            'balance' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi gagal saat menyimpan order.', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Simpan order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'order_type' => $request->order_type,
                'total_amount' => $request->total_amount,
                'cash_paid' => $request->cash_paid,
                'balance' => $request->balance,
                'user_id' => Auth::id()
            ]);

            Log::info('Order berhasil dibuat dengan ID: ' . $order->id);

            // Simpan item satu per satu
            foreach ($request->items as $item) {
                Log::info('Menyimpan item: ' . json_encode($item));

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ]);
            }

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'created_at' => $order->created_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function todayOrders()
{
    $today = Carbon::today();

    $orders = Order::with(['items', 'user'])
        ->whereDate('created_at', $today)
        ->orderBy('created_at', 'desc')
        ->get();

    // KEMBALIKAN DALAM BENTUK JSON
    return response()->json([
        'success' => true,
        'data' => $orders
    ]);
}

}
