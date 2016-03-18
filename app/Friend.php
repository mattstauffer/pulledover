<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model implements ReceivesTextMessages
{
    use formatsNumber;

    protected $fillable = [
        'name',
        'number'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    public $appends = [
        'status'
    ];

    public function markVerified()
    {
        $this->is_verified = true;
        $this->save();
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
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
