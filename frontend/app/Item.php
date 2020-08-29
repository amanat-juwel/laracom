<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'tbl_item';
    protected $primaryKey = 'item_id';
    public $timestamps = false;
    protected $fillable = ['item_name'];
}
