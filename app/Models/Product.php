<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 🎯 លុប $guarded ចោល ទុកតែ $fillable មួយបានហើយបង ដើម្បីកុំឱ្យជាន់គ្នា
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'category_id', 
        'regular_price',
        'discount_price',
        'qty',
        'description',
        'image'
    ];

    // 🎯 ថែមមុខងារនេះចូល ដើម្បីឱ្យផលិតផលនេះស្គាល់ Category របស់វា (ជួយឱ្យប្រព័ន្ធ Filter ដើររលូន)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}