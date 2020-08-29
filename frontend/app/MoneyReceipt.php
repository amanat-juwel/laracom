<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoneyReceipt extends Model
{
    protected $table = 'money_receipts';
    protected $primaryKey = 'mr_id';
    public $timestamps = false;


}
