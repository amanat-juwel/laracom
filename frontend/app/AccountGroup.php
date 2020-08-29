<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $table = 'tbl_account_group';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['name'];

    // public function heads(){
    // 	return $this->hasMany(IncomeHead::class);
    // }
    
}
