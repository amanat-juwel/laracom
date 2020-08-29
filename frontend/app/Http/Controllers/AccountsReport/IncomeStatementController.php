<?php

namespace App\Http\Controllers\AccountsReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDF;

class IncomeStatementController extends Controller
{   

    public function incomeStatement(){
        return view('admin.report.statement.income-statement.income-statement');
    }

    public function stockStore($type, $reportingDate){

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

            if($type == 'ending_stock'){
                $inventory_history = DB::table('tbl_stock')
                ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
                ->leftJoin('tbl_stock_master','tbl_stock_master.id','=','tbl_stock.stock_master_id')
                ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
                ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_stock_master.date,tbl_batch.purchase_rate as rate')
                ->where('tbl_stock.item_id','=',$data->item_id)
                ->where('tbl_stock_master.date','<=',$reportingDate)
                ->orderBy('tbl_stock.stock_id', 'asc')
                ->get(); 
            }
            else{
                $inventory_history = DB::table('tbl_stock')
                ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
                ->leftJoin('tbl_stock_master','tbl_stock_master.id','=','tbl_stock.stock_master_id')
                ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
                ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_stock_master.date,tbl_batch.purchase_rate as rate')
                ->where('tbl_stock.item_id','=',$data->item_id)
                ->where('tbl_stock_master.date','<',$reportingDate)
                ->orderBy('tbl_stock.stock_id', 'asc')
                ->get(); 
            }
            
            
            $quantity = 0;
            $rate = 0;
            
            if(count($inventory_history)>0){
                foreach($inventory_history as $key => $single_data){
                    if($single_data->stock_in>0){
                        $var_1 = $quantity * $rate;
                        $var_2 = $single_data->stock_in * $single_data->rate;
                        $sum_var = $var_1 + $var_2;

                        $quantity += $single_data->stock_in;
                        $rate = $sum_var / $quantity;
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

    public function incomeStatementReport(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        //all transactions for the given period of time
        $income_transaction = DB::table('tbl_sales_master')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->selectRaw('sum(memo_total) - sum(discount) as sales')
        ->value('sales');
        
        $sales_return = DB::table('tbl_sales_return_exchange_master')
        ->join('tbl_purchase_master','tbl_purchase_master.purchase_master_id','=','tbl_sales_return_exchange_master.purchase_master_id')
        ->whereBetween('tbl_purchase_master.purchase_date', [$start_date, $end_date])
        ->selectRaw('sum(memo_total) as total')
        ->value('total');

        $opening_stock_value = $this->stockStore('starting_stock', $start_date);

        $purchased_stock_value = DB::table('tbl_stock')
        ->join('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
        ->join('tbl_batch','tbl_batch.id', '=', 'tbl_stock.batch_id')
        ->selectRaw('sum(tbl_batch.purchase_rate * tbl_stock.stock_in) as purchased_stock_value')
        ->whereBetween('tbl_stock_master.date', [$start_date, $end_date])
        ->where('tbl_stock_master.type', '!=', 'Sales Return')
        ->where('tbl_stock.stock_in', '>', 0)
        ->value('purchased_stock_value');

        $closing_stock_value = $this->stockStore('ending_stock', $end_date);


        $cost_of_goods_sold = $opening_stock_value + $purchased_stock_value - $closing_stock_value;

        
        # 9. ADMINISTRATIVE, FINANCIAL, SELLING & DISTRIBUTION EXPENSES - START
        $administrative_fin_selling_expense = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->where('account_group_id',  3) // expense grp.
        ->whereBetween('transaction_date', [$start_date, $end_date])
        ->selectRaw('sum(deposit) as expense')
        ->value('expense');
        # 9. ADMINISTRATIVE, FINANCIAL, SELLING & DISTRIBUTION EXPENSES - END

        return view('admin.report.statement.income-statement.income-statement',compact('start_date','end_date','income_transaction','sales_return','cost_of_goods_sold','administrative_fin_selling_expense'));
    }

    public function onlyTransactionBalanceBetweenReportingDate($group_id,$start_date, $end_date){

        $all_account = DB::table('tbl_bank_account')
        ->where('tbl_bank_account.account_group_id',$group_id)
        ->orderBy('bank_name', 'asc')
        ->get();

        $dataArray = array();

        foreach ($all_account as $key => $single_account) {

            $bank_account_id = $single_account->bank_account_id;
                
            $initial_opening_balance = DB::table('tbl_bank_account_ledger')
            ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_account_ledger.bank_account_id')
            ->selectRaw('tbl_bank_account_ledger.*,tbl_bank_account.bank_name')
            ->where('tbl_bank_account_ledger.bank_account_id', '=', $bank_account_id)
            ->first();

            $account_name = $initial_opening_balance->bank_name;

            $all_transactions = $this->transaction($bank_account_id, $start_date, $end_date);

            $sum_transaction_balance_debit = 0; 
            $sum_transaction_balance_credit = 0;

            foreach($all_transactions as $key => $single_transaction){

                $sum_transaction_balance_debit += $single_transaction->deposit; 
                $sum_transaction_balance_credit += $single_transaction->expense; 

            }
            $transaction_balance_debit = $sum_transaction_balance_debit;
            $transaction_balance_credit = $sum_transaction_balance_credit;

            if($transaction_balance_debit > 0 || $transaction_balance_credit > 0)
                array_push($dataArray, array('account_name'=>"$account_name",'transaction_balance_debit'=>$transaction_balance_debit,'transaction_balance_credit'=>$transaction_balance_credit));

        } 
        return $dataArray;
    }

    public function transaction($bank_account_id, $start_date, $end_date){
        return DB::table('tbl_bank_transaction')
            ->leftJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.transaction_against')
            ->where('tbl_bank_transaction.bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
            ->get();
    }
    

}