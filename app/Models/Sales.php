<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';
    public $timestamps = false;

    protected $fillable = [
        'sale_date',
        'total',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(Sale_Details::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'sale_id');
    }
}
