<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'ARTISTS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'artistID';

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
        'artPass',
        'artBio',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'artPass',
    ];

    /**
     * Get the artist's full name.
     */
    public function getFullNameAttribute()
    {
        $middleName = $this->midName ? ' ' . $this->midName : '';
        return $this->firstName . $middleName . ' ' . $this->lastName;
    }

    /**
     * Get the products by the artist.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'artistID', 'artistID');
    }

    /**
     * Get the orders received by the artist.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'artistID', 'artistID');
    }
}
