<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Product_Reviews;
use Illuminate\Http\Request;

class Product__reviewsController extends Controller
{
public function store(Request $request){
    $request->validate([
        'rating' => 'required|numeric|between:1,5',
        'review' => 'nullable|string|max:255',
    ]);
     // Yeni bir ürün değerlendirmesi oluşturma
     $review = new Product_Reviews();
     $review->product_id = $request->product_id;
     $review->user_id = auth()->user()->id; 
     $review->review = $request->review;
     $review->rating = $request->rating;
     $review->save();

     // Başarılı bir yanıt döndürme
     return response()->json(['message' => 'Ürün değerlendirmesi başarıyla oluşturuldu'], 201);
 }
 
 public function getProductReviews($productId) {
    $reviews = Product_Reviews::with('user')->where('product_id', $productId)->get();

    // Değerlendirme sayısı
    $reviewCount = $reviews->count();

    // Ortalama değerlendirme puanı
    $averageRating = $reviews->avg('rating');

    // Kullanıcı adlarını değerlendirme nesneleriyle birlikte gönder
    $reviewData = [];
    foreach ($reviews as $review) {
        $reviewData[] = [
            'id' => $review->id,
            'product_id' => $review->product_id,
            'user_id' => $review->user_id,
            'review' => $review->review,
            'rating' => $review->rating,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
            'username' => $review->user->name // Kullanıcı adını al
        ];
    }

    return response()->json([
        'reviews' => $reviewData,
        'review_count' => $reviewCount,
        'average_rating' => $averageRating,
    ]);
}





}

//$product=Product::with('product__reviews')->find($id);