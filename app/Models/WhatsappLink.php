<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WhatsappLink extends Model
{
    protected $fillable = [
        'business_id', 'country_id', 'phone_number', 'custom_slug', 'alias', 'greeting', 'is_public'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function clicks(): MorphMany
    {
        return $this->morphMany(Click::class, 'clickable');
    }

    /**
     * Get the full WhatsApp URL.
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                $phoneNumber = preg_replace('/\D/', '', $this->phone_number);
                $fullPhone = $this->country->phone_code . $phoneNumber;

                $url = "https://api.whatsapp.com/send?phone={$fullPhone}";

                if ($this->greeting) {
                    return $url . '&text=' . urlencode($this->greeting);
                }

                return $url;
            }
        );
    }
}