<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'NOTIFICATIONS';
    protected $primaryKey = 'notificationID';
    public $timestamps = false;

    protected $fillable = [
        'buyerID',
        'artistID',
        'adminID',
        'notifType',
        'notifMess',
        'dateSent',
        'isRead',
    ];

    protected $casts = [
        'dateSent' => 'datetime',
        'isRead' => 'boolean',
    ];

    /**
     * Helper to create a notification.
     */
    public static function send($type, $message, $targets = [])
    {
        $base = [
            'notifType' => $type,
            'notifMess' => $message,
            'dateSent' => now(),
            'isRead' => false,
        ];

        // If targets has multiple recipients of the same type, create one per recipient
        // targets format: ['buyerID' => 1, 'artistID' => 2] or arrays
        if (isset($targets['buyerID'])) {
            $ids = is_array($targets['buyerID']) ? $targets['buyerID'] : [$targets['buyerID']];
            foreach ($ids as $id) {
                self::create(array_merge($base, ['buyerID' => $id]));
            }
        }

        if (isset($targets['artistID'])) {
            $ids = is_array($targets['artistID']) ? $targets['artistID'] : [$targets['artistID']];
            foreach ($ids as $id) {
                self::create(array_merge($base, ['artistID' => $id]));
            }
        }

        if (isset($targets['adminID'])) {
            $ids = is_array($targets['adminID']) ? $targets['adminID'] : [$targets['adminID']];
            foreach ($ids as $id) {
                self::create(array_merge($base, ['adminID' => $id]));
            }
        }
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyerID', 'buyerID');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artistID', 'artistID');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }
}
