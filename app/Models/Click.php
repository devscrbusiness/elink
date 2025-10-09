<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    protected $fillable = [
        'clickable_id',
        'clickable_type',
    ];

    public function clickable()
    {
        return $this->morphTo();
    }
}