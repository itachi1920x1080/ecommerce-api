<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;

// មុខងារនេះមានស្រាប់ (ទុកវាចោល)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ----------------------------------------------------
// 🎯 ថែមផ្លូវ API របស់បងនៅខាងក្រោមនេះ៖
// ----------------------------------------------------

// ផ្លូវសម្រាប់ទាញយកផលិតផលទាំងអស់
Route::get('/products', [ProductController::class, 'index']);

// ផ្លូវសម្រាប់ទាញយកផលិតផលតែ ១
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ផ្លូវនេះសម្រាប់តេស្តមើលថា តើ Token ដែលគេកាន់មក ត្រឹមត្រូវឬអត់
Route::middleware('auth:sanctum')->get('/my-profile', function (Request $request) {
    return $request->user();
});

// រាល់ Route នៅក្នុង Group នេះ គឺទាមទារសោរ Token ដាច់ខាត
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    
});