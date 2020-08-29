<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $table = 'tbl_purchase_return_master';
    protected $primaryKey = 'purchase_return_master_id';
    public $timestamps = false;
    protected $fillable = ['supplier_id','memo_total','advanced_amount','date'];
}
