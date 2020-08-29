<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMaster extends Model
{
    protected $table = 'tbl_stock_master';
    protected $primaryKey = 'id';
    public $timestamps = false;


    // public function getStockLocationName(){

    // 	return $this->belongsTo(StockLocation::class, 'stock_location_id');
    // }
}
