<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_Details extends Model
{
    use HasFactory;

    protected $table = 'sale__details'; 
    public $timestamps = false;

    protected $fillable = [
        'quantity',
        'unitary_price',
        'final_price',
        'sale_id',
        'product_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
