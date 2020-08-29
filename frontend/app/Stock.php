<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'tbl_stock';
    protected $primaryKey = 'stock_id';
    public $timestamps = false;
    protected $fillable = ['item_id','stock_location_id','purchase_details_id','sales_details_id','stock_in','stock_out','stock_change_date','note'];

    public function getStockLocationName(){

    	return $this->belongsTo(StockLocation::class, 'stock_location_id');
    }
}
