<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'REPORTS';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'reportsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'adminID',
        'reportType',
        'reportsDesc',
        'dateGenerated',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dateGenerated' => 'datetime',
    ];

    /**
     * Get the admin who generated the report.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }
}
