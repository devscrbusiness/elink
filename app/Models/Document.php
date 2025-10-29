<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'name', 'path'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get all of the document's clicks.
     */
    public function clicks(): MorphMany
    {
        return $this->morphMany(Click::class, 'clickable');
    }
}