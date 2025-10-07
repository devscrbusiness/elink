<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'business_id', 'plan', 'starts_at', 'ends_at', 'active'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}