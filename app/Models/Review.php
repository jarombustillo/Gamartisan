<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'reviewID';
    public $timestamps = false;

    protected $fillable = [
        'productID',
        'buyerID',
        'orderID',
        'revRating',
        'revText',
        'datePosted',
    ];

    protected $casts = [
        'datePosted' => 'datetime',
        'revRating' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyerID', 'buyerID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'orderID');
    }
}
