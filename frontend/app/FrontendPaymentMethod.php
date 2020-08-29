<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrontendPaymentMethod extends Model
{
    protected $table = 'frontend_payment_methods';
    protected $primaryKey = 'id';
    public $timestamps = false;
   
}
