<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // 🎯 មុខងារ Checkout (បំប្លែងពី Cart ទៅជា Order)
    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500'
        ]);

        $user_id = Auth::id();

        // ១. ទាញយកទំនិញក្នុងកន្ត្រករបស់ User មកមើល បើអត់មានទេមិនឱ្យ Checkout ឡើយ
        $cartItems = Cart::with('product')->where('user_id', $user_id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'កន្ត្រកទំនិញរបស់អ្នកនៅទទេឡើយ មិនអាច Checkout បានទេ!'
            ], 400);
        }

        // ២. គណនាតម្លៃលុយសរុប (Total Amount)
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            // បើមានតម្លៃបញ្ចុះតម្លៃ (discount_price) យកតម្លៃនោះ បើអត់ទេយកតម្លៃធម្មតា
            $price = $item->product->discount_price ?? $item->product->regular_price;
            $totalAmount += $price * $item->quantity;
        }

        // ប្រើប្រាស់ DB::transaction ដើម្បីធានាថា បើកូដគាំងត្រង់ណា វានឹងទាញទិន្នន័យថយក្រោយវិញ (សុវត្ថិភាពទិន្នន័យ)
        DB::beginTransaction();

        try {
            // ៣. បង្កើតជួរទិន្នន័យក្នុងតារាង orders
            $order = Order::create([
                'user_id' => $user_id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $totalAmount,
                'shipping_address' => $request->shipping_address,
                'status' => 'pending'
            ]);

            // ៤. រុញទំនិញនីមួយៗចូលទៅតារាង order_items
            foreach ($cartItems as $item) {
                $price = $item->product->discount_price ?? $item->product->regular_price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price
                ]);

                // 🎯 (ច្នៃបន្ថែម) ដកចំនួនទំនិញចេញពីស្តុកផលិតផល
                $item->product->decrement('qty', $item->quantity);
            }

            // ៥. សម្អាតកន្ត្រកទំនិញចោល
            Cart::where('user_id', $user_id)->delete();

            DB::commit(); // រក្សាទុកការប្រែប្រួលទាំងអស់ចូល Database

            return response()->json([
                'success' => true,
                'message' => 'ការបញ្ជាទិញរបស់លោកអ្នកទទួលបានជោគជ័យ!',
                'order' => $order->load('items.product') // ផ្ទុកព័ត៌មានលម្អិតទៅឱ្យ Frontend
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // បើមានបញ្ហាគាំង វានឹងលុបអ្វីៗដែលទើបតែធ្វើអម្បាញ់មិញចោល ការពារទិន្នន័យខូច
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()
            ], 500);
        }
    }

    // 🎯 មុខងារមើលប្រវត្តិបញ្ជាទិញរបស់ User ផ្ទាល់ខ្លួន (Order History)
    public function getMyOrders()
    {
        $user_id = Auth::id();
        $orders = Order::with('items.product')->where('user_id', $user_id)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }
}