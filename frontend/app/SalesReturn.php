<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    protected $table = 'tbl_sales_return_master';
    protected $primaryKey = 'sales_return_master_id';
    public $timestamps = false;
    protected $fillable = ['customer_id','memo_total','advanced_amount','date'];
}
