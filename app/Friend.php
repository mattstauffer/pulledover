<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends PhoneNumber
{
    use formatsNumber;

    protected $fillable = [
        'name',
        'number'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];
}
