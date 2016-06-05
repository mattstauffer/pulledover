<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PhoneNumber extends Model
{
    use formatsNumber;

    protected $fillable = ['number'];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_blacklisted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markVerified()
    {
        $this->is_verified = true;
        $this->save();
    }

    public function markBlacklisted()
    {
        $this->is_blacklisted = true;
        $this->save();
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
}
