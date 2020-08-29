<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    protected $table = 'tbl_customer_ledger';
    protected $primaryKey = 'customer_ledger_id';
    public $timestamps = false;
    protected $fillable = ['sales_master_id','customer_id','tran_ref_id','tran_ref_name','debit','credit','transaction_date'];

    public function getMemo()
    {
        return $this->belongsTo(Sales::class,'sales_master_id');
    }

}
