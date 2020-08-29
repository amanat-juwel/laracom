<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'tbl_sales_master';
    protected $primaryKey = 'sales_master_id';
    public $timestamps = false;
    protected $fillable = ['customer_id','memo_no','memo_total','advanced_amount','discount','sales_date','status'];
}
