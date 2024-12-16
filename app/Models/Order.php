<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'driver_id',
        'cart_id',
        'bill',
        'status',
        'delivery_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
