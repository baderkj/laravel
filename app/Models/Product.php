<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        "name","type","price","quantity"];

       
       protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            $model->old_quantity = $model->getOriginal('quantity');
        });
    }

 
    public function getOldQuantity()
    {
        return $this->old_quantity ?? null;
    }
    public function store()
    {
        return $this->belongsTo(Store::class);    
    }

}
