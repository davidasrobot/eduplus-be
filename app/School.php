<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';
    protected $fillable = [
        'uuid',
        'npsn',
        'name',
        'address',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
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
        'publish'
    ];

    protected $hidden = [
        'id', 'created_at', 'publish',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
    ];

    public function User()
    {
        return $this->hasOne(User::class, 'school_id');
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
        return $this->hasOne(Cost::class);
    }
    public function Facilities()
    {
        return $this->hasMany(Facility::class);
    }
    public function Majors()
    {
        return $this->hasMany(Major::class);
    }
    public function Extracurricular()
    {
        return $this->hasMany(Extracurricular::class);
    }
    public function Registration()
    {
        return $this->hasOne(RegistrationDate::class);
    }


    public function Favorite()
    {
        return $this->hasOne(Favorite::class);
    }
    public function pending()
    {
        return $this->hasMany(PendingSchool::class);
    }
}
