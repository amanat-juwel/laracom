<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_orders';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
}
