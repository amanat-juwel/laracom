<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'tbl_purchase_master';
    protected $primaryKey = 'purchase_master_id';
    public $timestamps = false;
    protected $fillable = ['supplier_id','memo_no','memo_total','advanced_amount','discount','purchase_date','status'];
}
