<?php

namespace App;

use App\User;
use App\Events\PhoneNumberWasBlacklisted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PhoneNumber extends Model
{
    use formatsNumber;

    protected $fillable = ['number'];

    protected $casts = [
        'is_verified' => 'boolean',
        'blacklisted' => 'boolean'
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
        $this->is_verified = true;
        $this->save();
    }

    public function markBlacklisted()
    {
        $this->blacklisted = true;
        $this->save();
        event(new PhoneNumberWasBlacklisted($this));
    }

    public static function findByNumber($number)
    {
        return self::where('number', $number)->firstOrFail();
    }

    public function scopeVerified($query)
    {
        return $query->where([
            'is_verified' => true,
            'blacklisted' => false
        ]);
    }

    public static function findByTwilioNumber($number)
    {
        $number = str_replace('+1', '', $number);

        return self::where('number', $number)->firstOrFail();
    }

    public static function findVerifiedByTwilioNumber($number)
    {
        $number = str_replace('+1', '', $number);

        return self::where('number', $number)->where('is_verified', true)->firstOrFail();
    }

    public function getStatusAttribute()
    {
        if ($this->blacklisted) {
            return 'blacklisted';
        } elseif ($this->is_verified) {
            return 'verified';
        }

        return 'un-verified';
    }
}
