<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\Sales;
use App\Customer;
use App\CustomerLedger;
use App\Supplier;
use App\SupplierLedger;
use App\Item;
use App\PurchaseDetails;
use App\SalesDetails;
use App\BankAccount;
use App\BankAccountLedger;
use App\BankTransaction;
use App\Stock;
use App\IncomeHead;
use App\ExpenseHead;
use App\Income;
use App\Expense;
use App\Voucher;
use App\JournalAmount;
use Auth;
use DB;
use PDF;

class JournalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){

 
        $vouchers = DB::table('vouchers')
        ->leftJoin('journal_amounts','journal_amounts.voucher_ref','=','vouchers.voucher_ref')
        ->selectRaw('vouchers.*,journal_amounts.amount')
        ->where('type','Journal')
        ->orderBy('vouchers.date','desc')
        ->orderBy('vouchers.id','desc')
        ->get();

        return view('journal.index',compact('vouchers'));
    }

    public function create(){
        
        $accounts = BankAccount::all()->sortBy('bank_name');
        $customers = Customer::all()->sortBy('customer_name');
        $suppliers = Supplier::all()->sortBy('sup_name');
        $incomeHeads = IncomeHead::all()->sortBy('income_head');
        $expenseHeads = ExpenseHead::all()->sortBy('expense_head');

        $voucher = DB::table('vouchers')->orderBy('id', 'DESC')->first();
        if($voucher!=NULL){
            $voucher_ref = ++$voucher->id;
        }
        else{
            $voucher_ref = "1";   
        }

        return view('journal.create',compact('accounts','customers','suppliers','incomeHeads','expenseHeads','voucher_ref'));
    }

    public function store(Request $request){

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
        $voucher->voucher_description = $request->voucher_description;
        $voucher->date = $request->date;
        $voucher->save();

        $total_amount = 0;

        foreach ($request->account_id as $key => $value) {

            #### Cash/Bank/Other ###
            if($value[0]=='G'){
                $transaction = new BankTransaction;
                $transaction->voucher_ref = $voucher_ref;
                $transaction->bank_account_id = substr($request->account_id[$key],1);
                $transaction->transaction_date = $request->date;
                $transaction->transaction_description = $request->description[$key];
                $transaction->deposit = $request->debit[$key];
                $transaction->expense = $request->credit[$key];
                $transaction->save();

                $total_amount += $request->debit[$key];
            }

            #### Customer ###
            if($value[0]=='C'){
                $customer_ledger = new CustomerLedger;
                $customer_ledger->sales_master_id = 0;
                $customer_ledger->customer_id = substr($request->account_id[$key],1);
                
                if($request->credit[$key]>0){
                    $customer_ledger->tran_ref_id = 7;
                    $customer_ledger->tran_ref_name = 'PayMoney';
                }
                elseif($request->debit[$key]>0){
                    $customer_ledger->tran_ref_id = 90;
                    $customer_ledger->tran_ref_name = 'CreditVoucher';
                }

                $customer_ledger->particulars = $request->description[$key];
                $customer_ledger->debit = $request->debit[$key];
                $customer_ledger->credit = $request->credit[$key];
                $customer_ledger->voucher_ref = $voucher_ref;
                $customer_ledger->transaction_date = $request->date;
                $customer_ledger->save();

                $total_amount += $request->debit[$key];
            }

            #### Supplier ###
            if($value[0]=='S'){
                $supplier_ledger = new SupplierLedger;
                $supplier_ledger->purchase_master_id = 0;
                $supplier_ledger->supplier_id = substr($request->account_id[$key],1);

                if($request->debit[$key]>0){
                    $supplier_ledger->tran_ref_id = 7;
                    $supplier_ledger->tran_ref_name = 'PayMoney';
                }
                elseif($request->credit[$key]>0){
                    $supplier_ledger->tran_ref_id = 60;
                    $supplier_ledger->tran_ref_name = 'DebitVoucher';
                }

                $supplier_ledger->particulars = $request->description[$key];
                $supplier_ledger->debit = $request->debit[$key];
                $supplier_ledger->credit = $request->credit[$key];
                $supplier_ledger->voucher_ref = $voucher_ref;
                $supplier_ledger->transaction_date = $request->date;
                $supplier_ledger->save();

                $total_amount += $request->debit[$key];
            }

            #### Income ###
            if($value[0]=='I'){
                $income = new Income;
                $income->voucher_ref = $voucher_ref;
                $income->income_head_id = substr($request->account_id[$key],1);
                $income->date = $request->date;
                $income->amount = $request->credit[$key];
                $income->description = $request->description[$key];
                $income->admin_id = Auth::user()->id;
                $income->save();

                $total_amount += $request->debit[$key];
            }

            #### Expense ###
            if($value[0]=='E'){
                $expense = new Expense;
                $expense->voucher_ref = $voucher_ref;
                $expense->expense_head_id = substr($request->account_id[$key],1);
                $expense->date = $request->date;
                $expense->amount = $request->debit[$key];
                $expense->description = $request->description[$key];
                $expense->admin_id = Auth::user()->id;
                $expense->save();

                $total_amount += $request->debit[$key];
            }

        }

        $journal_amount = new JournalAmount;
        $journal_amount->voucher_ref = $voucher_ref;
        $journal_amount->amount = $total_amount;
        $journal_amount->save();

        return redirect('/journal')->with('success','Journal entry successfully stored');
        
    }

    public function show($voucher_ref){

        $voucher = DB::table('vouchers')
        ->leftJoin('journal_amounts','journal_amounts.voucher_ref','=','vouchers.voucher_ref')
        ->where('vouchers.voucher_ref',$voucher_ref)
        ->first();

        $transactions = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->where('voucher_ref',$voucher_ref)
        ->get();

        $customer_ledger = DB::table('tbl_customer_ledger')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_customer_ledger.customer_id')
        ->where('voucher_ref',$voucher_ref)
        ->get();

        $supplier_ledger = DB::table('tbl_supplier_ledger')
        ->join('tbl_supplier','tbl_supplier.supplier_id','=','tbl_supplier_ledger.supplier_id')
        ->where('voucher_ref',$voucher_ref)
        ->get();

        $income = DB::table('tbl_income')
        ->join('tbl_income_head','tbl_income_head.income_head_id','=','tbl_income.income_head_id')
        ->selectRaw('tbl_income.*,tbl_income_head.income_head')
        ->where('tbl_income.voucher_ref',$voucher_ref)
        ->get();

        $expense = DB::table('tbl_expense')
        ->join('tbl_expense_head','tbl_expense_head.expense_head_id','=','tbl_expense.expense_head_id')
        ->selectRaw('tbl_expense.*,tbl_expense_head.expense_head')
        ->where('tbl_expense.voucher_ref',$voucher_ref)
        ->get();

        return view('journal.print-voucher',compact('voucher','transactions','customer_ledger','supplier_ledger','income','expense'));

    }

    public function edit($voucher_ref){

        $accounts = BankAccount::all()->sortBy('bank_name');
        $customers = Customer::all()->sortBy('customer_name');
        $suppliers = Supplier::all()->sortBy('sup_name');
        $incomeHeads = IncomeHead::all()->sortBy('income_head');
        $expenseHeads = ExpenseHead::all()->sortBy('expense_head');

        $voucher = DB::table('vouchers')
        ->leftJoin('journal_amounts','journal_amounts.voucher_ref','=','vouchers.voucher_ref')
        ->where('vouchers.voucher_ref',$voucher_ref)->first();

        $transactions = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->where('voucher_ref',$voucher_ref)
        ->get();

        $customer_ledger = DB::table('tbl_customer_ledger')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_customer_ledger.customer_id')
        ->where('voucher_ref',$voucher_ref)
        ->get();

        $supplier_ledger = DB::table('tbl_supplier_ledger')
        ->join('tbl_supplier','tbl_supplier.supplier_id','=','tbl_supplier_ledger.supplier_id')
        ->where('voucher_ref',$voucher_ref)
        ->get();

        $income = DB::table('tbl_income')
        ->join('tbl_income_head','tbl_income_head.income_head_id','=','tbl_income.income_head_id')
        ->selectRaw('tbl_income.*,tbl_income_head.income_head')
        ->where('tbl_income.voucher_ref',$voucher_ref)
        ->get();

        $expense = DB::table('tbl_expense')
        ->join('tbl_expense_head','tbl_expense_head.expense_head_id','=','tbl_expense.expense_head_id')
        ->selectRaw('tbl_expense.*,tbl_expense_head.expense_head')
        ->where('tbl_expense.voucher_ref',$voucher_ref)
        ->get();

        return view('journal.edit',compact('accounts','customers','suppliers','incomeHeads','expenseHeads','voucher','transactions','customer_ledger','supplier_ledger','income','expense'))->with('voucher_ref',$voucher_ref);

    }

    public function update(Request $request){

        $voucher_ref = $request->voucher_ref;
        $date = $request->date;


        //DELETE PREVIOUS ENTRY
        $bank_transaction = BankTransaction::where('voucher_ref','=',"$voucher_ref");
        $bank_transaction->delete();

        $customer_ledger = CustomerLedger::where('voucher_ref','=',"$voucher_ref");
        $customer_ledger->delete();

        $supplier_ledger = SupplierLedger::where('voucher_ref','=',"$voucher_ref");
        $supplier_ledger->delete();

        $income = Income::where('voucher_ref','=',"$voucher_ref");
        $income->delete();

        $expense = Expense::where('voucher_ref','=',"$voucher_ref");
        $expense->delete();
        //END

        DB::table('vouchers')
            ->where('voucher_ref', $voucher_ref)
            ->update(['voucher_description' => $request->input('voucher_description'),
                      'date' => $request->input('date')]);
        
        $total_amount = 0;

        foreach ($request->account_id as $key => $value) {

            #### Cash/Bank/Other ###
            if($value[0]=='G'){

                $transaction = new BankTransaction;
                $transaction->voucher_ref = $voucher_ref;
                $transaction->bank_account_id = substr($request->account_id[$key],1);
                $transaction->transaction_date = $request->date;
                $transaction->transaction_description = $request->description[$key];
                $transaction->deposit = $request->debit[$key];
                $transaction->expense = $request->credit[$key];
                $transaction->save();

                $total_amount += $request->debit[$key];
            }

            #### Customer ###
            if($value[0]=='C'){

                $customer_ledger = new CustomerLedger;
                $customer_ledger->sales_master_id = 0;
                $customer_ledger->customer_id = substr($request->account_id[$key],1);
                
                if($request->credit[$key]>0){
                    $customer_ledger->tran_ref_id = 7;
                    $customer_ledger->tran_ref_name = 'PayMoney';
                }
                elseif($request->debit[$key]>0){
                    $customer_ledger->tran_ref_id = 90;
                    $customer_ledger->tran_ref_name = 'CreditVoucher';
                }

                $customer_ledger->particulars = $request->description[$key];
                $customer_ledger->debit = $request->debit[$key];
                $customer_ledger->credit = $request->credit[$key];
                $customer_ledger->voucher_ref = $voucher_ref;
                $customer_ledger->transaction_date = $request->date;
                $customer_ledger->save();

                $total_amount += $request->debit[$key];
            }

            #### Supplier ###
            if($value[0]=='S'){

                $supplier_ledger = new SupplierLedger;
                $supplier_ledger->purchase_master_id = 0;
                $supplier_ledger->supplier_id = substr($request->account_id[$key],1);

                if($request->debit[$key]>0){
                    $supplier_ledger->tran_ref_id = 7;
                    $supplier_ledger->tran_ref_name = 'PayMoney';
                }
                elseif($request->credit[$key]>0){
                    $supplier_ledger->tran_ref_id = 60;
                    $supplier_ledger->tran_ref_name = 'DebitVoucher';
                }

                $supplier_ledger->particulars = $request->description[$key];
                $supplier_ledger->debit = $request->debit[$key];
                $supplier_ledger->credit = $request->credit[$key];
                $supplier_ledger->voucher_ref = $voucher_ref;
                $supplier_ledger->transaction_date = $request->date;
                $supplier_ledger->save();

                $total_amount += $request->debit[$key];
            }

            #### Income ###
            if($value[0]=='I'){

                $income = new Income;
                $income->voucher_ref = $voucher_ref;
                $income->income_head_id = substr($request->account_id[$key],1);
                $income->date = $request->date;
                $income->amount = $request->credit[$key];
                $income->description = $request->description[$key];
                $income->admin_id = Auth::user()->id;
                $income->save();

                $total_amount += $request->debit[$key];
            }

            #### Expense ###
            if($value[0]=='E'){

                $expense = new Expense;
                $expense->voucher_ref = $voucher_ref;
                $expense->expense_head_id = substr($request->account_id[$key],1);
                $expense->date = $request->date;
                $expense->amount = $request->debit[$key];
                $expense->description = $request->description[$key];
                $expense->admin_id = Auth::user()->id;
                $expense->save();

                $total_amount += $request->debit[$key];
            }

        }

        DB::table('journal_amounts')
            ->where('voucher_ref', $voucher_ref)
            ->update(['amount' => $total_amount]);

        return redirect('/journal')->with('success','Journal Entry Updated');
    }

    public function destroy($voucher_ref){

        $voucher = Voucher::where('voucher_ref','=',"$voucher_ref");
        $voucher->delete();

        $bank_transaction = BankTransaction::where('voucher_ref','=',"$voucher_ref");
        $bank_transaction->delete();

        $customer_ledger = CustomerLedger::where('voucher_ref','=',"$voucher_ref");
        $customer_ledger->delete();

        $supplier_ledger = SupplierLedger::where('voucher_ref','=',"$voucher_ref");
        $supplier_ledger->delete();

        $income = Income::where('voucher_ref','=',"$voucher_ref");
        $income->delete();

        $expense = Expense::where('voucher_ref','=',"$voucher_ref");
        $expense->delete();

        $journalAmount = JournalAmount::where('voucher_ref','=',"$voucher_ref");
        $journalAmount->delete();

        return redirect()->back()->with('success','Journal Entry Deleted');
    }
}
