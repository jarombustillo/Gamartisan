<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
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

        // One row per recipient, written in a single INSERT.
        // targets format: ['buyerID' => 1, 'artistID' => 2] or arrays of IDs
        $rows = [];
        foreach (['buyerID', 'artistID', 'adminID'] as $column) {
            if (!isset($targets[$column])) {
                continue;
            }
            foreach ((array) $targets[$column] as $id) {
                $rows[] = $base + ['buyerID' => null, 'artistID' => null, 'adminID' => null, $column => $id];
            }
        }

        if ($rows) {
            self::insert($rows);
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
