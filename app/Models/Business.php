<?php
namespace App\Models;

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