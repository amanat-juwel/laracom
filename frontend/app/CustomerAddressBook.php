<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddressBook extends Model
{
    protected $table = 'tbl_customer_address_books';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
}
