<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationDate extends Model
{
    protected $fillable = [
        'school_id', 'registration', 'annoucement', 're_registration'
    ];

    protected $hidden = [
        'school_id', 'id', 'created_at'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
