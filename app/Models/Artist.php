<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Artist extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'artists';

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
     * The buyer profile this artist's own purchases are filed under.
     */
    public function buyerProfile()
    {
        return $this->belongsTo(Buyer::class, 'buyerID', 'buyerID');
    }

    /**
     * The buyerID to place this artist's orders under, creating the linked
     * buyer profile on first use. It cannot be logged into directly.
     */
    public function purchasingBuyerID(): int
    {
        if ($this->buyerID) {
            return (int) $this->buyerID;
        }

        return DB::transaction(function () {
            // Lock the artist row so two first purchases at once create one profile.
            $artist = static::whereKey($this->artistID)->lockForUpdate()->first();

            if (!$artist->buyerID) {
                // Reuse the artist's email unless a separate buyer account owns it.
                $email = Buyer::where('email', $artist->email)->exists()
                    ? "seller-{$artist->artistID}@sellers.gamartisan.invalid"
                    : $artist->email;

                $buyer = Buyer::create([
                    'firstName' => $artist->firstName,
                    'midName' => $artist->midName,
                    'lastName' => $artist->lastName,
                    'email' => $email,
                    'password' => Hash::make(Str::random(64)),
                    'status' => 'active',
                ]);

                $artist->buyerID = $buyer->buyerID;
                $artist->save();
            }

            $this->buyerID = $artist->buyerID;

            return (int) $artist->buyerID;
        });
    }

    /**
     * Get the orders received by the artist.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'artistID', 'artistID');
    }
}
