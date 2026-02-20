<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'REVIEWS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'reviewID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'buyerID',
        'productID',
        'revRating',
        'revText',
        'datePosted',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'datePosted' => 'datetime',
    ];

    /**
     * Get the buyer who wrote the review.
     */
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyerID', 'buyerID');
    }

    /**
     * Get the product being reviewed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }
}
