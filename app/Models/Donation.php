<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'DONATIONS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'donationID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'orderID',
        'charityID',
        'amountDonated',
        'dateDonated',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amountDonated' => 'decimal:2',
        'dateDonated' => 'datetime',
    ];

    /**
     * Get the order for this donation.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'orderID');
    }

    /**
     * Get the charity receiving this donation.
     */
    public function charity()
    {
        return $this->belongsTo(Charity::class, 'charityID', 'charityID');
    }
}
