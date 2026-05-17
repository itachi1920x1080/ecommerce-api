<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // មុខងារនេះសម្រាប់ទាញយកបញ្ជីផលិតផលទាំងអស់
    public function index(Request $request)
    {
        // បង្កើត Query សម្រាប់ទាញយក Product
        $query = Product::query();

        // 🎯 បើ Frontend គេបញ្ជូន category_id មក វានឹងធ្វើការ Filter ភ្លាម
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // ទាញទិន្នន័យមកម្តង ១០ ផលិតផល
        $products = $query->latest()->paginate(10); 

        return response()->json([
            'success' => true,
            'message' => 'ទាញយកបញ្ជីផលិតផលជោគជ័យ',
            'data' => $products
        ], 200);
    }

    // មុខងារនេះសម្រាប់ទាញយកផលិតផលតែ ១ តាមរយៈលេខ ID
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'រកមិនឃើញផលិតផលនេះទេ'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'ទាញយកព័ត៌មានលម្អិតជោគជ័យ',
            'data' => $product
        ], 200);
    }
    
}