<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\SupplierLedger;
use App\Customer;
use App\CustomerLedger;
use App\BankAccountLedger;
use App\Income;
use App\IncomeGroup;
use App\IncomeHead;
use App\ExpenseHead;
use App\ExpenseGroup;
use App\Expense;
use App\Voucher;
use App\AccountGroup;
use DB;

class LedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    ###################################################
    ################# Supplier Ledger #################
    ###################################################
    public function index()
    {   
        $suppliers = Supplier::all()->sortBy("sup_name");
        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();
        
        return view('ledger.supplier-ledger',compact('suppliers','accounts'));
    }

    public function supplierLedger(Request $request)
    {   
        $supplier_id = $request->supplier_id;
        $suppliers = Supplier::all()->sortBy("sup_name");
        $supplier_ledgers = SupplierLedger::where('supplier_id',$supplier_id)->orderBy('transaction_date','Desc')->get();
        $current_supplier = Supplier::where('supplier_id',$supplier_id)->first();
        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();

        //var_dump($supplier_ledgers);
        return view('ledger.supplier-ledger',compact('suppliers','supplier_ledgers','current_supplier','accounts'));
    }

    public function supplierLedgerPDF($supplier_id)
    {   
        $supplier_ledgers = SupplierLedger::where('supplier_id',$supplier_id)->orderBy('transaction_date','Desc')->get();
        $current_supplier = Supplier::where('supplier_id',$supplier_id)->first();

        //var_dump($supplier_ledgers);
        return view('ledger.supplier-ledger-pdf',compact('supplier_ledgers','current_supplier'));
    }

    ###################################################
    ################# Customer Ledger #################
    ###################################################

    public function indexCustomer()
    {   
        $customers = Customer::all()->sortBy("customer_name");
        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();
        
        return view('ledger.customer-ledger',compact('customers','accounts'));
    }

    public function customerLedger(Request $request)
    {   
        $customer_id = $request->customer_id;
        $customers = Customer::all()->sortBy("customer_name");
        $customer_ledgers = CustomerLedger::where('customer_id',$customer_id)->orderBy('transaction_date','Desc')->get();
        $current_customer = Customer::where('customer_id',$customer_id)->first();
        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();

        //var_dump($supplier_ledgers);
        return view('ledger.customer-ledger',compact('customers','customer_ledgers','current_customer','accounts'));
    }

    public function customerLedgerPDF($customer_id)
    {   
        $customer_ledgers = CustomerLedger::where('customer_id',$customer_id)->orderBy('transaction_date','Desc')->get();
        $current_customer = Customer::where('customer_id',$customer_id)->first();
        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();
        //var_dump($supplier_ledgers);
        return view('ledger.customer-ledger-pdf',compact('customer_ledgers','current_customer','accounts'));
    }

    ###################################################
    ################# START Expense Ledger #################
    ###################################################
    public function indexExpense()
    {   
        $expenseAccounts = ExpenseHead::all()->sortBy("expense_head");

        $accounts = DB::table('tbl_bank_account')->orderBy('bank_account_id', 'asc')->get();
        
        return view('ledger.expense-ledger',compact('expenseAccounts','accounts'));
    }

    public function expenseLedger(Request $request)
    {   
        $expense_head_id = $request->expense_head_id;
        $expenseAccounts = ExpenseHead::all()->sortBy("expense_head");
        $expense_account_ledgers = Expense::where('expense_head_id',$expense_head_id)->orderBy('date','Desc')->get();
        $current_expense_account = ExpenseHead::where('expense_head_id',$expense_head_id)->first();
        $accounts = DB::table('tbl_bank_account')->orderBy('bank_account_id', 'asc')->get();

        //var_dump($supplier_ledgers);
        return view('ledger.expense-ledger',compact('expenseAccounts','expense_account_ledgers','current_expense_account','accounts'));
    }

    public function expenseLedgerPDF($expense_head_id)
    {   
        $expenseAccounts = ExpenseHead::all()->sortBy("expense_head");
        $expense_account_ledgers = Expense::where('expense_head_id',$expense_head_id)->orderBy('date','Desc')->get();
        $current_expense_account = ExpenseHead::where('expense_head_id',$expense_head_id)->first();
        $accounts = DB::table('tbl_bank_account')->orderBy('bank_account_id', 'asc')->get();

        //var_dump($supplier_ledgers);
        return view('ledger.expense-ledger-pdf',compact('expenseAccounts','expense_account_ledgers','current_expense_account','accounts'));
    }

    ###################################################
    ################# END Expense Ledger #################
    ###################################################

    public function indexAccountGroup()
    {   
        $accountGroups = AccountGroup::all()->sortBy("name");
        
        return view('ledger.account-group-ledger',compact('accountGroups'));
    }

    public function accountGroupLedger(Request $request)
    {   
        $id = $request->id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $accountGroups = AccountGroup::all()->sortBy("name");
        $current_account_group = AccountGroup::where('id',$id)->first();

        $group_account_ledgers = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id', '=', 'tbl_bank_transaction.bank_account_id')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name')
        ->where('tbl_account_group.id',$id)
        ->whereBetween('tbl_bank_transaction.transaction_date',[$start_date,$end_date])
        ->orderBy('tbl_bank_transaction.transaction_date', 'asc')
        ->get();

        return view('ledger.account-group-ledger',compact('accountGroups','group_account_ledgers','current_account_group','start_date','end_date'));
    }

    public function create()
    {
        
    }


    public function store(Request $request)
    {
       
    }

    public function show($id)
    {
       
    }


    public function edit($id)
    {
      
    }


    public function update(Request $request, $id)
    {
        
    }


    public function destroy($id)
    {
        
    }
}
