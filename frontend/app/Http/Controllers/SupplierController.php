<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;
use App\Supplier;
use App\SupplierLedger;
use App\BankTransaction;
use App\BankAccountLedger;
use App\Voucher;
use App\JournalAmount;
use DB;
use Image;
use Storage;
use Validator;
use Auth;

class SupplierController extends Controller
{   
    

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function tableName(){

        $obj = new Supplier;
        return $table_name = $obj->getTable();
    }

    public function index()
    {   
        /* START GET USER PERMISSION  */

        $suppliers = DB::table('tbl_supplier')
        ->leftJoin('tbl_supplier_ledger','tbl_supplier_ledger.supplier_id','=','tbl_supplier.supplier_id')
        ->selectRaw('tbl_supplier.*,SUM(credit) as credit,SUM(debit) as debit')
        ->groupBy('tbl_supplier.supplier_id')
        ->orderBy('sup_name', 'asc')
        ->get();

        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();

        return view('admin.supplier.index', compact('suppliers','accounts'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(Request $request)
    {   

        $supplier = new Supplier();

        $supplier->sup_name = $request->sup_name;
        $supplier->sup_address = $request->sup_address;
        $supplier->sup_phone_no = $request->sup_phone_no;
        $supplier->sup_email = $request->sup_email;     
        $supplier->note = $request->note;
        ($request->debit!='')?$supplier->op_bal_debit = $request->debit : $x=1;
        ($request->credit!='')?$supplier->op_bal_credit = $request->credit : $x=1;
        $supplier->save();

        
        return redirect('admin/supplier')->with('success','New Supplier Added');
    }


    public function show($id)
    {
        $supplierById = DB::table('tbl_supplier')
        ->select('tbl_supplier.*')
        ->where('supplier_id', $id)
        ->first();
        
        return view('admin.supplier.show',['supplierById'=>$supplierById]);
    }


    public function edit($id)
    {   

        $suppliers = Supplier::find($id);
        return view('admin.supplier.edit',compact('suppliers'))->with('id',$id);

        
    }


    public function update(Request $request, $id)
    {
        
        $suppliers = Supplier::find($id);
        
        $suppliers->sup_name = $request->input('sup_name');
        $suppliers->sup_address = $request->input('sup_address');
        $suppliers->sup_phone_no = $request->input('sup_phone_no');
        $suppliers->sup_email = $request->input('sup_email');
        $suppliers->note = $request->input('note');
        ($request->debit!='')?$suppliers->op_bal_debit = $request->debit : $x=1;
        ($request->credit!='')?$suppliers->op_bal_credit = $request->credit : $x=1;

        $suppliers->update();

       
        return redirect('admin/supplier')->with('update','Supplier Info Updated');
    }


    public function destroy($id)
    {   
        $count = DB::table('tbl_supplier_ledger')
        ->where('supplier_id',$id)
        ->count();
        
        if($count==0){
           $suppliers = Supplier::find($id);
            $suppliers->delete();

            return redirect('admin/supplier')->with('delete','Supplier Deleted');
        }
        else{
            return redirect()->back()->with('delete','Supplier is in use!');
        }

    }

    public function paymentIndex()
    {   

        $suppliers = DB::table('tbl_supplier')
        ->leftJoin('tbl_supplier_ledger','tbl_supplier_ledger.supplier_id','=','tbl_supplier.supplier_id')
        ->selectRaw('tbl_supplier.*,SUM(credit) as credit,SUM(debit) as debit')
        ->groupBy('tbl_supplier.supplier_id')
        ->orderBy('sup_name', 'asc')
        ->get();

        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();

        $supplier_payments = DB::table('tbl_supplier')
        ->leftJoin('tbl_supplier_ledger','tbl_supplier_ledger.supplier_id','=','tbl_supplier.supplier_id')
        ->leftJoin('vouchers','vouchers.voucher_ref','=','tbl_supplier_ledger.voucher_ref')
        ->leftJoin('tbl_bank_transaction','tbl_bank_transaction.voucher_ref','=','vouchers.voucher_ref')
        ->leftJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_supplier.*,tbl_supplier_ledger.*,tbl_bank_account.bank_name')
        ->where('debit','>',0)
        ->orderBy('transaction_date', 'dsc')
        ->get();

        return view('admin.supplier.payment-index', compact('suppliers','accounts','supplier_payments'));
    }

    public function transaction(Request $request)
    {   

        $voucher = DB::table('vouchers')->orderBy('id', 'DESC')->first();
        if($voucher!=NULL){
            $voucher_ref = ++$voucher->id;
        }
        else{
            $voucher_ref = "1";   
        }

        $voucher = new Voucher;
        $voucher->voucher_ref = $voucher_ref;
        $voucher->type = "Journal";
        $voucher->voucher_description =  $request->description;
        $voucher->date = $request->date;
        $voucher->save();

        $journal_amount = new JournalAmount;
        $journal_amount->voucher_ref = $voucher_ref;
        $journal_amount->amount = $request->amount;
        $journal_amount->save();

        $supplier_ledger = new SupplierLedger;
        $supplier_ledger->purchase_master_id = 0;
        $supplier_ledger->supplier_id = $request->input('supplier_id');
        $supplier_ledger->tran_ref_id = 7;
        $supplier_ledger->tran_ref_name = 'PreviousDuePaid';
        $supplier_ledger->particulars = $request->description;
        $supplier_ledger->debit = $request->amount;
        $supplier_ledger->credit = 0;
        $supplier_ledger->voucher_ref = $voucher_ref;
        $supplier_ledger->transaction_date = $request->date;
        $supplier_ledger->save();

        # Start Cash/Bank Transaction
        $transaction = new BankTransaction;
        $transaction->voucher_ref = $voucher_ref;
        $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
        $transaction->transaction_date = $request->date;
        $transaction->transaction_description = "Sup. Payment: $request->description";
        $transaction->deposit = 0;
        $transaction->expense = $request->amount;
        $transaction->save();
        # End Cash/Bank Transaction

        //return view('expense.head.index')->with('success','New Expense Head Added');
        return redirect()->back()->with('success','Success');
    }

    public function destroyPayment($supplier_ledger_id){   

        $obj = DB::table('tbl_supplier_ledger')
        ->where('supplier_ledger_id',$supplier_ledger_id)
        ->first();
        
        $bank_transaction = BankTransaction::where('voucher_ref','=',"$obj->voucher_ref");
        $bank_transaction->delete();

        $supplier_ledger = SupplierLedger::where('voucher_ref','=',"$obj->voucher_ref");
        $supplier_ledger->delete();

        $voucher = Voucher::where('voucher_ref','=',"$obj->voucher_ref");
        $voucher->delete();
        
        $journalAmount = JournalAmount::where('voucher_ref','=',"$obj->voucher_ref");
        $journalAmount->delete();

        return redirect()->back()->with('success','Transaction Deleted Successfully!');
        

    }
}
