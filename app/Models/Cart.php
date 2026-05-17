<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ទាញយកព័ត៌មានលម្អិតរបស់ផលិតផលនៅក្នុង Cart
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}