<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccountLedger extends Model
{
    protected $table = 'tbl_bank_account_ledger';
    protected $primaryKey = 'bank_account_ledger_id';
    public $timestamps = false;
    protected $fillable = ['bank_account_id,date,debit,credit'];

}
