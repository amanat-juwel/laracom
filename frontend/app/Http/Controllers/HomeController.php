<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use App\Item;
use App\Customer;
use App\Stock;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {      
        
        $current_date = date("Y-m-d");
        
        $sale_today = DB::table('tbl_sales_master')
        ->selectRaw('SUM(memo_total) as memo_total')
        ->where('sales_date', '=', $current_date)
        ->value('memo_total');    

        $sale_discount = DB::table('tbl_sales_master')
        ->selectRaw('SUM(discount) as discount')
        ->where('sales_date', '=', $current_date)
        ->value('discount');   

        $due_received = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'PreviousDuePaid')
        ->get();
        foreach ($due_received as $key => $due) {
            $due_received_today = $due->credit;
        }

        $due_paid = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(debit) as debit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'PreviousDuePaid')
        ->get();
        foreach ($due_paid as $key => $due) {
            $due_paid_today = $due->debit;
        }

        $purchase_today = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(debit) as debit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'AdvancedPaid')
        ->get();    
        //var_dump($purchase_today);

        $expense_today = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->where('account_group_id',  3) // expense grp.
        ->where('transaction_date', '=', $current_date)
        ->selectRaw('sum(deposit) as expense')
        ->value('expense');

        $customer_due_today = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit,SUM(debit) as debit')
        ->where('tran_ref_name', '!=', 'PreviousDuePaid')
        ->where('tran_ref_name', '!=', 'OpeningBalance')
        ->where('transaction_date', '=', $current_date)
        ->get();    

        $supplier_due_today = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(credit) as credit,SUM(debit) as debit')
        ->where('tran_ref_name', '!=', 'PreviousDuePaid')
        ->where('tran_ref_name', '!=', 'OpeningBalance')
        ->where('transaction_date', '=', $current_date)
        ->get(); 

        $last_three_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_item.item_name,tbl_sales_details.sales_details_id,tbl_sales_details.memo_no,tbl_sales_details.quantity,tbl_sales_details.sales_price,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_sales_master.created_at,tbl_customer.customer_name,tbl_sales_details.sales_master_id')
        ->orderBy('tbl_sales_details.sales_master_id', 'desc')
        ->limit('3')
        ->get();

        $last_three_purchases = DB::table('tbl_purchase_details')
        ->join('tbl_purchase_master','tbl_purchase_master.purchase_master_id', '=', 'tbl_purchase_details.purchase_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_purchase_details.item_id')
        ->join('tbl_supplier','tbl_supplier.supplier_id', '=', 'tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_item.item_name,tbl_purchase_details.purchase_price,tbl_purchase_master.purchase_date,tbl_purchase_master.advanced_amount,tbl_purchase_master.created_at,tbl_purchase_master.status,tbl_supplier.sup_name,tbl_purchase_details.purchase_master_id')
        ->orderBy('tbl_purchase_details.purchase_master_id', 'desc')
        ->limit('3')
        ->get();

        
        $current_stocks = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.item_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_name')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get(); 
        $in = 0; $out = 0;
        foreach ($current_stocks as $key => $cs) {
            $in += $cs->stock_in; 
            $out += $cs->stock_out; 
        }
        $item_count = $in - $out;
        $opening_stocks = DB::table('tbl_item')
        ->selectRaw('SUM(opening_stock_qty) as opening_stock_qty')
        ->get();
        foreach ($opening_stocks as $key => $os) {
            $item_count += $os->opening_stock_qty; 
        }
        
        $customer_count = DB::table('tbl_customer')->count();
        $supplier_count = DB::table('tbl_supplier')->count();

        $category_count = DB::table('tbl_category')->count();
        $brand_count = DB::table('tbl_brand')->count();
    
        $cashes = DB::table('tbl_bank_transaction')
        ->selectRaw('SUM(deposit) as deposit,SUM(expense) as expense')
        ->where('transaction_date', '=', $current_date)
        ->where('bank_account_id', '=', '4')
        ->get();
        foreach ($cashes as $key => $cash) {
           $cash_in_hand = $cash->deposit - $cash->expense;
        }
        
        ####Profit today
        $date = date('Y-m-d');
        $profit_today = 0;
        $profit_today = 0;

        ##### TOP 5 ITEM SOLD ##### 
        $top_five = DB::table('tbl_sales_details')
        ->select('tbl_sales_details.item_id', DB::raw('COUNT(sales_details_id) as count'))
        ->groupBy('tbl_sales_details.item_id')
        ->orderBy(DB::raw('COUNT(tbl_sales_details.item_id)'), 'DESC')
        ->take(5)
        ->get();
        $top_5_item_sold = array();
        foreach ($top_five as $key => $data) {
           $item = Item::find($data->item_id);
            array_push($top_5_item_sold, array("$item->item_name","$data->count"));
        }
        //echo $top_5_item_sold[0][0]." ".$top_5_item_sold[0][1];
        ##### TOP 5 ITEM SOLD ##### 

        ##### TOP 5 CUSTOMER ##### 
        $top_five_c = DB::table('tbl_sales_master')
        ->select('tbl_sales_master.customer_id', DB::raw('COUNT(sales_master_id) as count'))
        ->groupBy('tbl_sales_master.customer_id')
        ->orderBy(DB::raw('COUNT(tbl_sales_master.customer_id)'), 'DESC')
        ->take(5)
        ->get();
        $top_5_customer = array();
        foreach ($top_five_c as $key => $data) {
           $customer = Customer::find($data->customer_id);
            array_push($top_5_customer, array("$customer->customer_name","$data->count"));
        }
        //echo $top_5_customer[0][0]." ".$top_5_customer[0][1];
        ##### TOP 5 CUSTOMER ##### 

        #### START OF CHART #####
        $yearly_sale = DB::table('tbl_sales_master')
        ->select(DB::raw('YEAR(sales_date) as year') ,DB::raw('MONTH(sales_date) as month'),DB::raw('SUM(memo_total) as paid'))
        ->where(DB::raw('YEAR(sales_date)'), '=', date('Y')) #matching year to sales date year
        ->groupBy(DB::raw('YEAR(sales_date)'))
        ->groupBy(DB::raw('MONTH(sales_date)'))
        ->get();

        //var_dump($yearly_sale);

        $yearly_sales_data = array();
        foreach ($yearly_sale as $key => $single_value) {

            $yearly_sales_data =  $yearly_sales_data + array("$single_value->month"=>"$single_value->paid");

        }

        if(!array_key_exists("1",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("1"=>"0"); 
        }
        if(!array_key_exists("2",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("2"=>"0"); 
        }
        if(!array_key_exists("3",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("3"=>"0"); 
        }
        if(!array_key_exists("4",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("4"=>"0"); 
        }
        if(!array_key_exists("5",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("5"=>"0"); 
        }
        if(!array_key_exists("6",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("6"=>"0"); 
        }
        if(!array_key_exists("7",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("7"=>"0"); 
        }
        if(!array_key_exists("8",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("8"=>"0"); 
        }
        if(!array_key_exists("9",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("9"=>"0"); 
        }
        if(!array_key_exists("10",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("10"=>"0"); 
        }
        if(!array_key_exists("11",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("11"=>"0"); 
        }
        if(!array_key_exists("12",$yearly_sales_data)){
         $yearly_sales_data =  $yearly_sales_data + array("12"=>"0"); 
        }

        #### END OF CHART #####

        $total_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.costing_rate,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_id')
        ->get();
        $inventory_total_amount = 0; $inventory_total_qty = 0;
        foreach ($total_inventory as $key => $value) {
            $inventory_total_qty += ($value->stock_in-$value->stock_out);
            $inventory_total_amount += (($value->stock_in-$value->stock_out) * $value->costing_rate);
        }

        $due_receivable_obj = DB::table('tbl_customer')
        ->leftJoin('tbl_customer_ledger','tbl_customer_ledger.customer_id','=','tbl_customer.customer_id')
        ->selectRaw('tbl_customer.*,SUM(credit) as credit,SUM(debit) as debit')
        ->groupBy('tbl_customer.customer_id')
        ->orderBy('customer_code', 'asc')
        ->get();
        $due_receivable = 0;
        foreach ($due_receivable_obj as $key => $data) {
            $due_receivable += ($data->debit + $data->op_bal_debit - ($data->credit + $data->op_bal_credit));
        }

        #stock_store
        $stockStore = $this->stockStore();

        return view('admin.home',compact('cash_in_hand','profit_today','sale_today','due_received_today','due_paid_today','purchase_today','expense_today','customer_due_today','supplier_due_today','last_three_sales','last_three_purchases','item_count','customer_count','supplier_count','top_5_item_sold','top_5_customer','yearly_sales_data','brand_count','category_count','sale_discount','inventory_total_qty','inventory_total_amount','inventory_vluation','due_receivable','stockStore'));
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

    public function view()
    {
        $settings = DB::table('settings')->where('setting_id','=','1')->first();
        return view('admin.profile.user',compact('settings'));
    }

    public function log()
    {
        $logs = DB::table('logs')
        ->leftJoin('users','users.id','=','logs.user_id')
        ->selectRaw('logs.*,users.name as user')
        ->orderBy('id', 'dsc')
        ->get();
        return view('admin.logs.index',compact('logs'));
    }

    public function settings()
    {
        $settings = DB::table('settings')->where('setting_id','=','1')->first();
        return view('admin.settings.index',compact('settings'));
    }

    
    public function store(Request $request)
    {
        
    }

    public function restore()
    {

        #################
        # PURCHASE
        #################


        #################
        # SALES
        #################


        return "success";
    }

}
