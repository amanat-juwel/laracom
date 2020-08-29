<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;
use App\BankAccount;
use App\AccountGroup;
use App\BankAccountLedger;
use App\BankTransaction;
use App\CustomerLedger;
use App\SupplierLedger;

use App\Voucher;
use App\JournalAmount;
use DB;
use Validator;

class BankTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $transactions = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name,tbl_bank_account.bank_account')
        ->orderBy('transaction_date', 'dsc')
        ->get();

        $bank_accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account', 'asc')
        ->get();
 
        $bank_transaction = DB::table('tbl_bank_transaction')
        ->selectRaw('SUM(deposit) as deposit,SUM(expense) as expense')
        ->get();  

        foreach ($bank_transaction as $key => $cash) {
            $current_cash_in_hand = $cash->deposit - $cash->expense;
        }


        
        return view('admin.bank.index', compact('transactions','bank_accounts','current_cash_in_hand'));
    }

    public function indexTransfer()
    {
        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();


        return view('admin.bank.transfer', compact('accounts'));
    }

    public function storeTransfer(Request $request)
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
        $voucher->voucher_description = $request->description;
        $voucher->date = $request->date;
        $voucher->save();

        $account = BankAccount::find($request->to_account);
        $transaction_from = new BankTransaction;
        $transaction_from->bank_account_id = $request->from_account;
        $transaction_from->voucher_ref = $voucher_ref; 
        $transaction_from->transaction_date = $request->date;
        $transaction_from->transaction_description = "$account->bank_name";
        $transaction_from->deposit = 0;
        $transaction_from->expense = $request->amount;
        $transaction_from->save();

        $account = BankAccount::find($request->from_account);
        $transaction_to = new BankTransaction;
        $transaction_to->bank_account_id = $request->to_account;
        $transaction_to->voucher_ref = $voucher_ref; 
        $transaction_to->transaction_date = $request->date;
        $transaction_to->transaction_description = "$account->bank_name";
        $transaction_to->deposit = $request->amount;
        $transaction_to->expense = 0;
        $transaction_to->save();

        $journal_amount = new JournalAmount;
        $journal_amount->voucher_ref = $voucher_ref;
        $journal_amount->amount = $request->amount;
        $journal_amount->save();

        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();

        return redirect('admin/bank/transaction')->with('success','Transaction Successful');
    }

    public function indexAccount()
    {
        $bank_accounts = DB::table('tbl_bank_transaction')
        ->rightJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->leftJoin('tbl_account_group','tbl_account_group.id','=','tbl_bank_account.account_group_id')
        ->leftJoin('tbl_account_type','tbl_account_type.account_type_id','=','tbl_bank_account.account_type_id')
        ->leftJoin('tbl_sub_account_type','tbl_sub_account_type.id','=','tbl_bank_account.sub_account_type_id')
        ->selectRaw('tbl_bank_account.*,tbl_account_group.name as group_name,tbl_account_type.name acc_type_name,
            tbl_sub_account_type.name sub_acc_type_name, sum(tbl_bank_transaction.deposit) as debit, sum(tbl_bank_transaction.expense) as credit')
        ->groupBy('bank_account_id')
        ->orderBy('bank_account', 'asc')
        ->get();

        //return dd($bank_accounts);
        $account_groups = AccountGroup::all()->sortBy('name');

        return view('admin.bank.account.index', compact('bank_accounts','account_groups'));
    }

    public function indexOtherAccount()
    {
        $bank_accounts = DB::table('tbl_bank_account')
        ->leftJoin('tbl_account_group','tbl_account_group.id','=','tbl_bank_account.account_group_id')
        ->leftJoin('tbl_account_type','tbl_account_type.account_type_id','=','tbl_bank_account.account_type_id')
        ->leftJoin('tbl_sub_account_type','tbl_sub_account_type.id','=','tbl_bank_account.sub_account_type_id')
        ->selectRaw('tbl_bank_account.*,tbl_account_group.name as group_name,tbl_account_type.name acc_type_name,
            tbl_sub_account_type.name sub_acc_type_name')
        ->where('is_payment_method',0)
        ->orderBy('bank_account', 'asc')
        ->get();
        $account_groups = AccountGroup::all()->sortBy('name');

        return view('admin.bank.otherAccount.index', compact('bank_accounts','account_groups'));
    }

    public function create()
    {
        return view('expense.create');
    }

    public function store(Request $request)
    {
        if($request->amount <= 0){
            return redirect('admin/bank/transaction')->with('delete','Transaction Failed');
        }
        
        $transaction = new BankTransaction;
        $transaction->bank_account_id = $request->bank_account_id;
        $transaction->transaction_date = $request->transaction_date;
        $transaction->transaction_description = $request->transaction_description;
        $category = $request->category;
        if($category == 'Deposit'){
            $transaction->deposit = $request->amount;
            $transaction->expense = 0;
        }
        else{
            $transaction->deposit = 0;
            $transaction->expense = $request->amount;
        }

        $transaction->save();

        return redirect('admin/bank/transaction')->with('success','New Transaction Added');
    }

    public function storeAccount(Request $request)
    {
        $bank_account = new BankAccount;
        $bank_account->bank_name = $request->bank_name;
        $bank_account->bank_account = $request->bank_account;
        $bank_account->description = $request->input('description')? : 'Null';
        $bank_account->account_type_id = $request->account_type_id;
        $bank_account->sub_account_type_id = $request->sub_account_type_id;
        $bank_account->account_group_id = $request->account_group_id;
        $bank_account->is_payment_method = (!empty($request->is_payment_method))?$request->is_payment_method : 0;
        $bank_account->op_bal_dr = $request->input('op_bal_dr')? : '0';
        $bank_account->op_bal_cr = $request->input('op_bal_cr')? : '0';
        $bank_account->save();

        return redirect('admin/bank/account')->with('success','New Account Created');
    }


    public function storeOtherAccount(Request $request)
    {
        $bank_account = new BankAccount;
        $bank_account->bank_name = $request->bank_name;
        $bank_account->bank_account = $request->bank_account;
        $bank_account->description = $request->description;
        $bank_account->account_type_id = $request->account_type_id;
        $bank_account->sub_account_type_id = $request->sub_account_type_id;
        $bank_account->account_group_id = $request->account_group_id;
        $bank_account->is_payment_method = 0;//(!empty($request->is_payment_method))?$request->is_payment_method : 0;
        $bank_account->save();

        ##### START OPENING BALANCE ######
        if($request->debit > 0){
            $bank_account_ledger = new BankAccountLedger;
            $bank_account_ledger->bank_account_id = $bank_account->bank_account_id;
            $bank_account_ledger->particulars = "Opening Balance of Account";
            $bank_account_ledger->date = date('Y-m-d');
            $bank_account_ledger->voucher_ref = "opening_bal_".$bank_account->bank_account_id;
            $bank_account_ledger->debit = $request->debit;
            $bank_account_ledger->credit = 0;
            $bank_account_ledger->save();
        }
        elseif($request->credit > 0){
            $bank_account_ledger = new BankAccountLedger;
            $bank_account_ledger->bank_account_id = $bank_account->bank_account_id;
            $bank_account_ledger->particulars = "Opening Balance of Account";
            $bank_account_ledger->date = date('Y-m-d');
            $bank_account_ledger->voucher_ref = "opening_bal_".$bank_account->bank_account_id;
            $bank_account_ledger->debit = 0;
            $bank_account_ledger->credit = $request->credit;
            $bank_account_ledger->save();
        }
        else{
            $bank_account_ledger = new BankAccountLedger;
            $bank_account_ledger->bank_account_id = $bank_account->bank_account_id;
            $bank_account_ledger->particulars = "Opening Balance of Account";
            $bank_account_ledger->date = date('Y-m-d');
            $bank_account_ledger->voucher_ref = "opening_bal_".$bank_account->bank_account_id;
            $bank_account_ledger->debit = 0;
            $bank_account_ledger->credit = 0;
            $bank_account_ledger->save();
        }
        ##### END OPENING BALANCE ######

        $voucher = new Voucher;
        $voucher->voucher_ref = "opening_bal_".$bank_account->bank_account_id;
        $voucher->type = "General";
        $voucher->save();

        return redirect()->back()->with('success','New Account Created');
    }

    public function show($id)
    {
        $brand = DB::table('tbl_brand')
        ->orderBy('brand_id', 'desc')
        ->get();

        return view('brand.index', compact('brand'));
    }

    public function edit($id)
    {
        $singleTransactionById = BankTransaction::find($id);

        ##########Start Check if journal transaction#############

        // $voucher = DB::table('vouchers')->where('voucher_ref',"$singleTransactionById->voucher_ref")->first();
        // if($voucher!=NULL){
        //     if($voucher->type=="Journal"){

        //         return redirect("/journal/$voucher->voucher_ref/edit");
        //     }
        // }


        ##########Start Check if Transfer transaction#############
        $transactions = DB::table('tbl_bank_transaction')
        ->where('voucher_ref',$singleTransactionById->voucher_ref)
        ->get();

        
        if(count($transactions)>=2){

            $accounts = DB::table('tbl_bank_account')
            ->orderBy('bank_account_id', 'asc')
            ->get();

            return view('admin.bank.edit',compact('accounts','singleTransactionById','transactions'))->with('id',$id);
        };
        ##########End Check if Transfer transaction#############


        ##########Start Check if Supplier transaction#############
        $supplier_ledger = DB::table('tbl_supplier_ledger')
        ->where('voucher_ref',$singleTransactionById->voucher_ref)
        ->first();

        
        if(count($supplier_ledger) >= 1){
            $payment_method = DB::table('tbl_bank_transaction')
            ->where('voucher_ref', $supplier_ledger->voucher_ref)
            ->first();

            $suppliers = DB::table('tbl_supplier')
            ->orderBy('sup_name', 'asc')
            ->get();

            $accounts = DB::table('tbl_bank_account')
            ->orderBy('bank_account_id', 'asc')
            ->get();


            return view('supplier.edit-transaction',compact('supplier_ledger','suppliers','accounts','payment_method'))->with('id',$id);
        };
        ##########End Check if income transaction#############

        return redirect()->back()->with('delete','Edit not possible at this moment');

 
    }

    public function editAccount($id)
    {
        $singleAccountById = BankAccount::find($id);

        $account_groups = AccountGroup::all()->sortBy('name');
        $sub_acc_type = DB::table('tbl_sub_account_type')
        ->where('account_type_id',$singleAccountById->account_type_id)
        ->orderBy('name')
        ->get();

        return view('admin.bank.account.edit',compact('singleAccountById','account_groups','sub_acc_type'))->with('id',$id);
    }

    public function update(TransactionRequest $request, $id)
    {

        $from_transaction_= BankTransaction::find($request->bank_transaction_id_from);
        $from_transaction_->bank_account_id = $request->input('from_account');
        $from_transaction_->transaction_date = $request->input('date');
        $from_transaction_->transaction_description = $request->input('description');
        $from_transaction_->expense = $request->input('amount');
        $from_transaction_->update();

        $to_transaction_= BankTransaction::find($request->bank_transaction_id_to);
        $to_transaction_->bank_account_id = $request->input('to_account');
        $to_transaction_->transaction_date = $request->input('date');
        $to_transaction_->transaction_description = $request->input('description');
        $to_transaction_->deposit = $request->input('amount');
        $to_transaction_->update();

        return redirect('admin/bank/transaction')->with('success','Transaction Edited Successfully');
    }

    public function updateAccount(Request $request, $id)
    {
        $bank_account = BankAccount::find($id);
        $bank_account->bank_name = $request->input('bank_name');
        $bank_account->bank_account = $request->input('bank_account');
        $bank_account->description = $request->input('description');
        $bank_account->account_type_id = $request->input('account_type_id');
        $bank_account->sub_account_type_id = $request->input('sub_account_type_id');
        $bank_account->account_group_id = $request->input('account_group_id');
        $bank_account->op_bal_dr = $request->input('op_bal_dr');
        $bank_account->op_bal_cr = $request->input('op_bal_cr');
        $bank_account->is_payment_method = (!empty($request->is_payment_method))?$request->is_payment_method : 0;
        $bank_account->update();
        
        return redirect('admin/bank/account')->with('update','Account info Updated');
        
    }

    public function destroy(Request $request,$id)
    {
        $bank_transaction = BankTransaction::where('voucher_ref','=',"$request->voucher_ref");
        $bank_transaction->delete();

        $customer_ledger = CustomerLedger::where('voucher_ref','=',"$request->voucher_ref");
        $customer_ledger->delete();

        $supplier_ledger = SupplierLedger::where('voucher_ref','=',"$request->voucher_ref");
        $supplier_ledger->delete();

        $voucher = Voucher::where('voucher_ref','=',"$request->voucher_ref");
        $voucher->delete();
        
        $journalAmount = JournalAmount::where('voucher_ref','=',"$request->voucher_ref");
        $journalAmount->delete();

        return redirect('admin/bank/transaction')->with('delete','Transaction Deleted');
    }

    public function destroyAccount($id)
    {
        $count = DB::table('tbl_bank_transaction')
        ->where('bank_account_id',$id)
        ->count();
        
        if($count==0){
            $bank_account = BankAccount::find($id);
            $bank_account->delete();


            return redirect('admin/bank/account')->with('success','Account Deleted');
        }
        else{
            return redirect()->back()->with('delete','Account is in use!');
        }

        
    }

    public function subAccountType($account_type_id)
    {
        $sub_acc_type = DB::table('tbl_sub_account_type')
        ->where('account_type_id',$account_type_id)
        ->orderBy('name')
        ->get();
        return json_encode($sub_acc_type);
    }
}
