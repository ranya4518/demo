<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //bulunan tüm categoryleri görüntülenme
    public function index(){
        $product=Category::all();
        return response()->json(['categories'=>$product]);
    }
    // bire categorye ait tüm ürünleri elde edilir
    public function show($id){
    $category=Category::with('products')->find($id);
    return response()->json($category);
    }
}
