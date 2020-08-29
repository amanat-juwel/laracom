<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    protected $table = 'tbl_item_unit';
    protected $primaryKey = 'tbl_item_unit_id';
    public $timestamps = false;
    protected $fillable = ['tbl_item_unit_id'];
}
