<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // One-to-Many relation with Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Many-to-Many relation with Products for Wishlist
    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists');
    }

    // One-to-Many relation with Reviews
    public function reviews()
    {
        return $this->hasMany(Product_Reviews::class);
    }

    // One-to-Many relation with ShippingAddresses
    public function shippingAddresses()
    {
        return $this->hasMany(Shipping_Addresses::class);
    }
    public function profile(){
     return $this->hasOne(Profile::class);
    }
}
