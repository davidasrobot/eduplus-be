<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingSchool extends Model
{
    protected $table = 'pending_schools';
    protected $fillable = [
        'school_id',
        'uuid',
        'npsn',
        'name',
        'address',
        'province',
        'city',
        'district',
        'village',
        'postal_code',
        'phone_number',
        'email',
        'website',
        'curriculum',
        'headmaster',
        'schools_hour',
        'total_student',
        'accreditation',
        'status',
        'educational_stage',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
    ];

    protected $hidden = [
        'id', 'created_at', 'publish'
    ];

    public function User()
    {
        return $this->hasOne(User::class, 'school_id');
    }

    public function School()
    {
        return $this->belongsTo(School::class);
    }

    public function Province()
    {
        return $this->belongsTo('App\Province', 'province_id');
    }

    public function Regency()
    {
        return $this->belongsTo('App\Regency');
    }

    public function District()
    {
        return $this->belongsTo('App\District');
    }

    public function Village()
    {
        return $this->belongsTo('App\Village');
    }

    public function Images()
    {
        return $this->hasMany(Image::class, 'schools_id');
    }
    public function Costs()
    {
        return $this->hasMany('App\Cost', 'schools_id');
    }
    public function Facilities()
    {
        return $this->hasMany('App\Facility', 'schools_id');
    }
    public function Majors()
    {
        return $this->hasMany('App\Major', 'schools_id');
    }
    public function Extracurricular()
    {
        return $this->hasMany('App\Extracurricular', 'schools_id');
    }
    public function Favorite()
    {
        return $this->hasOne(Favorite::class);
    }
}
