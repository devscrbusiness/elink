<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'phone_code', 'iso_code'];

    /**
     * Get the country's flag emoji.
     */
    protected function flagEmoji(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->iso_code) {
                    return '';
                }
                $regionalOffset = 0x1F1E6 - ord('A');
                return mb_convert_encoding('&#' . (ord($this->iso_code[0]) + $regionalOffset) . ';', 'UTF-8', 'HTML-ENTITIES')
                     . mb_convert_encoding('&#' . (ord($this->iso_code[1]) + $regionalOffset) . ';', 'UTF-8', 'HTML-ENTITIES');
            }
        );
    }
}