<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'tbl_brand';
    protected $primaryKey = 'brand_id';
    public $timestamps = false;
    protected $fillable = ['brand_id','brand_name'];

    // public function item()
    // {
    //     return $this->belongsTo('App\Item');
    // }
}
