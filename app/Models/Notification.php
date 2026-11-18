<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Notifications for the logged-in user, or null when nobody is logged in.
     * An artist also gets those sent to their linked buyer profile (their purchases).
     */
    public static function forCurrentUser(): ?Builder
    {
        $id = session('user_id');

        return match (session('user_type')) {
            'buyer' => $id ? self::where('buyerID', $id) : null,
            'admin' => $id ? self::where('adminID', $id) : null,
            'artist' => $id ? self::where(function ($q) use ($id) {
                $q->where('artistID', $id);
                $buyerID = Artist::whereKey($id)->value('buyerID');
                if ($buyerID) {
                    $q->orWhere('buyerID', $buyerID);
                }
            }) : null,
            default => null,
        };
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
