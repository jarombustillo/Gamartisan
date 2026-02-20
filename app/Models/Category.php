<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'CATEGORIES';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'categoryID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'catName',
    ];

    /**
     * Get the products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'categoryID', 'categoryID');
    }
}
