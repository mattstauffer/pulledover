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
        'is_verified' => 'boolean'
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

    public static function findByNumber($number)
    {
        return self::where('number', $number)->firstOrFail();
    }

    public function scopeVerified(Builder $builder)
    {
        return $builder->where('is_verified', true);
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
}
