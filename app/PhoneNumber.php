<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
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
}
