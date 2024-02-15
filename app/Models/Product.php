<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Payment;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount',
        'quantity',
        'stock',
        'image'
    ];


    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }


    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

}