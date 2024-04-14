<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // One-to-Many relation with OrderDetails
    public function orderDetails()
    {
        return $this->hasMany(Order_Details::class);
    }

    // Many-to-Many relation with Categories
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // One-to-Many relation with Reviews
    public function product__reviews()
    {
        return $this->hasMany(Product_Reviews::class)->with('user');
    }

    // Many-to-Many relation with Users for Wishlist
    public function wishlistUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }
}
