<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'logo', 'website', 'custom_link'
    ];

    /**
     * Get the user that owns the business.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the social links for the business.
     */
    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get all of the clicks for the business through its links.
     */
    public function clicks(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Click::class, SocialLink::class, 'business_id', 'clickable_id')->where('clickable_type', SocialLink::class);
    }

    /**
     * Get the WhatsApp links for the business.
     */
    public function whatsappLinks(): HasMany
    {
        return $this->hasMany(WhatsappLink::class);
    }

    /**
     * Get the subscriptions for the business.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the location for the business.
     */
    public function location()
    {
        return $this->hasOne(Location::class);
    }
}