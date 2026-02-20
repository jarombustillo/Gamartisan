<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'BUYERS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'buyerID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'firstName',
        'midName',
        'lastName',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the buyer's full name.
     */
    public function getFullNameAttribute()
    {
        $middleName = $this->midName ? ' ' . $this->midName : '';
        return $this->firstName . $middleName . ' ' . $this->lastName;
    }

    /**
     * Get the orders for the buyer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyerID', 'buyerID');
    }

    /**
     * Get the reviews by the buyer.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'buyerID', 'buyerID');
    }

    /**
     * Get the notifications for the buyer.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'buyerID', 'buyerID');
    }
}
