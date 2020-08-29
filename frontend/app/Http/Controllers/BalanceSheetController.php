<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\Sales;
use App\Customer;
use App\Supplier;
use App\Item;
use App\ItemUnit;
use App\StockLocation;
use App\PurchaseDetails;
use App\SalesDetails;
use App\BankAccountLedger;
use App\CustomerLedger;
use App\SupplierLedger;
use App\ExpenseHead;
use App\IncomeHead;
use App\Stock;
use App\StockTransfer;
use DB;
use PDF;

class BalanceSheetController extends Controller
{
    public function index()
    {   
        /*
        * ASSET
        */
        #cash bank
        $cashBank = $this->cashBank();
        #stock_store
        $stockStore = $this->stockStore();
        #accounts receivable
        $accountsReceivable = $this->accountsReceivable();

        /*
        * LIABILITY
        */
        #accounts payable
        $accountsPayable = $this->accountsPayable();
        #Loan
        $loans = $this->loanAccounts();

        return view('admin.report.statement.balance-sheet', compact('cashBank','stockStore','accountsReceivable','accountsPayable','loans'));
    }


    public function generateReport(Request $request)
    {   

        


        return view('admin.report.statement.balance-sheet', compact('reporting_date'));
    }

    public function cashBank(){

        $cash_bank_array = array();

        $accountGroups = DB::table('tbl_bank_account')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->where('tbl_bank_account.account_group_id','1') // 1 = cash bank 
        ->get();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $accObj = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();


            $sum_opening_balance_debit = $accObj->op_bal_dr; 
            $sum_opening_balance_credit = $accObj->op_bal_cr;

            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->groupBy('bank_account_id')
            ->first();

            if($all_transactions){
                $closing_balance = $opening_balance + $all_transactions->debit - $all_transactions->credit;
            }
            else{
                 $closing_balance = $opening_balance;
            }

            array_push($cash_bank_array,array("account_name"=>$accObj->bank_name, "closing_balance"=>$closing_balance));
        }

        return $cash_bank_array;
    }

    public function stockStore(){

        /*
        *   Closing Invemtory
        *
        */

        $items = DB::table('tbl_stock')
            ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->selectRaw('tbl_item.item_id,tbl_item.item_name,tbl_item.item_code,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
            ->groupBy('tbl_item.item_id')
            ->orderBy('tbl_item.item_name', 'asc')
            ->get();

        $closing = array();

        foreach($items as $data){

            $inventory_history = DB::table('tbl_stock')
            ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->leftJoin('tbl_stock_master','tbl_stock_master.id','=','tbl_stock.stock_master_id')
            ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
            ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_stock_master.date,tbl_batch.purchase_rate as rate')
            ->where('tbl_stock.item_id','=',$data->item_id)
            ->orderBy('tbl_stock.stock_id', 'asc')
            ->get(); 
            
            $quantity = 0;
            $rate = 0;
            
            if(count($inventory_history)>0){
                foreach($inventory_history as $key => $single_data){
                    if($single_data->stock_in>0){
                        $var_1 = $quantity * $rate;
                        $var_2 = $single_data->stock_in * $single_data->rate;
                        $sum_var = $var_1 + $var_2;

                        $quantity += $single_data->stock_in;
                        // to avoid division by Zero error
                        if($quantity>0){
                            $rate = $sum_var / $quantity;
                        }
                        
                    }
                    else{
                        $quantity -= $single_data->stock_out;
                    }
                }
            }
            array_push($closing, array('amount'=>$quantity * $rate));
        }

        $inventory_vluation = 0;

        foreach ($closing as $value) {
            $inventory_vluation += $value['amount'];
        }

        return $inventory_vluation;
    }

    public function accountsReceivable(){
        $grandReceivable = DB::table('tbl_customer')
        ->leftJoin('tbl_customer_ledger','tbl_customer_ledger.customer_id','=','tbl_customer.customer_id')
        ->selectRaw('tbl_customer.*,SUM(credit) as credit,SUM(debit) as debit')
        ->groupBy('tbl_customer.customer_id')
        ->orderBy('customer_name', 'asc')
        ->get();

        return $grandReceivable;
    }

    public function accountsPayable(){

        $grandPayable = DB::table('tbl_supplier')
        ->leftJoin('tbl_supplier_ledger','tbl_supplier_ledger.supplier_id','=','tbl_supplier.supplier_id')
        ->selectRaw('tbl_supplier.*,SUM(credit) as credit,SUM(debit) as debit')
        ->groupBy('tbl_supplier.supplier_id')
        ->orderBy('sup_name', 'asc')
        ->get();

        return $grandPayable;
    }
    

    public function dateToDateSalesMemoWiseReport(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_sales = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_sales_master.*,tbl_customer.*')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->orderBy('tbl_sales_master.memo_no', 'asc')
        ->get();
        
        return view('admin.report.sales.date-to-date-sales-memo-wise',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_sales'=>$date_to_date_sales]);
    }

   public function loanAccounts(){

        $loan_acc_array = array();

        $accountGroups = DB::table('tbl_bank_account')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->where('tbl_bank_account.account_group_id','!=','1') // 1 = cash bank 
        ->orderBy('bank_name', 'asc')
        ->get();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $accObj = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();


            $sum_opening_balance_debit = $accObj->op_bal_dr; 
            $sum_opening_balance_credit = $accObj->op_bal_cr;

            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->groupBy('bank_account_id')
            ->first();

            if($all_transactions){
                $closing_balance =  $all_transactions->credit - $opening_balance + $all_transactions->debit;
            }
            else{
                 $closing_balance = $opening_balance;
            }

            array_push($loan_acc_array,array("account_name"=>$accObj->bank_name, "closing_balance"=>$closing_balance));
        }

        return $loan_acc_array;
    }
    

}
