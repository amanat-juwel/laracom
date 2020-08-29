<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'tbl_supplier';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;
    protected $fillable = ['sup_name','sup_address','sup_phone_no','opening_balance'];
}
