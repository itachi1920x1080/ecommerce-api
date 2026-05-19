<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

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
    // 🎯 មុខងារសម្រាប់ Admin បន្ថែមផលិតផលថ្មី
    public function store(Request $request)
    {
        // ១. ពិនិត្យមើលទិន្នន័យដែល Admin បញ្ជូនមក (Validation) ថាត្រឹមត្រូវតាមលក្ខខណ្ឌឬអត់
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // ត្រូវតែជា category ដែលមានស្រាប់ក្នុងប្រព័ន្ធ
            'regular_price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        // ២. បង្កើត Slug និង SKU ដោយស្វ័យប្រវត្តិ
        // ឧទាហរណ៍៖ name "RUPP T-Shirt" => slug "rupp-t-shirt-123xyz"
        $slug = Str::slug($request->name) . '-' . uniqid(); 
        $sku = 'PROD-' . strtoupper(Str::random(6)); // បង្កើតកូដទំនិញ (SKU) ប្រវែង ៦ ខ្ទង់

        // ៣. បង្កើតទិន្នន័យចូលតារាង products
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'sku' => $sku,
            'category_id' => $request->category_id,
            'regular_price' => $request->regular_price,
            'discount_price' => $request->discount_price, // អាចមាន ឬអត់ក៏បាន
            'qty' => $request->qty,
            'description' => $request->description,
        ]);

        // ៤. ឆ្លើយតបទៅកាន់ Frontend វិញ
        return response()->json([
            'success' => true,
            'message' => 'បានបន្ថែមផលិតផលថ្មីចូលស្តុកជោគជ័យ!',
            'data' => $product
        ], 201); // 201 Created
    }
    
}