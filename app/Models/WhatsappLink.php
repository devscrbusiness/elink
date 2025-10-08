<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'country_id',
        'phone_number',
        'alias',
        'custom_slug',
        'greeting',
        'is_public',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the full WhatsApp URL.
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                $fullPhone = $this->country->phone_code . $this->phone_number;
                $url = "https://api.whatsapp.com/send?phone={$fullPhone}";
                return $this->greeting ? $url . '&text=' . urlencode($this->greeting) : $url;
            }
        );
    }
}