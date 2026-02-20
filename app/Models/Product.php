<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'PRODUCTS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'productID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'artistID',
        'categoryID',
        'prodName',
        'prodDesc',
        'prodPrice',
        'prodImage',
        'prodStatus',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'prodPrice' => 'decimal:2',
    ];

    /**
     * Get the artist that owns the product.
     */
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artistID', 'artistID');
    }

    /**
     * Get the category of the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryID', 'categoryID');
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'productID', 'productID');
    }

    /**
     * Get the orders for the product.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'productID', 'productID');
    }

    /**
     * Check if product is available.
     */
    public function isAvailable()
    {
        return $this->prodStatus === 'available';
    }
}
