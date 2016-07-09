<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use formatsNumber;

    protected $fillable = ['number'];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_blacklisted' => 'boolean',
    ];

    public $appends = ['status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markVerified()
    {
        $this->is_verified = true;
        $this->save();
    }

    public function markBlacklisted($value = true)
    {
        $this->is_blacklisted = $value;
        $this->save();
    }

    public function addToBlacklist()
    {
        return $this->markBlacklisted();
    }

    public function removeFromBlacklist()
    {
        return $this->markBlacklisted(false);
    }

    public function scopeVerified(Builder $builder)
    {
        return $builder->where('is_verified', true);
    }

    public function scopeBlacklisted(Builder $builder, $value = true)
    {
        return $builder->where('is_blacklisted', $value);
    }

    public function scopeByNumber(Builder $builder, $number)
    {
        return $builder->where('number', str_replace('+1', '', $number));
    }

    public static function findByNumber($number)
    {
        return self::byNumber($number)->firstOrFail();
    }
    
    public static function findVerifiedByTwilioNumber($number)
    {
        return self::byNumber($number)->verified()->firstOrFail();
    }

    public function getStatusAttribute()
    {
        return $this->is_blacklisted ? 'blacklisted' : ($this->is_verified ? 'verified' : 'unverified');
    }
}
