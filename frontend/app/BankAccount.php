<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'tbl_bank_account';
    protected $primaryKey = 'bank_account_id';
    public $timestamps = false;
    protected $fillable = ['bank_name,bank_branch,bank_account,description'];

}
