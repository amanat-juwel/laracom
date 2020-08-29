<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\Sales;
use App\SalesMasterAddLess;
use App\Brand;
use App\Customer;
use App\Supplier;
use App\SupplierLedger;
use App\CustomerLedger;
use App\Item;
use App\ItemUnit;
use App\StockLocation;
use App\PurchaseDetails;
use App\SalesDetails;

use App\StockMaster;
use App\Stock;
use App\Log;

use App\BankTransaction;
use App\BankAccountLedger;
use App\Voucher;

use DB;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderShipped;
use App\Mail\SalesInvoice;
// use Illuminate\Support\Facades\Mail;
use Mail;
use Auth;
use Carbon\Carbon;

class SalesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index()
    {
        
        $customers = Customer::all();
        $customer_categories = DB::table('tbl_customer_category')->get();

        $items = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->selectRaw('tbl_item.*,tbl_category.cata_name,tbl_brand.brand_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_name')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        $stock_locations = StockLocation::all()->sortBy("stock_location_name");
        #$item_unit = ItemUnit::all()->where('is_sold',0)->sortBy("imei");
        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();
        
        $references = DB::table('employees')
        ->orderBy('name', 'asc')
        ->get();

        $flag = 0;
        try{
            $lastSalesMasterNo = Sales::all()->last()->sales_master_id;
            $chalan_no = DB::table('tbl_sales_details')->max('chalan_no');
            $lastMemoNo = Sales::all()->last()->memo_no;
            $lastMemoNo += 1;
            $flag=1;
        }
        catch(Exception $e) {
            //echo 'Message: ' .$e->getMessage();
         }
        finally{
            if ($flag == 0) {
                $lastSalesMasterNo = 0;
                $lastMemoNo = 1;
                $chalan_no = 0;
            }
            
        }

        return view('admin.sales.index' , compact('lastMemoNo','lastSalesMasterNo','item_unit','customers','items','accounts','stock_locations','references','chalan_no','customer_categories'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //validation for double click event
        $last_sales = Sales::orderBy('sales_master_id','dsc')->limit(1)->first();
        if(isset($last_sales)){
            $created_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $last_sales->created_at);
            $now = \Carbon\Carbon::now();
            $diff_in_seconds = $created_at->diffInSeconds($now);
            //set 10 seconds waiting time between two sales
            if($diff_in_seconds < 10){
                return redirect()->back()->with('delete','Please wait..');
            }
        }

        //validation for due & payment
        if($request->due < 0 || ($request->advanced_amount > 0 && $request->bank_account_id == '')){
            return redirect('admin/sales')->with('delete','Sales : Failed');
        }

        //success code
        else{
            $voucher = DB::table('vouchers')->orderBy('id', 'DESC')->first();
            if($voucher!=NULL){
                $voucher_ref = ++$voucher->id;
            }
            else{
                $voucher_ref = "1";   
            }

            $voucher = new Voucher;
            $voucher->voucher_ref = $voucher_ref;
            $voucher->type = "Sales";
            $voucher->save();

            $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();

            $sales = new Sales;

            if($request->customer_id != ''){
                $sales->customer_id = $request->customer_id;
            }
            else{
                $sales->customer_id = 1; // By default Walk-In customer
            }

            $sales->memo_no = $request->memo_no;
            $sales->memo_total = $request->memo_total;

            $memo_total_with_out_vat = ($request->memo_total*100)/(100+$globalSettings->vat_percent);
                
            if($request->discount_mode == 'Percentage'){
                $discount_in_tk = ($memo_total_with_out_vat*$request->discount)/100;
            }
            else{
                $discount_in_tk = $request->discount;
            }

            $paid_amount = $request->memo_total-$discount_in_tk;
            ($request->advanced_amount!='')?$sales->advanced_amount = $request->advanced_amount:$sales->advanced_amount = 0;
            
            ($discount_in_tk!='')?$sales->discount = $discount_in_tk:$sales->discount = 0;

            $sales->sales_date = $request->sales_date;
            $sales->delivery_date = $request->delivery_date;
            $sales->sales_note = $request->sales_note;
            $sales->voucher_ref = $voucher_ref;

            $sales->sold_by = Auth::user()->id;
            $sales->reference_by = $request->reference_by;
            $sales->remarks = $request->remarks;
            if($sales->save())
            {
                
                
                $sales_masterId =  $sales->sales_master_id;
                $memo_no = $sales->memo_no;
                $customer_id = $sales->customer_id;
                $buy_product = $sales->memo_total;
                $advanced_paid = $sales->advanced_amount;
                $discount_amount = $sales->discount;
                $trans_date = $sales->sales_date;
                $string_item = '';
                foreach ($request->product_id as $key => $i) 
                {
                    $item_info = Item::find($request->product_id[$key]);
                    $string_item .= $item_info->item_name.',';

                    $salesDetails = new SalesDetails;
                    $salesDetails->item_id = $request->product_id[$key];
                    $salesDetails->sales_master_id = $sales_masterId;
                    $salesDetails->memo_no = $memo_no;
                    $salesDetails->quantity = $request->qty[$key];
                    $salesDetails->sales_price = $request->price[$key];
                    $salesDetails->is_delivered =  'yes'; //$request->is_delivered[$key];
                    $salesDetails->item_note =  $request->item_note[$key];
                    $salesDetails->chalan_no =  $request->chalan_no;
                    $vat = $request->dis[$key]; //dis == vat
                    $salesDetails->item_vat = $vat;
                    $salesDetails->save();
                    
                }

                  //START STOCK MASTER
                  $stock_master=new StockMaster;
                  $stock_master->date = $request->sales_date;
                  $stock_master->description = 'Sales';
                  $stock_master->type = 'Sales';
                  $stock_master->user_id = Auth::user()->id;
                  $stock_master->ref_id = $sales->sales_master_id;
                  $stock_master->save();
                  //END STOCK MASTER
                  //START STOCK DETAILS
                  if($stock_master->save()){
                  
                    foreach ($request->product_id as $key => $i)  {
                      //get all active batches of this item
                        $all_active_batches = $this->allActiveBatch($request->product_id[$key]);
                        $required_qty = $request->qty[$key];
                        foreach ($all_active_batches as $batch) {
                          //check if current batch is satisfying the needs
                          if($batch->stock >= $required_qty){
                            $stock=new Stock;
                            $stock->stock_master_id=$stock_master->id;
                            $stock->batch_id=$batch->batch_id;
                            $stock->item_id=$request->product_id[$key];
                            $stock->stock_out=$required_qty;
                            $stock->particulars="Invoice #$sales->sales_master_id";
                            $stock->save();
                            break;
                          }
                          else{
                            $stock=new Stock;
                            $stock->stock_master_id=$stock_master->id;
                            $stock->batch_id=$batch->batch_id;
                            $stock->item_id=$request->product_id[$key];
                            $stock->stock_out=$batch->stock;
                            $stock->particulars="Invoice #$sales->sales_master_id";
                            $stock->save();
                            $required_qty -= $batch->stock;
                          }
                        }
                      }
                  }
                  //END STOCK DETAILS

                ## START ADD/LESS
                if($request->amount_add_less[0]>0){
                    foreach ($request->particulars as $key => $i) 
                    {
                        $addLess = new SalesMasterAddLess;
                        $addLess->sales_master_id = $sales_masterId;
                        $addLess->particular = $request->particulars[$key];
                        $addLess->amount = $request->amount_add_less[$key];
                        $addLess->save();
                    }
                }
                ## END ADD/LESS

                # Start Bank Transaction
                if($request->advanced_amount > 0){
                    $transaction = new BankTransaction;
                    $transaction->voucher_ref = $voucher_ref;
                    $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
                    $transaction->transaction_date = $request->sales_date;
                    $transaction->transaction_description = substr($string_item, 0, -1);
                    $transaction->deposit = $request->advanced_amount;
                    $transaction->expense = 0;
                    $transaction->save();
                }
                # End Bank Transaction

                if($buy_product>0){
                    
                    $sales_master_id = $sales_masterId;
                    $customer_id = $customer_id;
                    $tran_ref_id = 1;
                    $tran_ref_name = 'BuyProduct';
                    $debit = $buy_product;
                    $credit = 0;
                    $transaction_date = $trans_date;                 
                    // Via QB
                    DB::table('tbl_customer_ledger')->insert(
                        [
                        'sales_master_id'=>"$sales_master_id",
                        'voucher_ref'=>"$voucher_ref",
                        'customer_id'=>"$customer_id",
                        'tran_ref_id'=>"$tran_ref_id",
                        'tran_ref_name'=>"$tran_ref_name",
                        'debit'=>"$debit",
                        'credit'=>"$credit",
                        'transaction_date'=>"$transaction_date",
                        'particulars'=>"Buy - inv $sales_master_id",
                        
                        ]
                        );
               
                }

                if($advanced_paid>0){

                    $sales_master_id = $sales_masterId;
                    $customer_id = $customer_id;
                    $tran_ref_id = 5;
                    $tran_ref_name = 'AdvancedPaid';
                    $debit = 0;
                    $credit = $advanced_paid;
                    $transaction_date = $trans_date;                
                    // Via QB
                    DB::table('tbl_customer_ledger')->insert(
                        [
                        'sales_master_id'=>"$sales_master_id",
                        'voucher_ref'=>"$voucher_ref",
                        'customer_id'=>"$customer_id",
                        'tran_ref_id'=>"$tran_ref_id",
                        'tran_ref_name'=>"$tran_ref_name",
                        'debit'=>"$debit",
                        'credit'=>"$credit",
                        'transaction_date'=>"$transaction_date",
                        'particulars'=>"Payment - inv $sales_master_id",
                                           ]
                        );
                }

                if($discount_amount>0){

                    $sales_master_id = $sales_masterId;
                    $customer_id = $customer_id;
                    $tran_ref_id = 3;
                    $tran_ref_name = 'Discount';
                    $debit = 0;
                    $credit = $discount_amount;
                    $transaction_date = $trans_date;                  
                    // Via QB
                    DB::table('tbl_customer_ledger')->insert(
                        [
                        'sales_master_id'=>"$sales_master_id",
                        'voucher_ref'=>"$voucher_ref",
                        'customer_id'=>"$customer_id",
                        'tran_ref_id'=>"$tran_ref_id",
                        'tran_ref_name'=>"$tran_ref_name",
                        'debit'=>"$debit",
                        'credit'=>"$credit",
                        'transaction_date'=>"$transaction_date",
                        'particulars'=>"Discount - inv $sales_master_id",
                        ]
                        );

                    

                }

            }
        }

        //$sms = $this->sendSms($sales->sales_master_id); // returns true or false

        return redirect('admin/sales')->with('success','Sales info Stored');
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


    public function edit($id)
    {
        //
    }

    // public function emailInvoice($sales_master_id){
        
    //     $orderId = $sales_master_id; 
    //     $sales = Sales::findOrFail($orderId);

    //     $customer = Customer::find($sales->customer_id);
    //     //echo $customer->email;
    //     //var_dump($order);
    //     //Mail::to('lone.hacker.2017@gmail.com')->send(new OrderShipped($sales));
    //     Mail::to("$customer->email")->queue(new SalesInvoice($sales));
    //     if( count(Mail::failures()) > 0 ){
    //        echo "There was one or more failures. They were: <br />";
    //        foreach(Mail::failures as $email_address) {
    //            echo " - $email_address <br />";
    //         }
    //     }
    //     else{
    //         echo "Email sent successfully!";
    //     }

    // }
    public function emailInvoice($sales_master_id){
        
        $settings = DB::table('settings')->where('setting_id','=','1')->first();
        $company_name = $settings->company_name;
        $company_mobile = $settings->mobile;
        $company_address = $settings->address;
        $logo = $settings->logo;

        $orderId = $sales_master_id; 
        $sales = Sales::findOrFail($orderId);

        $customer = Customer::find($sales->customer_id);
        
        if(empty($customer->email)){
            return redirect('admin/sales/sales-details')->with('danger','Failed! This customer has no email address associated to it');
        }

        $to_name = $customer->customer_name;
        $to_mail = $customer->email;

        $data = array('name'=>"$customer->customer_name",'memo_no'=>"$sales->memo_no","memo_total"=>"$sales->memo_total",'discount'=>"$sales->discount",'advanced_amount'=>"$sales->advanced_amount",'to_mail'=>"$to_mail","company_name"=>"$company_name","company_mobile"=>"$company_mobile","company_address"=>"$company_address","logo"=>"$logo");
        Mail::send('email.sales-invoice', $data, function($message) use ($to_mail, $to_name, $company_name) {
        $message->to($to_mail, $to_name)->subject("$company_name Billing System");
        //$message->attach('C:\logo.png');
        $message->from('juwel@v-linknetwork.com',"$company_name");
        });
        if( count(Mail::failures()) > 0 ){
           echo "There was one or more failures. They were: <br />";
           foreach(Mail::failures as $email_address) {
               echo " - $email_address <br />";
            }
        }
        else{
            //echo "<h4 align='center'>Email sent successfully!</h4>";
            return redirect('admin/sales/sales-details')->with('success','Email sent successfully');
        }
    
    }

    public function resendSms($sales_master_id){

        $sms = $this->sendSms($sales_master_id);
        if($sms){
            return redirect('admin/sales/sales-details')->with('success',"SMS sent successfully");
        }
        else{
            return redirect('admin/sales/sales-details')->with('danger',"SMS not sent");
        }
    }

    public function sendSms($sales_master_id){

        $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();

        $sales_master = Sales::find($sales_master_id);
        $customer = Customer::find($sales_master->customer_id);
        $total = $sales_master->memo_total;
        ($sales_master->discount>0)? $discount = $sales_master->discount : $discount =0;
        ($sales_master->advanced_amount>0)? $paid = $sales_master->advanced_amount : $paid =0;
        (($total-$discount-$paid)>0)? $due = ($total-$discount-$paid) : $due =0;

        $formatted_sales_invoice = "SFL-BI-".str_pad($sales_master_id, 8, '0', STR_PAD_LEFT);

        //start send sms
        $numbers = $customer->mobile_no;
        $text = "Dear $customer->customer_name ,Invoice no: $formatted_sales_invoice,Total $total,Disc $discount,Paid $paid,Due $due . Thank You for purchasing our furniture #$globalSettings->company_name";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://107.20.199.106/restapi/sms/1/text/single");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"from\":\"8804445653970\",\"to\":[$numbers],\"text\":\"$text\" }");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: Basic c29oYWlsQHYtbGlua25ldHdvcmsuY29tOnBhc3N3b3JkMjAxOA==";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch),true);
        //print_r($result);
        //$array = $result['messages'][0];
        //echo $array['status']['name'];

        if (curl_errno($ch)) {
            $msg =  'Error:' . curl_error($ch);
            //return redirect('admin/sales/sales-details')->with('danger',$array['status']['name']);
            return false;
        }
        curl_close ($ch);
        //end send sms

        //return redirect('admin/sales/sales-details')->with('success',"'SMS sent successfully'");
        return true;
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($sales_master_id)
    {
        
        $sales_master = Sales::find($sales_master_id);
        $customer = Customer::find($sales_master->customer_id);
        $customer_ledger = CustomerLedger::where('sales_master_id','=', $sales_master_id)->delete();
        $bank_transaction = BankTransaction::where('voucher_ref','=',"$sales_master->voucher_ref")->delete();
        $voucher = Voucher::where('voucher_ref','=',"$sales_master->voucher_ref")->delete();

        $stock_master = DB::table('tbl_stock_master')
        ->selectRaw('tbl_stock_master.id')
        ->where('type','=','Sales')
        ->where('ref_id','=',$sales_master_id)
        ->first();
  
        $stock = Stock::where('stock_master_id','=',$stock_master->id);
        $stock->delete();
            
        $stock_master = StockMaster::where('type','=','Sales')->where('ref_id','=',$sales_master_id)->delete();

        $sales_detail = SalesDetails::where('sales_master_id','=', $sales_master_id)->delete();

        $log = New Log;
        $log->type = "Sales Delete";
        $log->description = "Invoice: $sales_master_id, Memo Total: $sales_master->memo_total, Discount: $sales_master->discount, Customer Paid: $sales_master->advanced_amount. Customer: $customer->customer_name";
        $log->user_id = Auth::user()->id;
        $log->ip_address = $_SERVER['REMOTE_ADDR'];
        $log->save();

        $sales_master->delete();

        return redirect('admin/sales/sales-details')->with('success','Sales Info Deleted');

    }

    public function singleStockDestroy(Request $request,$stock_id){


        $stock = Stock::find($stock_id);

        $stock_master = StockMaster::find($stock->stock_master_id);

        $sales_master = Sales::find($stock_master->ref_id);
        $sales_master->memo_total -= ($stock->stock_out * $request->sales_price); 
        $sales_master->update();

        $customer_ledger = CustomerLedger::where('sales_master_id',$sales_master->sales_master_id)->where('tran_ref_id',1)->first();
        $ledger = CustomerLedger::find($customer_ledger->customer_ledger_id);
        $ledger->debit -= ($stock->stock_out * $request->sales_price); 
        $ledger->update();

        $stock = Stock::where('stock_id',$stock_id)->delete();

        return redirect()->back()->with('success','Stock Deleted');   
    }



    public function show()
    {   
        

        $sales_detail = DB::table('tbl_sales_master')
        ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->leftJoin('users','users.id','=','tbl_sales_master.sold_by')
        ->selectRaw('tbl_sales_master.sales_master_id,tbl_sales_master.voucher_ref,tbl_sales_master.memo_no,tbl_sales_master.memo_total,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_customer.customer_name,tbl_customer.mobile_no,tbl_customer.customer_code,users.name as sold_by,tbl_sales_master.delivery_date')
        ->orderBy('tbl_sales_master.sales_master_id', 'desc')
        ->get();
      
        return view('admin.sales.sales-details' , compact('sales_detail'));
    }

    public function inventorysalesDetails($id)
    {   
        

        $salesMasters = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->leftJoin('employees','employees.id','=','tbl_sales_master.reference_by')
        ->selectRaw('tbl_sales_master.*,tbl_customer.*,employees.name as reference_by')
        ->where('tbl_sales_master.sales_master_id', '=', $id)
        ->first();

        $addLessDetails = DB::table('sales_master_add_less')
        ->where('sales_master_id', '=', $id)
        ->get();

        $paymentHistory = DB::table('tbl_customer_ledger')
        ->where('sales_master_id', '=', $id)
        ->where('tran_ref_id', '!=', 1)
        ->get();

        $sold_by = DB::table('users')->where('users.id', '=', $salesMasters->sold_by)->first();
        
        $references = DB::table('employees')
        ->orderBy('name', 'asc')
        ->get();

        $customerLedger = DB::table('tbl_customer_ledger')
        ->where('sales_master_id', '=', $id)
        ->get();

        $debit = 0;
        $credit = 0;          
        foreach($customerLedger as $customer_ledger)
        {
            $debit += $customer_ledger->debit;
            $credit += $customer_ledger->credit;
        }
        $due = $debit-$credit;

        $salesDetail = DB::table('tbl_stock')
        ->leftJoin('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
        ->leftJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->leftJoin('tbl_sales_details','tbl_sales_details.item_id', '=', 'tbl_stock.item_id')
        ->leftJoin('tbl_batch','tbl_batch.id', '=', 'tbl_stock.batch_id')
        ->where('type', '=', 'Sales')
        ->where('ref_id', '=', $id)
        ->get();


        $items = Item::all()->sortBy("item_name");
        $stock_locations = StockLocation::all()->sortBy("stock_location_name");

        $customers = Customer::all()->sortBy('customer_name');
        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();
        //var_dump($salesMaster);

        $flag = 0;
        try{

            $chalan_no = DB::table('tbl_sales_details')->max('chalan_no');
            $flag=1;
        }
        catch(Exception $e) {
            //echo 'Message: ' .$e->getMessage();
        }
        finally{
            if ($flag == 0) {
                $chalan_no = 0;
            }
            
        }

        return view('admin.sales.inventory-sales-details', compact('customers','salesMasters','paymentHistory','sold_by','customerLedger', 'salesDetail','due','items','stock_locations','accounts','stock_locations','references','chalan_no','addLessDetails'))->with('id',$id);

    }

    public function invoiceDetails($id)
    {

        $salesMasters = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->leftJoin('employees','employees.id','=','tbl_sales_master.reference_by')
        ->selectRaw('tbl_sales_master.*,tbl_customer.*,employees.name as reference_by')
        ->where('tbl_sales_master.sales_master_id', '=', $id)
        ->first();
        //var_dump($salesMaster);

        
        $sold_by = DB::table('users')->where('users.id', '=', $salesMasters->sold_by)->first();

        $customerLedger = DB::table('tbl_customer_ledger')
        ->where('sales_master_id', '=', $id)
        ->get();

        $debit = 0;
        $credit = 0;          
        foreach($customerLedger as $customer_ledger)
        {
            $debit += $customer_ledger->debit;
            $credit += $customer_ledger->credit;
        }
        $due = $debit-$credit;

        $salesDetail = DB::table('tbl_sales_details')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        //->join('tbl_item_unit','tbl_item_unit.tbl_item_unit_id', '=', 'tbl_sales_details.tbl_item_unit_id')
        ->join('tbl_category','tbl_category.cata_id', '=', 'tbl_item.cata_id')
        ->leftJoin('tbl_sub_category','tbl_sub_category.id', '=', 'tbl_item.sub_cata_id')
        //->join('tbl_stock_location','tbl_stock_location.stock_location_id', '=', 'tbl_sales_details.stock_location_id')
        ->where('sales_master_id', '=', $id)
        ->get();

        $addLessDetails = DB::table('sales_master_add_less')
        ->where('sales_master_id', '=', $id)
        ->get();
        //return dd($addLessDetails);

        $items = Item::all();

        return view('admin.sales.invoice', compact('salesMasters','sold_by','customerLedger', 'salesDetail','due','items','stock_locations','addLessDetails'))->with('id',$id);
    }

    public function chalanDetails($id)
    {

        $salesMasters = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->leftJoin('tbl_expense_head','tbl_expense_head.expense_head_id','=','tbl_sales_master.reference_by')
        ->selectRaw('tbl_sales_master.*,tbl_customer.*,tbl_expense_head.expense_head as reference_by')
        ->where('tbl_sales_master.sales_master_id', '=', $id)
        ->first();
        //var_dump($salesMaster);

        $sold_by = DB::table('users')->where('users.id', '=', $salesMasters->sold_by)->first();

        $customerLedger = DB::table('tbl_customer_ledger')
        ->where('sales_master_id', '=', $id)
        ->get();

        $debit = 0;
        $credit = 0;          
        foreach($customerLedger as $customer_ledger)
        {
            $debit += $customer_ledger->debit;
            $credit += $customer_ledger->credit;
        }
        $due = $debit-$credit;

        $salesDetail = DB::table('tbl_sales_details')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        //->join('tbl_item_unit','tbl_item_unit.tbl_item_unit_id', '=', 'tbl_sales_details.tbl_item_unit_id')
        ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_item.brand_id')
        //->join('tbl_stock_location','tbl_stock_location.stock_location_id', '=', 'tbl_sales_details.stock_location_id')
        ->where('sales_master_id', '=', $id)
        ->get();

        $items = Item::all();

        return view('admin.sales.chalan', compact('salesMasters','sold_by','customerLedger', 'salesDetail','due','items','stock_locations'))->with('id',$id);
    }

    public function updateSalesMaster(Request $request, $id)
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
        $voucher->type = "Sales";
        $voucher->voucher_description = "Due receive";
        $voucher->date = $request->input('updated_at');
        $voucher->save();

        $due = $request->due;
        $discount = ($request->discount!='')?$request->discount:0;;
        $paid = ($request->paid!='')?$request->paid:0;;
        
        //Update Sales Master
        $sales = Sales::find($id);
        $previous_advanced_amount = $sales->advanced_amount; 
        $previous_discount = $sales->discount;
        $sales->advanced_amount = $request->input('paid') + $previous_advanced_amount;
        $sales->discount = $request->input('discount')+ $previous_discount;

        
        $sales->update();

        //Update Customer Ledger
        if($request->input('paid')>0){

            # Start Bank Transaction
            $transaction = new BankTransaction;
            $transaction->voucher_ref = $voucher_ref;
            if($request->bank_account_id!=''){
                $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
            }
            else{
                $transaction->bank_account_id = 4;
            }
            $transaction->transaction_date = $request->input('updated_at');
            $transaction->transaction_description = "Amount Received for invoice $sales->memo_no";
            $transaction->deposit = $paid;
            $transaction->expense = 0;
            $transaction->save();
            # End Bank Transaction

            $customer_ledger = new CustomerLedger;
            $customer_ledger->voucher_ref = $voucher_ref;
            $customer_ledger->sales_master_id = $request->input('sales_master_id');
            $customer_ledger->customer_id = $request->input('customer_id');
            $customer_ledger->tran_ref_id = 7;
            $customer_ledger->tran_ref_name = 'PreviousDuePaid';
            $customer_ledger->debit = 0;
            $customer_ledger->credit = $request->input('paid');
            $customer_ledger->transaction_date = $request->input('updated_at');       
            $customer_ledger->save();
        }

        if($request->input('discount')>0){
            $customer_ledger = new CustomerLedger;
            $customer_ledger->voucher_ref = $voucher_ref;
            $customer_ledger->sales_master_id = $request->input('sales_master_id');
            $customer_ledger->customer_id = $request->input('customer_id');
            $customer_ledger->tran_ref_id = 3;
            $customer_ledger->tran_ref_name = 'Discount';
            $customer_ledger->debit = 0;
            $customer_ledger->credit = $request->input('discount');
            $customer_ledger->transaction_date = $request->input('updated_at');       
            $customer_ledger->save();

        }
        
        return redirect()->back()->with('success','Invoice updated successfully');
    }
    
    public function updateInvoiceInfo(Request $request, $sales_master_id)
    {

        $created_at = $request->created_at;
        $updated_at = $request->updated_at;
        $reference_by_old = $request->reference_by_old;
        $reference_by = $request->reference_by;
        $customer_id_old = $request->customer_id_old;
        $customer_id = $request->customer_id;
        $discount_old = $request->discount_old;
        $discount = $request->discount;
        $advanced_amount_old = $request->advanced_amount_old;
        $advanced_amount = $request->advanced_amount;

        $sales_master = Sales::find($sales_master_id);
        $sales_master->delivery_date = $request->delivery_date;
        $sales_master->sales_note = $request->sales_note;
        $sales_master->update();

        $log_string = "Invoice No: $sales_master_id. ";

        if($created_at != $updated_at){
            $sales_master = Sales::find($sales_master_id);
            $sales_master->sales_date = $updated_at;
            $sales_master->update();

            $customer_ledger = CustomerLedger::where('sales_master_id',$sales_master_id);
            $customer_ledger->update(['transaction_date'=>$updated_at]);

            $transaction = BankTransaction::where('voucher_ref',$sales_master->voucher_ref);
            $transaction->update(['transaction_date'=>$updated_at]);

            $all_sales_details_id = DB::table('tbl_sales_details')
            ->selectRaw('tbl_sales_details.sales_details_id')
            ->where('sales_master_id','=',$sales_master_id)
            ->get();

            foreach ($all_sales_details_id as $key => $sales_details_id) {   
                $sales_details_id = $sales_details_id->sales_details_id;
                $stock = Stock::where('sales_details_id',$sales_details_id);
                $stock->update(['stock_change_date'=>$request->updated_at]);     
            }

            $log_string = 'Date updated from $created_at to $updated_at. ';

        }
        if($reference_by_old != $reference_by){
            $sales_master = Sales::find($sales_master_id);
            $sales_master->reference_by = $reference_by;
            $sales_master->update();
            $log_string .= "Ref updated from $reference_by_old to $reference_by";
        }
        if($customer_id_old != $customer_id){
            $sales_master = Sales::find($sales_master_id);
            $sales_master->customer_id = $customer_id;
            $sales_master->update();

            $customer_ledger = CustomerLedger::where('sales_master_id',$sales_master_id);
            $customer_ledger->update(['customer_id'=>$customer_id]);

            $log_string .= "Customer updated from $customer_id_old to $customer_id";
        }
        if($discount_old != $discount){
            $sales_master = Sales::find($sales_master_id);
            $sales_master->discount = $discount;
            $sales_master->update();

            $log_string .= "Discount updated from $discount_old to $discount";

       
            

            $customer_ledger = CustomerLedger::where('sales_master_id',$sales_master_id)->where('tran_ref_name','Discount')->first();
            if(count($customer_ledger)==1){
                $customer_ledger->update(['credit'=>$discount]);
            }
            else{
                //insert discount
                $tran_ref_id = 3;
                $tran_ref_name = 'Discount';
                $debit = 0;
                $credit = $discount;
                $transaction_date = $updated_at;                  
                // Via QB
                DB::table('tbl_customer_ledger')->insert(
                    [
                    'sales_master_id'=>"$sales_master_id",
                    'voucher_ref'=>"$sales_master->voucher_ref",
                    'customer_id'=>"$customer_id",
                    'tran_ref_id'=>"$tran_ref_id",
                    'tran_ref_name'=>"$tran_ref_name",
                    'debit'=>"$debit",
                    'credit'=>"$credit",
                    'transaction_date'=>"$transaction_date",
                    'particulars'=>"Discount  - inv $sales_master_id",
                    ]
                    );

            }
        }
        if($advanced_amount_old != $advanced_amount){
            $sales_master = Sales::find($sales_master_id);
            $sales_master->advanced_amount = $advanced_amount;
            $sales_master->update();

            $customer_ledger = CustomerLedger::where('sales_master_id',$sales_master_id)->where('tran_ref_name','AdvancedPaid');
            $customer_ledger->update(['credit'=>$advanced_amount]);

            $transaction = BankTransaction::where('voucher_ref',$sales_master->voucher_ref);
            $transaction->update(['deposit'=>$advanced_amount]);

            $log_string .= "Advanced Amount updated from $advanced_amount_old to $advanced_amount";
        }

        $log = New Log;
        $log->type = "Sales Edit";
        $log->description = "$log_string";
        $log->user_id = Auth::user()->id;
        $log->ip_address = $_SERVER['REMOTE_ADDR'];
        $log->save();

        return redirect("sales/memo_details/$sales_master_id")->with('success','Invoice Updated Successfully');
    }
    
    public function editItemUnit($sales_master_id,$sales_details_id)
    {
        $itemUnitById = DB::table('tbl_sales_details')
        ->leftJoin('tbl_stock','tbl_stock.sales_details_id', '=', 'tbl_sales_details.sales_details_id')
        ->where('tbl_sales_details.sales_details_id', $sales_details_id)
        ->first();

        $items = Item::all();
        $stock_locations = StockLocation::all()->sortBy("stock_location_name");
        //var_dump($items)
        return view('admin.sales.item-unit-edit', compact('sales_master_id','itemUnitById','items','stock_locations'));
    }

    public function updateItemUnit(Request $request, $sales_master_id)
    {
        $sales_details_id = $request->sales_details_id;

        ####### Update Stock Location ########
        if($request->input('stock_location_id_old') != $request->input('stock_location_id')){
            $stock = Stock::where('sales_details_id',$sales_details_id);
            $stock->update(['stock_location_id'=>$request->input('stock_location_id')]);
        }

        ####### Update Item Id #######
        if($request->item_id_old != $request->item_id){
            $sales_details = SalesDetails::where('sales_details_id',$sales_details_id)->first();
            $sales_details->update(['item_id'=>$request->input('item_id')]);

            $stock = Stock::where('sales_details_id',$sales_details_id)->first();
            $stock->update(['item_id'=>$request->input('item_id')]);

        }

        ####### Update Quantity ########
        if($request->input('quantity_old') != $request->input('quantity')){
        $sales_details = SalesDetails::find($sales_details_id);
        $sales_details->quantity = $request->input('quantity'); 
        $sales_details->update();

        $stock = Stock::where('sales_details_id',$sales_details_id);
        $stock->update(['stock_out'=>$request->input('quantity')]);
        }

        ####### Update Quantity ########

        $sales_details = SalesDetails::find($sales_details_id);
        $sales_details->chalan_no = $request->input('chalan_no'); 
        $sales_details->update();

        ####### Update Price #######
        if($request->input('quantity_old') == $request->input('quantity') && $request->input('sales_price_old') != $request->input('sales_price')){
            $added_sales_price = $request->input('quantity') * ($request->input('sales_price') - $request->input('sales_price_old') );
        }
        elseif($request->input('quantity_old') != $request->input('quantity') && $request->input('sales_price_old') == $request->input('sales_price')){
            $added_sales_price = ( ($request->input('quantity') * $request->input('sales_price')) - ($request->input('quantity_old') * $request->input('sales_price')) );
        }
        elseif($request->input('quantity_old') != $request->input('quantity') && $request->input('sales_price_old') != $request->input('sales_price')){
            $added_sales_price = ( ($request->input('quantity') * $request->input('sales_price')) - ($request->input('quantity_old') * $request->input('sales_price_old')) );
        }
        else{
            $added_sales_price = 0;
        }   
            ####### Update SalesDetails #######
            $sales_details = SalesDetails::where('sales_details_id',$sales_details_id);
            $sales_details->update(['sales_price'=>$request->input('sales_price')]);

            $id = $sales_master_id;
            $sales = Sales::find($id);
            $previous_memo_total = $sales->memo_total; 
            $sales->memo_total = $added_sales_price + $previous_memo_total;
            
            $sales->update();

            ####### Update Customer ledger #######
            $customer_ledgers = CustomerLedger::where('sales_master_id','=', $id)->where('tran_ref_id','=', 1)->get();
            foreach ($customer_ledgers as $key => $customer_ledger) {
                $customer_ledger_id = $customer_ledger->customer_ledger_id;
                $previous_debit = $customer_ledger->debit;
            }
            $debit = $added_sales_price + $previous_debit;
            $cus_ledger = CustomerLedger::find($customer_ledger_id);
            $cus_ledger->debit = $debit;
            $cus_ledger->update();

        return redirect("sales/memo_details/$sales_master_id")->with('success','Updated Successfully');
    }

    public function addNewItemToInvoice(Request $request, $sales_master_id)
    {
       
        $stock_master_id = StockMaster::where('type','Sales')->where('ref_id',$sales_master_id)->value('id');

        //get all active batches of this item
        $all_active_batches = $this->allActiveBatch($request->item_id);
        $required_qty = $request->quantity;
        foreach ($all_active_batches as $batch) {
          //check if current batch is satisfying the needs
          if($batch->stock >= $required_qty){
            $stock=new Stock;
            $stock->stock_master_id=$stock_master_id;
            $stock->batch_id=$batch->batch_id;
            $stock->item_id=$request->item_id;
            $stock->stock_out=$required_qty;
            $stock->particulars="Invoice #$sales_master_id";
            $stock->save();
            break;
          }
          else{
            $stock=new Stock;
            $stock->stock_master_id=$stock_master_id;
            $stock->batch_id=$batch->batch_id;
            $stock->item_id=$request->item_id;
            $stock->stock_out=$batch->stock;
            $stock->particulars="Invoice #$sales_master_id";
            $stock->save();
            $required_qty -= $batch->stock;
          }
        }


        $sales_master = Sales::find($sales_master_id);
        $sales_master->memo_total += ($request->rate * $request->quantity); 
        $sales_master->update();

        $salesDetails = new SalesDetails;
        $salesDetails->item_id = $request->item_id;
        $salesDetails->sales_master_id = $sales_master_id;
        $salesDetails->quantity = $request->quantity;
        $salesDetails->sales_price = $request->rate;
        $salesDetails->is_delivered =  'yes'; //$request->is_delivered[$key];
        $salesDetails->item_note =  "";
        $salesDetails->chalan_no =  "";
        $vat = 0; 
        $salesDetails->item_vat = $vat;
        $salesDetails->save();

        $customer_ledger = CustomerLedger::where('sales_master_id',$sales_master_id)->where('tran_ref_id',1)->first();
        $ledger = CustomerLedger::find($customer_ledger->customer_ledger_id);
        $ledger->debit += ($request->rate * $request->quantity); 
        $ledger->update();



        return redirect()->back()->with('success','New Item Added');

    }

    public function deliverItem(Request $request)
    {

        $sales_details_id = $request->sales_details_id;

        $salesDetails = SalesDetails::find($sales_details_id);
        $salesDetails->is_delivered = 'yes';
        $salesDetails->chalan_no = $request->chalan_no;
        $salesDetails->update();
        //var_dump($salesDetails);

        $table_stock = new Stock;
        $table_stock->item_id = $salesDetails->item_id;
        $table_stock->stock_location_id = 1;
        $table_stock->purchase_details_id = 0;
        $table_stock->sales_details_id = $sales_details_id;
        $table_stock->stock_in = 0;
        $table_stock->stock_out = 1;
        $table_stock->stock_change_date = date('Y-m-d');
        $table_stock->save();
        
        return redirect()->back()->with('success','Item marked as delivered');
    }


    public function printMoneyReceipt($sales_master_id,$voucher_ref)
    {

        $customer_ledgers = DB::table('tbl_customer_ledger')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_customer_ledger.customer_id')
        ->where('voucher_ref', '=', $voucher_ref)
        ->get();

        $transactions = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->where('voucher_ref', '=', $voucher_ref)
        ->first();

        $sales_master = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->selectRaw('tbl_sales_master.*,tbl_customer.*')
        ->where('tbl_sales_master.sales_master_id', '=', $sales_master_id)
        ->first();

        $paymentHistory = DB::table('tbl_customer_ledger')
        ->where('sales_master_id', '=', $sales_master_id)
        ->where('tran_ref_id', '!=', 1)
        //->where('voucher_ref', '!=', $voucher_ref)
        ->get();

        return view('admin.sales.invoice-ledger',compact('customer_ledgers','transactions','sales_master','paymentHistory'));

    }

    public function editAddLess($add_less_id)
    {

        $add_less = SalesMasterAddLess::find($add_less_id);
        return response()->json([
                'add_less_id' =>$add_less_id,
                'particular' => $add_less->particular,
                'amount' => $add_less->amount
            ]); 

    }

    public function updateAddLess(Request $request)
    {

        $add_less_id = $request->input('add_less_id');
        $particular = $request->input('particular');
        $amount_old = $request->input('amount_old');
        $amount = $request->input('amount');

        $add_less = SalesMasterAddLess::find($add_less_id);
        $add_less->particular = $particular;
        $add_less->amount = $amount;
        $add_less->update();

        if($amount_old != $amount){
            $diff = $amount - $amount_old;

            $sales = Sales::find($add_less->sales_master_id);
            $previous_memo_total = $sales->memo_total; 
            $sales->memo_total = $diff + $previous_memo_total;
            $sales->update();

            ####### Update Customer ledger #######
            $customer_ledgers = CustomerLedger::where('sales_master_id','=', $add_less->sales_master_id)->where('tran_ref_id','=', 1)->get();
            foreach ($customer_ledgers as $key => $customer_ledger) {
                $customer_ledger_id = $customer_ledger->customer_ledger_id;
                $previous_debit = $customer_ledger->debit;
            }
            $debit = $diff + $previous_debit;
            $cus_ledger = CustomerLedger::find($customer_ledger_id);
            $cus_ledger->debit = $debit;
            $cus_ledger->update();   


      
        }

        return redirect()->back()->with('success','Add/Less info updated');
    }


}
