<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\FriendWasBlacklisted;

class Friend extends PhoneNumber
{
    protected $fillable = [
        'name',
        'number'
    ];

    public function markBlacklisted()
    {
        $this->setAttribute(PhoneNumber::BLACKLISTED, true)->save();
        event(new FriendWasBlacklisted($this));
    }

    public function recordings()
    {
        throw new \RuntimeException('Friends do not relate to recordings.');
    }
}
