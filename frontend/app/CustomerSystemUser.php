<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSystemUser extends Model
{
    protected $table = 'tbl_customer_system_user';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
}
