<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'cartItemID';
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'userType',
        'productID',
        'quantity',
        'dateAdded',
    ];

    protected $casts = [
        'dateAdded' => 'datetime',
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }
}
