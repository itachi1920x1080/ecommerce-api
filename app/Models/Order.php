<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // វិក្កយបត្រមួយ មានទំនិញលម្អិតច្រើនជួរ
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}