<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'PAYMENTS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'paymentID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'orderID',
        'payAmount',
        'payMethod',
        'payStatus',
        'datePaid',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'payAmount' => 'decimal:2',
        'datePaid' => 'datetime',
    ];

    /**
     * Get the order for this payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'orderID');
    }
}
