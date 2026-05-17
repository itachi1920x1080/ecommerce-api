<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ១. មុខងារថែមទំនិញចូលកន្ត្រក (Add to Cart)
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user_id = Auth::id(); // ចាប់យក ID របស់ User ដែលកំពុង Login តាមរយៈ Token

        // ពិនិត្យមើលបើមានផលិតផលនេះក្នុងកន្ត្រករបស់ User ហ្នឹងហើយ ឱ្យវាថែមតែចំនួន (Quantity) ទៅបានហើយ
        $cartItem = Cart::where('user_id', $user_id)
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // បើអត់ទាន់មានទេ បង្កើតជួរថ្មី
            Cart::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'បានថែមទំនិញចូលក្នុងកន្ត្រកជោគជ័យ!'
        ], 200);
    }

    // ២. មុខងារមើលទំនិញក្នុងកន្ត្រកខ្លួនឯង (Get Cart Items)
    public function getCart()
    {
        $user_id = Auth::id();
        
        // ទាញយកទំនិញទាំងអស់របស់ User នោះ ព្រមទាំងព័ត៌មានទំនិញ (with product)
        $cartItems = Cart::with('product')->where('user_id', $user_id)->get();

        return response()->json([
            'success' => true,
            'data' => $cartItems
        ], 200);
    }
}