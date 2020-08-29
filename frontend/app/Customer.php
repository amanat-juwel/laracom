<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'tbl_customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
    protected $fillable = ['customer_id','customer_name','mobile_no','customer_nid','email','address','passport','customer_tin','driving_license','customer_image'];
}
