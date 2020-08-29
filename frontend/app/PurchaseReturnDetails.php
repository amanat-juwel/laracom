<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetails extends Model
{
    protected $table = 'tbl_purchase_return_details';
    protected $primaryKey = 'purchase_return_details_id';
    public $timestamps = false;
    protected $fillable = ['item_id','quantity'];
}
