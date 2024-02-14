<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipping_address',
        'billing_address',
        'payment_method',
        'credit_card_number',
        'credit_card_expiration',
        'credit_card_cvv',
        'shipping_method',
        'shipping_cost',
        'estimated_delivery_date',
        'special_instructions',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'customer_email',
        'customer_phone',
        'customer_name',
        'newsletter_subscription',
        'terms_accepted',
        'order_notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}