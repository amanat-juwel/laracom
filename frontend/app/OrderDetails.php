<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $table = 'tbl_order_details';
    protected $primaryKey = 'id';
    public $timestamps = false;
   
}
