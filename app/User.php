<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function phoneNumbers()
    {
        return $this->hasMany(\App\PhoneNumber::class);
    }

    public function friends()
    {
        return $this->hasMany(\App\Friend::class);
    }

    public function recordings()
    {
        return $this->hasMany(\App\Recording::class);
    }
}
