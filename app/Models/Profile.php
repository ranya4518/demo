<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use User;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'birthdate',
        'phone',
        'fcm_token'
    ];

public function user(){
    return $this->belongsTo(User::class);
}
}
