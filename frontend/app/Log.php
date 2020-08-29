<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // public function heads(){
    // 	return $this->hasMany(IncomeHead::class);
    // }
    
}
