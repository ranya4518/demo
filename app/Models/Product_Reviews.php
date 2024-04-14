<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Reviews extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'review',
        'rating',
    ];


     // Many-to-One relation with User
     public function user()
     {
         return $this->belongsTo(User::class);
     }
 
     // Many-to-One relation with Product
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
