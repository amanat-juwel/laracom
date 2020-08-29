<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    protected $table = 'tbl_purchase_details';
    protected $primaryKey = 'purchase_details_id';
    public $timestamps = false;
    protected $fillable = ['item_id','purchase_master_id','stock_location_id','memo_no','quantity','purchase_price','item_vat'];
}
