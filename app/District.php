<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function Schools()
    {
        return $this->hasMany('App\School', 'district_id');
    }

    public function Regency()
    {
        return $this->belongsTo('App\Regency');
    }

    public function Village()
    {
        return $this->hasMany('App\Village');
    }

    public function Favorite()
    {
        return $this->hasManyThrough(Favorite::class, School::class);
    }
}
