<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'name',
        'image',
        'schools_id',
        'facility_id',
        'extracurricular_id',
    ];

    public function Schools()
    {
        return $this->belongsTo('App\School');
    }
    public function Facilities()
    {
        return $this->belongsTo('App\Facility');
    }
    public function Extracurricular()
    {
        return $this->belongsTo('App\Extracurricular');
    }
}
