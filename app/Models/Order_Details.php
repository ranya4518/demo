<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Details extends Model
{
    use HasFactory;

     // Many-to-One relation with Order
     public function order()
     {
         return $this->belongsTo(Order::class);
     }
 
     // Many-to-One relation with Product
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
// stok kontrollu
/*foreach ($order->orderDetails as $orderDetail) {
    $product = $orderDetail->product;
    // Ürünün stok miktarını azalt
    $product->stock -= $orderDetail->quantity;
    $product->save();
}
*/
