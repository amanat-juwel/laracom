<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Http\Requests;
use App\SalesReturnExchange;
use App\Purchase;
use App\Sales;
use App\SalesDetails;
use App\Customer;
use App\Supplier;
use App\SupplierLedger;
use App\CustomerLedger;
use App\Item;
use App\StockLocation;
use App\StockMaster;
use App\Stock;
use App\BankTransaction;
use App\BankAccountLedger;
use App\Voucher;
use App\Log;
use App\Batch;
use DB;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderShipped;
use App\Mail\SalesInvoice;
use Mail;
use Auth;

class SalesReturnExchangeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){

        $sales_return_exchanges = DB::table('tbl_sales_return_exchange_master')
        ->leftJoin('users','users.id','=','tbl_sales_return_exchange_master.user_id')
        ->orderBy('tbl_sales_return_exchange_master.id', 'desc')
        ->get();

        return view('admin.sales_return_exchange.index' , compact('sales_return_exchanges'));
    }
    public function create()
    {
        
        $sold_items = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        
        ->where('tbl_stock.stock_out','>',0)
        ->groupBy('tbl_item.item_code')
        ->orderBy('tbl_item.item_code', 'asc')
        ->get();

        $new_sales_items = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->selectRaw('tbl_item.*,tbl_category.cata_name,tbl_brand.brand_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_name')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        $customers = DB::table('tbl_customer')
        
        ->orderBy('customer_name', 'asc')
        ->get();

        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();

        $flag = 0;
        try{
            $lastReturnExcMaster = DB::table('tbl_sales_return_exchange_master') 
            ->selectRaw('id')
            ->orderBy('id','dsc')
            ->limit(1)
            ->value('id');
            $flag=1;
        }
        catch(Exception $e) {
            //echo 'Message: ' .$e->getMessage();
         }
        finally{
            if ($flag == 0) {
                $lastReturnExcMaster = 0;
            }
        }

        return view('admin.sales_return_exchange.create',compact('sold_items','new_sales_items','customers','accounts','lastReturnExcMaster'));
    }

    public function allActiveBatch($item_id){
      $all_batch = DB::table('tbl_stock')
        ->selectRaw('SUM(tbl_stock.stock_in) - SUM(tbl_stock.stock_out) as stock, batch_id')
        ->where('item_id',$item_id)
        ->havingRaw('(SUM(tbl_stock.stock_in) - SUM(tbl_stock.stock_out)) > ?', [0])
        ->groupBy('batch_id')
        ->orderBy('stock_id')
        ->get();
        return $all_batch;
    }

    public function generateBatchCode($item_id){

        $item_id = Item::where('item_id', $item_id)->value('item_id');
        $current_month =  date('m');
        $current_year  =  date("y");
        $batch_count   =  Batch::where('item_id', $item_id)->count();
        if($batch_count > 0){
            $batch_count++;
        }else{
            $batch_count = 1;
        }
        $generateBatchCode = $item_id.'-'.$current_month.$current_year.'-'.$batch_count;
        return $generateBatchCode;

    }

    public function store(Request $request)
    {   

        $voucher = DB::table('vouchers')->orderBy('id', 'DESC')->first();
        if(!is_null($voucher)){ $voucher_ref = ++$voucher->id; }
        else{ $voucher_ref = "1"; }

        $voucher = new Voucher;
        $voucher->voucher_ref = $voucher_ref;
        $voucher->type = "Sales Return And Exchange";
        $voucher->save();

        //Purchase start
        $purchase_master_id = $this->purchaseHandler($request, $voucher_ref);
        //Purchase end

        //Sales start
        if($request->out_product_id[0]!=''){
            $sales_master_id = $this->salesHandler($request, $voucher_ref);
        }
        else{
            $sales_master_id = null;
        }
        //Sales end

        $_Master = new SalesReturnExchange;
        $_Master->date = $request->date;
        $_Master->voucher_ref = $voucher_ref;
        $_Master->sales_master_id = $sales_master_id;
        $_Master->purchase_master_id = $purchase_master_id;
        $_Master->user_id = Auth::user()->id;
        $_Master->save();

        return redirect()->back()->with('success','Data Stored Successful');
    }

    public function purchaseHandler($request, $voucher_ref){
        $purchase = new Purchase;
        $purchase->supplier_id = 1; //default
        $purchase->discount = 0;
        $purchase->purchase_date = $request->date;
        $purchase->voucher_ref = $voucher_ref;
        $purchase->status = 'Paid';
        $purchase->purchased_by = Auth::user()->id;
        if($purchase->save()){
            //purchase details start
            $purchase_master_id = $purchase->purchase_master_id;
            $memo_total = 0;
            $string_item = '';

            //START STOCK 
            $stock_master = New StockMaster;
            $stock_master->date = $request->date;
            $stock_master->description = "";
            $stock_master->type = "Sales Return";
            $stock_master->ref_id = $purchase->purchase_master_id;
            $stock_master->user_id = Auth::user()->id;
            $stock_master->save();

            foreach ($request->product_id as $key => $i) 
            {   
                $item_info = Item::find($request->product_id[$key]);
                $string_item .= $item_info->item_name.',';

                //SAVE BATCH INFO
                //$auto_generated_batch_code = $this->generateBatchCode($request->product_id[$key]);

                //no new batch creation is required in the time of sales return
                $last_batch_info = DB::table('tbl_batch')
                ->selectRaw('tbl_batch.id')
                ->where('item_id', '=', $request->product_id[$key])
                ->orderBy('id','dsc')
                ->limit(1)
                ->first(); 

                // $batch = new Batch;
                // $batch->code = $auto_generated_batch_code;
                // $batch->item_id = $request->product_id[$key];
                // $batch->purchase_rate = $last_batch_purchase_rate;
                // $batch->save();

                //STOCK 
                $table_stock = new Stock;
                $table_stock->stock_master_id = $stock_master->id;
                $table_stock->batch_id = $last_batch_info->id;
                $table_stock->item_id = $request->product_id[$key];
                $table_stock->stock_in = $request->qty[$key];
                $table_stock->stock_out = 0;
                $table_stock->particulars = "Sales Return";
                $table_stock->save();

                $memo_total += ($request->qty[$key] * $request->price[$key]);
            }


            $purchaseMaster = Purchase::find($purchase_master_id);
            $purchaseMaster->memo_total = $memo_total;
            $purchaseMaster->advanced_amount = $memo_total;
            $purchaseMaster->update();
            //purchase details end

            # Start Transaction
            $supplier_ledger = new SupplierLedger;
            $supplier_ledger->purchase_master_id = $purchase->purchase_master_id;
            $supplier_ledger->voucher_ref = $voucher_ref;
            $supplier_ledger->supplier_id = $purchase->supplier_id;
            $supplier_ledger->tran_ref_id = 1;
            $supplier_ledger->tran_ref_name = 'BuyProduct';
            $supplier_ledger->debit = 0;
            $supplier_ledger->credit = $memo_total;
            $supplier_ledger->transaction_date = $purchase->purchase_date;  
            $supplier_ledger->save();

            $supplier_ledger = new SupplierLedger;
            $supplier_ledger->purchase_master_id = $purchase->purchase_master_id;
            $supplier_ledger->voucher_ref = $voucher_ref;
            $supplier_ledger->supplier_id = $purchase->supplier_id;
            $supplier_ledger->tran_ref_id = 5;
            $supplier_ledger->tran_ref_name = 'AdvancedPaid';
            $supplier_ledger->debit = $memo_total;
            $supplier_ledger->credit = 0;
            $supplier_ledger->transaction_date = $purchase->purchase_date;            
            $supplier_ledger->save();

            $transaction = new BankTransaction;
            $transaction->voucher_ref = $voucher_ref;
            $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
            $transaction->transaction_date = $request->date;
            $transaction->transaction_description = "Sales Return: ".$string_item;
            $transaction->deposit = 0;
            $transaction->expense = $memo_total;
            $transaction->save();
            # End Transaction
        }

        return $purchase_master_id;
    }


    public function salesHandler($request, $voucher_ref){
        $sales = new Sales;
        $sales->customer_id = $request->customer_id;
        $sales->memo_total = 0;
        $sales->advanced_amount  = 0;
        $sales->discount = 0;
        $sales->sales_date = $request->date;
        $sales->delivery_date = $request->date;
        $sales->voucher_ref = $voucher_ref;
        $sales->sold_by = Auth::user()->id;
        if($sales->save()){
            //sales details start
            $sales_master_id = $sales->sales_master_id;
            $memo_total = 0;
            $string_item = '';
            foreach ($request->out_product_id as $key => $i){
                
                $item_info = Item::find($request->out_product_id[$key]);
                $string_item .= $item_info->item_name.',';

                $salesDetails = new SalesDetails;
                $salesDetails->item_id = $request->out_product_id[$key];
                $salesDetails->sales_master_id = $sales_master_id;
                $salesDetails->quantity = $request->out_qty[$key];
                $salesDetails->sales_price = $request->out_price[$key];
                $salesDetails->is_delivered =  'yes'; 
                $salesDetails->save();

                $memo_total += ($salesDetails->quantity * $salesDetails->sales_price);
            }
            $salesMaster = Sales::find($sales_master_id);
            $salesMaster->memo_total = $memo_total;
            $salesMaster->advanced_amount = $memo_total;
            $salesMaster->update();
            //sales details end

            # Start Transaction
            $transaction = new BankTransaction;
            $transaction->voucher_ref = $voucher_ref;
            $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
            $transaction->transaction_date = $request->date;
            $transaction->transaction_description = substr($string_item, 0, -1);
            $transaction->deposit = $memo_total;
            $transaction->expense = 0;
            $transaction->save();

            DB::table('tbl_customer_ledger')->insert(
                [
                'sales_master_id'=>$sales_master_id,
                'voucher_ref'=>"$voucher_ref",
                'customer_id'=>$request->customer_id,
                'tran_ref_id'=>1,
                'tran_ref_name'=>"BuyProduct",
                'debit'=>$memo_total,
                'credit'=>0,
                'transaction_date'=>$request->date,
                'particulars'=>"Buy - inv $sales_master_id",
                
                ]
            );

            DB::table('tbl_customer_ledger')->insert(
                [
                'sales_master_id'=>$sales_master_id,
                'voucher_ref'=>"$voucher_ref",
                'customer_id'=>$request->customer_id,
                'tran_ref_id'=>5,
                'tran_ref_name'=>"AdvancedPaid",
                'debit'=>0,
                'credit'=>$memo_total,
                'transaction_date'=>$request->date,
                'particulars'=>"Payment - inv $sales_master_id",
                
                ]
            );
            # End Transaction

            //START STOCK MASTER
            $stock_master=new StockMaster;
            $stock_master->date = $request->date;
            $stock_master->description = 'Sales';
            $stock_master->type = 'Sales';
            $stock_master->user_id = Auth::user()->id;
            $stock_master->ref_id = $sales->sales_master_id;
            $stock_master->save();
            //END STOCK MASTER
            //START STOCK DETAILS
            if($stock_master->save()){
              
                foreach ($request->out_product_id as $key => $i)  {
                  //get all active batches of this item
                    $all_active_batches = $this->allActiveBatch($request->out_product_id[$key]);
                    $required_qty = $request->out_qty[$key];
                    foreach ($all_active_batches as $batch) {
                      //check if current batch is satisfying the needs
                      if($batch->stock >= $required_qty){
                        $stock=new Stock;
                        $stock->stock_master_id=$stock_master->id;
                        $stock->batch_id=$batch->batch_id;
                        $stock->item_id=$request->out_product_id[$key];
                        $stock->stock_out=$required_qty;
                        $stock->particulars="Invoice #$sales->sales_master_id";
                        $stock->save();
                        break;
                      }
                      else{
                        $stock=new Stock;
                        $stock->stock_master_id=$stock_master->id;
                        $stock->batch_id=$batch->batch_id;
                        $stock->item_id=$request->out_product_id[$key];
                        $stock->stock_out=$batch->stock;
                        $stock->particulars="Invoice #$sales->sales_master_id";
                        $stock->save();
                        $required_qty -= $batch->stock;
                      }
                    }
                }
            }
            //END STOCK DETAILS
        }

        return $sales_master_id;
    }


    public function show($id){

        return view('admin.sales_return_exchange.show' , compact('_Master','_Details'));
    }

    public function print($id){

        return view('admin.sales_return_exchange.print' , compact('_Master','_Details'));
    }

    public function destroy($id){

        $_Master = SalesReturnExchange::find($id);

        app('App\Http\Controllers\SalesController')->destroy($_Master->sales_master_id);

        app('App\Http\Controllers\PurchaseController')->destroy($_Master->purchase_master_id);

        $_Master->delete();

        return redirect()->back()->with('success','Info Deleted Successfully');

    }

}
