<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function Schools()
    {
        return $this->hasMany('App\School');
    }

    public function Regency()
    {
        return $this->hasMany('App\Regency');
    }

    public function Favorite()
    {
        return $this->hasManyThrough(Favorite::class, School::class);
    }
}
