<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'name',
        'number'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    public function markVerified()
    {
        $this->is_verified = true;
        $this->save();
    }
}
