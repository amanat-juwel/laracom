<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'tbl_orders_history';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
}
