<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = [
        'business_id', 'type', 'alias', 'url', 'greeting', 'is_public', 'position'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function clicks()
    {
        // Un enlace social puede tener muchos clics.
        // El segundo argumento es el nombre de la relación polimórfica ('clickable').
        return $this->morphMany(Click::class, 'clickable');
    }
}