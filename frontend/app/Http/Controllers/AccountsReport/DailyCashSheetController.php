<?php

namespace App\Http\Controllers\AccountsReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDF;

class DailyCashSheetController extends Controller
{   

    public function index()
    {
        $cash_bank_accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();

        return view('admin.report.statement.daily-cash-sheet.index', compact('cash_bank_accounts'));
    }

    public function report(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $bank_account_id = $request->bank_account_id;

        //Opening Balance Start
        $initial_opening_balance = DB::table('tbl_bank_account')
        ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
        ->first();

        $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->where('tbl_bank_transaction.transaction_date', '<', $start_date)
        ->get();

        $sum_opening_balance_deposit = $initial_opening_balance->op_bal_dr; 
        $sum_opening_balance_expense = $initial_opening_balance->op_bal_cr;


        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_deposit += $single_transaction->deposit; 
            $sum_opening_balance_expense += $single_transaction->expense;
        }
        $opening_balance = $sum_opening_balance_deposit - $sum_opening_balance_expense;
        //Opening Balance End

        $receipts = DB::table('tbl_bank_transaction')
        ->selectRaw('tbl_bank_transaction.*')
        ->where('tbl_bank_transaction.bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->where('deposit', '>', 0)
        ->orderBy('transaction_date', 'asc')
        ->get();

        $payments = DB::table('tbl_bank_transaction')
        ->selectRaw('tbl_bank_transaction.*')
        ->where('tbl_bank_transaction.bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->where('expense', '>', 0)
        ->orderBy('transaction_date', 'asc')
        ->get();

        $bank_name = $initial_opening_balance->bank_name;
  
        $cash_bank_accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();

        //return dd($payments);

        return view('admin.report.statement.daily-cash-sheet.index', compact('cash_bank_accounts','bank_account_id','bank_name','opening_balance','receipts','payments','start_date','end_date'));
    }

    public function print($start_date ,$end_date, $bank_account_id)
    {

        //Opening Balance Start
        $initial_opening_balance = DB::table('tbl_bank_account')
        ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
        ->first();

        $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->where('tbl_bank_transaction.transaction_date', '<', $start_date)
        ->get();

        $sum_opening_balance_deposit = $initial_opening_balance->op_bal_dr; 
        $sum_opening_balance_expense = $initial_opening_balance->op_bal_cr;


        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_deposit += $single_transaction->deposit; 
            $sum_opening_balance_expense += $single_transaction->expense;
        }
        $opening_balance = $sum_opening_balance_deposit - $sum_opening_balance_expense;
        //Opening Balance End

        $receipts = DB::table('tbl_bank_transaction')
        ->selectRaw('tbl_bank_transaction.*')
        ->where('tbl_bank_transaction.bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->where('deposit', '>', 0)
        ->orderBy('transaction_date', 'asc')
        ->get();

        $payments = DB::table('tbl_bank_transaction')
        ->selectRaw('tbl_bank_transaction.*')
        ->where('tbl_bank_transaction.bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->where('expense', '>', 0)
        ->orderBy('transaction_date', 'asc')
        ->get();

        $bank_name = $initial_opening_balance->bank_name;
  
        $cash_bank_accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();


        return view('admin.report.statement.daily-cash-sheet.print', compact('cash_bank_accounts','bank_account_id','bank_name','opening_balance','receipts','payments','start_date','end_date'));

    }

}
