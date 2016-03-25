<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\FriendWasBlacklisted;

class Friend extends Model
{
    use formatsNumber;

    protected $fillable = [
        'name',
        'number'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'blacklisted' => 'boolean'
    ];

    public $appends = [
        'status'
    ];

    public function markVerified()
    {
        $this->is_verified = true;
        $this->save();
    }

    public function markBlacklisted()
    {
        $this->blacklisted = true;
        $this->save();
        event(new FriendWasBlacklisted($this));
    }

    public function scopeVerified($query)
    {
        return $query->where([
            'is_verified' => true,
            'blacklisted' => false
        ]);
    }

    public function getStatusAttribute()
    {
        if ($this->blacklisted) {
            return 'blacklisted';
        } elseif ($this->is_verified) {
            return 'verified';
        }

        return 'un-verified';
    }
}
