<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Driver extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory,HasApiTokens;

    


    protected $fillable = [
        "name",
        "phone",
        "available",
        "password"
    ];
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}

}
