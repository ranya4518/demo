<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function showlist(){
        $user = Auth::user();
    
        // Kullanıcının favori ürünlerini al
        $favorites = Wishlist::where('user_id', $user->id)->with('product')->get();
    
        return response()->json(['favorite'=>$favorites]);
    }

    public function favorite(Request $request, $productId) {
        // Giriş yapan kullanıcıyı al
        $user = Auth::user();
    
        // Ürünü bul
        $product = Product::find($productId);
    
        if ($user && $product) {
            // Kullanıcının bu ürünü favori listesine eklenip eklenmediğini kontrol et
            $isFavorite = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)->exists();
            // Eğer ürün zaten favori listesinde ise hata mesajı döndür
            if ($isFavorite) {
                return response()->json(['error' => 'Ürün zaten favori listesinde']);
            }
    
            // Favori listesine ekle
            $wishlist = new Wishlist();
            $wishlist->user_id = $user->id;
            $wishlist->product_id = $product->id;
            $wishlist->save();
    
            return response()->json(['message' => 'Ürün sepetinize eklendi']);
        } else {
            return response()->json(['error' => 'Bir hata oluştu']);
        }
    }
    

   
    
        public function removFavorite($productId){
            $user=Auth::user();   
             if($user){
           Wishlist::where('user_id', $user->id)->where('product_id',$productId)->delete();
           return response()->json(['message'=>'ürün sepetinizden silind']);
             }else{
             return response()->json(['message'=>'bir hata oluştu']);
             }
           }
}
