<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    protected $table = 'tbl_supplier_ledger';
    protected $primaryKey = 'supplier_ledger_id';
    public $timestamps = false;
    protected $fillable = ['purchase_master_id','supplier_id','tran_ref_id','tran_ref_name','debit','credit','transaction_date'];
}
