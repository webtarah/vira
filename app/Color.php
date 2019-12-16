<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{

    public $timestamps = false;

    protected $fillable = ['name','hex_color',];
}
