<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends PhoneNumber
{
    protected $fillable = [
        'name',
        'number'
    ];
}
