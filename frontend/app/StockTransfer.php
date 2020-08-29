<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $table = 'stock_transfers';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['from_stock_id','to_stock_id','from_stock_location_id','to_stock_location_id','item_id','quantity','date','user_id'];

    public function getUserName(){

    	return $this->belongsTo(User::class, 'user_id');
    }

    public function getItemName(){

    	return $this->belongsTo(Item::class, 'item_id');
    }

    public function getWareHouseFrom(){

    	return $this->belongsTo(StockLocation::class, 'from_stock_location_id');
    }

    public function getWareHouseTo(){

    	return $this->belongsTo(StockLocation::class, 'to_stock_location_id');
    }
}
