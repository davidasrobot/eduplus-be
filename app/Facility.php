<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';
    protected $fillable = [
        'name',
        'school_id'
    ];

    protected $hidden = [
        'school_id', 'created_at', 'updated_at'
    ];

    public function School()
    {
        return $this->belongsTo(School::class);
    }
}
