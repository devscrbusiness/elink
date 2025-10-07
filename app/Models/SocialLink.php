<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = [
        'business_id', 'type', 'alias', 'url', 'greeting', 'is_public'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}