<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function Schools()
    {
        return $this->hasMany('App\School');
    }

    public function Province()
    {
        return $this->belongsTo('App\Province');
    }

    public function District()
    {
        return $this->hasMany('App\District');
    }

    public function Favorite()
    {
        return $this->hasManyThrough(Favorite::class, School::class);
    }
}
