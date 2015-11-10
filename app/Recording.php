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
}
