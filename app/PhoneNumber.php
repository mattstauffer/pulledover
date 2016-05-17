<?php

namespace App;

use App\User;
use App\Events\PhoneNumberWasBlacklisted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PhoneNumber extends Model
{
    use formatsNumber;

    const VERIFIED = 'is_verified';
    const BLACKLISTED = 'blacklisted';

    protected $fillable = ['number'];

    protected $casts = [
        self::VERIFIED => 'boolean',
        self::BLACKLISTED => 'boolean'
    ];

    public $appends = [
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    public function markVerified()
    {
        $this->setAttribute(self::VERIFIED, true)->save();
    }

    public function markBlacklisted()
    {
        $this->setAttribute(self::BLACKLISTED, true)->save();
        event(new PhoneNumberWasBlacklisted($this));
    }

    public static function findByNumber($number)
    {
        return self::where('number', $number)->firstOrFail();
    }

    public static function findByTwilioNumber($number)
    {
        $number = str_replace('+1', '', $number);

        return self::where('number', $number)->firstOrFail();
    }

    public static function findVerifiedByTwilioNumber($number)
    {
        $number = str_replace('+1', '', $number);

        return self::where('number', $number)->where(self::VERIFIED, true)->firstOrFail();
    }

    public function scopeVerified($query)
    {
        return $query->where([
            self::VERIFIED => true,
            self::BLACKLISTED => false
        ]);
    }

    public function getStatusAttribute()
    {
        if ($this->getAttribute(self::BLACKLISTED)) {
            return 'blacklisted';
        } elseif ($this->getAttribute(self::VERIFIED)) {
            return 'verified';
        }

        return 'unverified';
    }
}
