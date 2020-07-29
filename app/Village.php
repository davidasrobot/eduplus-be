<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function Schools()
    {
        return $this->hasMany('App\School', 'village_id');
    }

    public function District()
    {
        return $this->belongsTo('App\District');
    }

    public function Favorite()
    {
        return $this->hasManyThrough(Favorite::class, School::class);
    }
}
