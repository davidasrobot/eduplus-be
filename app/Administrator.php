<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Administrator extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'uuid', 'email_verified_at', 'active', 'avatar'
    ];
    protected $hidden = [
        'password', 'remember_token', 'active', 'created_at', 'id'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
