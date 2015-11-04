<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    protected $fillable = ['number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findByNumber($number)
    {
        return self::where('number', $number)->firstOrFail();
    }
}
