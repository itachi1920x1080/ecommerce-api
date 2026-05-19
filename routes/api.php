<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| 🚪 ក្រុមទី ១៖ ផ្លូវទ្វារចំហ (Public Routes) - មិនទាមទារសោរ Token ឡើយ
|--------------------------------------------------------------------------
*/

// ផ្លូវសម្រាប់ទាញយកផលិតផល (មានប្រព័ន្ធ Filter & Pagination)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// ផ្លូវសម្រាប់ប្រព័ន្ធ Authentication (Register & Login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| 🔒 ក្រុមទី ២៖ ផ្លូវមានសោរការពារ (Protected Routes) - ទាមទារ Bearer Token ដាច់ខាត
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // មុខងារចាកចេញពីគណនី (កម្ទេចសោរ Token ចោល)
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // ទាញយកព័ត៌មានអ្នកប្រើប្រាស់ដែលកំពុង Login
    Route::get('/my-profile', function (Request $request) {
        return $request->user();
    });

    // ប្រព័ន្ធកន្ត្រកទំនិញ (Cart System)
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    
    // ប្រព័ន្ធបញ្ជាទិញ និងវិក្កយបត្រ (Order System)
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/my-orders', [OrderController::class, 'getMyOrders']); 

    /*
    |--------------------------------------------------------------------------
    | 👑 ក្រុមទី ៣៖ ផ្លូវពិសេសសម្រាប់ ADMIN - ទាមទារ Token ផង និងមានសិទ្ធិជា Admin ផង
    |--------------------------------------------------------------------------
    | Middleware \App\Http\Middleware\CheckAdmin នឹងដើរតួជាអ្នកយាមទ្វារជាន់ទី២
    */
    Route::middleware(\App\Http\Middleware\CheckAdmin::class)->group(function () {
        
        // ផ្លូវសម្រាប់ Admin ចូលទៅលុបផលិតផលចោលពីហាង
        Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);
        // 🎯 ផ្លូវសម្រាប់ Admin បន្ថែមផលិតផលថ្មី
        Route::post('/admin/products', [ProductController::class, 'store']);
        
        // ផ្លូវលុបទំនិញ (របស់ចាស់បងមានស្រាប់)
        Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);
            
    });


});