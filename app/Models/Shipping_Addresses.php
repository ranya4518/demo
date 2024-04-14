<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping_Addresses extends Model
{
    use HasFactory;
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Many-to-One relation with User
    public function user()
    {
        return $this->belongsTo(User::class);
        
    }
    protected $fillable = ['user_id','first_name', 'last_name','address_title' ,'address_line_1', 
    'address_line_2', 'city', 'state', 'country', 'zip_code'];
}
