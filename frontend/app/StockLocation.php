<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockLocation extends Model
{
    protected $table = 'tbl_stock_location';
    protected $primaryKey = 'stock_location_id';
    public $timestamps = false;
    protected $fillable = ['stock_location_name'];
}
