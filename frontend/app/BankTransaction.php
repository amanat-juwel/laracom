<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $table = 'tbl_bank_transaction';
    protected $primaryKey = 'bank_transaction_id';
    public $timestamps = false;
    protected $fillable = ['bank_account_id,transaction_description,deposit,expense'];

}
