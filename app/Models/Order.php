<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'ORDERS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'orderID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'buyerID',
        'artistID',
        'productID',
        'ordQuantity',
        'ordTotalPrice',
        'orderDate',
        'ordStatus',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'ordTotalPrice' => 'decimal:2',
        'orderDate' => 'datetime',
    ];

    /**
     * Get the buyer that placed the order.
     */
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyerID', 'buyerID');
    }

    /**
     * Get the artist who received the order.
     */
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artistID', 'artistID');
    }

    /**
     * Get the product in the order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }

    /**
     * Get the payment for the order.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'orderID', 'orderID');
    }

    /**
     * Get the donation associated with the order.
     */
    public function donation()
    {
        return $this->hasOne(Donation::class, 'orderID', 'orderID');
    }

    /**
     * Status badge color mapping.
     */
    public function getStatusColorAttribute()
    {
        return match($this->ordStatus) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'shipped' => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
