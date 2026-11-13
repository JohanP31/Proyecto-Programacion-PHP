<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';
    public $timestamps = false;

    protected $fillable = [
        'payment_method',
        'amount',
        'status',
        'sale_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }
}
