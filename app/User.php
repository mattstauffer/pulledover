<?php

namespace App;

use App\Friend;
use App\PhoneNumber;
use App\Recording;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'dismissed_welcome' => 'boolean'
    ];

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function friends()
    {
        return $this->hasMany(Friend::class);
    }

    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    public static function findByPhoneNumber($number)
    {
        return PhoneNumber::where('number', $number)->firstOrFail()->user;
    }

    public function hasAVerifiedPhoneNumber()
    {
        return $this->phoneNumbers()->verified()->count() > 0;
    }

    public function isAdmin()
    {
        return $this->role === 42;
    }
}
