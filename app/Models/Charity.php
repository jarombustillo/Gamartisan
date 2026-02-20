<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charity extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'CHARITIES';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'charityID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'charName',
        'charDesc',
        'charConDetails',
        'charStatus',
    ];

    /**
     * Get the donations for this charity.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'charityID', 'charityID');
    }

    /**
     * Get total donations amount.
     */
    public function getTotalDonationsAttribute()
    {
        return $this->donations()->sum('amountDonated');
    }

    /**
     * Check if charity is active.
     */
    public function isAvailable()
    {
        return $this->charStatus === 'available';
    }
}
