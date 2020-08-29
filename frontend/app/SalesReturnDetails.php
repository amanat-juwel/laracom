<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReturnDetails extends Model
{
    protected $table = 'tbl_sales_return_details';
    protected $primaryKey = 'sales_return_details_id';
    public $timestamps = false;
    protected $fillable = ['item_id','quantity'];
}
