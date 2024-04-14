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
        'rating' => 'required|integer|between:1,5',
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
}

//$product=Product::with('product__reviews')->find($id);