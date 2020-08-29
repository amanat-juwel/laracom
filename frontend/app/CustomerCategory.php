<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCategory extends Model
{
    protected $table = 'tbl_customer_category';
    protected $primaryKey = 'id';
    public $timestamps = false;

}
