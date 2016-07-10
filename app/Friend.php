<?php

namespace App;

use App\Events\FriendWasBlacklisted;
use Illuminate\Database\Eloquent\Model;

class Friend extends PhoneNumber
{
    protected $fillable = [
        'name',
        'number'
    ];

    public function addToBlacklist()
    {
        $this->markBlacklisted();
        event(new FriendWasBlacklisted($this));
    }
}
