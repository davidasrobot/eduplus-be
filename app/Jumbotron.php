<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jumbotron extends Model
{
    protected $fillable = [
        'background', 'image', 'heading', 'text'
    ];
}
