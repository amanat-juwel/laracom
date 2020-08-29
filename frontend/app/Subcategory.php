<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = 'tbl_sub_category';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id','name'];

    // public function item()
    // {
    //     return $this->belongsTo('App\Item');
    // }
}
