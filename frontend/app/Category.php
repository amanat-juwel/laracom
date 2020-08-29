<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_category';
    protected $primaryKey = 'cata_id';
    public $timestamps = false;
    protected $fillable = ['cata_id','cata_name','cata_details'];

    // public function item()
    // {
    //     return $this->belongsTo('App\Item');
    // }
}
