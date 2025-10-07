<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id',
        'latitude',
        'longitude',
        'detail',
    ];

    /**
     * Get the business that owns the location.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}