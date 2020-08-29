<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'tbl_unit';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
