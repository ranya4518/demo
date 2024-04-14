<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\Product_Reviews;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{  
public function index(){
$product=Product::all();
return response()->json(['products'=>$product]);
}
 public function show($id){
    $product=Product::with('product__reviews')->find($id);
    $stokDurumu = $product->stock < 10 ? 'Tükenmek üzere' : 'Stok var';
    $averageRating=$product->product__reviews->avg('rating');
    $reviewCount = $product->product__reviews->count();
     return response()->json([
    'product' => $product,
    'average_rating' => $averageRating,
    'review_count' => $reviewCount,
    'stock'=>$stokDurumu
          ]);
   }
   public function addToCart($productId, $quantity)
   {
       // Ürünü veritabanından bul
       $product = Product::find($productId);
   
       // Eğer ürün bulunamadıysa veya stokta yeterli miktarda değilse
       if (!$product || $product->stock < $quantity) {
           // Kullanıcıya uygun bir hata mesajı göster
           return "Üzgünüz, bu ürün stokta yeterli miktarda bulunmamaktadır.";
       }
       if ($product->stock < 10) {
        // "Yakında tükenecek" mesajını ekleyerek ürünü görüntüle
        return view('product.show', ['product' => $product, 'stockMessage' => 'Yakında tükenecek']);
       }
       // Ürünü sepete ekle
       return "Ürün başarıyla sepete eklendi.";
   }
public function search($query){

$product=Product::where('name','like',"%$query%")
->orWhere('description','like',"%$query%")->get();
return response()->json(['resualt'=>  $product]);
  }
}
