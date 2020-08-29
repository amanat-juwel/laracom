<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesDetails extends Model
{
    protected $table = 'tbl_sales_details';
    protected $primaryKey = 'sales_details_id';
    public $timestamps = false;
    protected $fillable = ['item_id','sales_master_id','stock_location_id','memo_no','quantity','sales_price','item_vat'];
}
