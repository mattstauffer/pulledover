<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $fillable = [
        'from',
        'city',
        'state',
        'url',
        'recording_sid',
        'duration',
        'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phone_number()
    {
        return $this->belongsTo(PhoneNumber::class);
    }

    public function getFormattedFromAttribute()
    {
        return sprintf(
            '(%s) %s-%s',
            substr($this->from, 2, 3),
            substr($this->from, 5, 3),
            substr($this->from, 8)
        );
    }
}
