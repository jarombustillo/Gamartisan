<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'NOTIFICATIONS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'notificationID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'buyerID',
        'notifType',
        'notifMess',
        'dateSent',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dateSent' => 'datetime',
    ];

    /**
     * Get the buyer for this notification.
     */
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyerID', 'buyerID');
    }
}
