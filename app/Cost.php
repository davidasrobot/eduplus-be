<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $table = 'costs';
    protected $fillable = [
        'school_id',
        'etf_cost',
        'spp_cost',
        'activities_cost',
        'book_cost',
        'discount'
    ];

    protected $hidden = [
        'school_id', 'id', 'created_at'
    ];

    public function School()
    {
        return $this->belongsTo(School::class);
    }
}
