<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\Sales;
use App\SalesReturn;
use App\Brand;
use App\Customer;
use App\Supplier;
use App\Item;
use App\ItemUnit;
use App\StockLocation;
use App\PurchaseDetails;
use App\salesReturnDetails;
use App\SupplierLedger;
use App\CustomerLedger;
use App\Stock;
use App\BankTransaction;
use App\BankAccountLedger;
use App\Voucher;
use App\ExpenseHead;
use DB;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderShipped;
use App\Mail\SalesInvoice;
// use Illuminate\Support\Facades\Mail;
use Mail;
use Auth;

class SalesReturnController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $customers = Customer::all();
        $items = Item::all()->sortBy("item_name");
        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();
        return view('sales_return.index' , compact('customers','items','accounts'));
    }

    public function store(Request $request){
        if($request->due < 0 || ($request->advanced_amount > 0 && $request->bank_account_id == '')){

            // $Response   = array(
            // 'success' => '0',
            // 'error' => 'My Flash Message'
            //  );

            // return $Response;
            return redirect('/sales-return')->with('delete','Sales Return : Failed');

        }

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
            $voucher->type = "SalesReturn";
            $voucher->save();


            $sales_return = new SalesReturn;
            $sales_return->memo_total = $request->memo_total;
            ($request->advanced_amount!='')?$sales_return->advanced_amount = $request->advanced_amount:$sales_return->advanced_amount = 0;
            $sales_return->discount = ($request->discount!='')?$request->discount:0;
            $sales_return->reason = $request->reason;
            $sales_return->customer_id = $request->customer_id;
            $sales_return->date = $request->date;
            $sales_return->voucher_ref = $voucher_ref;
            $sales_return->sales_master_id = $request->sales_master_id;
            if(($request->due)>0){
                $sales_return->status = 'Due';    
            }else{
                $sales_return->status = 'Paid';
            }
            $sales_return->received_by = Auth::user()->id;
            if($sales_return->save())
            {
                # Start Bank Transaction
                if($request->advanced_amount > 0){
                    $transaction = new BankTransaction;
                    $transaction->voucher_ref = $voucher_ref;
                    $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
                    $transaction->transaction_date = $request->date;
                    $transaction->transaction_description = "Item(s) Returned; invoice $sales_return->voucher_ref";
                    $transaction->deposit = 0;
                    $transaction->expense = $request->advanced_amount;
                    $transaction->save();
                }
                # End Bank Transaction
                
                $sales_return_master_id =  $sales_return->sales_return_master_id;
                $customer_id = $sales_return->customer_id;
                $memo_total = $sales_return->memo_total;
                $advanced_paid = $sales_return->advanced_amount;
                $discount_amount = $sales_return->discount;
                $trans_date = $sales_return->date;
                
                foreach ($request->product_id as $key => $i) 
                {

                    $salesReturnDetails = new SalesReturnDetails;
                    $salesReturnDetails->item_id = $request->product_id[$key];
                    $salesReturnDetails->sales_return_master_id = $sales_return_master_id;
                    #$salesReturnDetails->stock_location_id = $request->stock_location_id[$key];
                    $salesReturnDetails->quantity = $request->qty[$key];
                    $salesReturnDetails->rate = $request->price[$key];

                    // //set item unit sold
                    // $tbl_item_unit_id= $request->tbl_item_unit_id[$key];
                    // $sales_price = $request->sales_price[$key];
                    // DB::table('tbl_item_unit')
                    // ->where('tbl_item_unit_id', $tbl_item_unit_id)
                    // ->update(['item_unit_sales_price' => $sales_price,'is_sold' => 1]);
                    // //set item unit sold
                    // //Storing Item Ubit number 
                    // //$tbl_item_unit_id = ItemUnit::where('imei', $imei)->first()->tbl_item_unit_id;
                    // $salesReturnDetails->tbl_item_unit_id = $tbl_item_unit_id ;
                    // //Storing Item Ubit number 

                    $vat = $request->dis[$key]; //dis == vat
                    // $v = ($salesReturnDetails->quantity)*($salesReturnDetails->sales_price);
                    // if($vat==0)
                    // {
                    //     $x = 0;
                    // }else{
                    //     $x = $v*($vat/100);
                    // }
                    $salesReturnDetails->item_vat = $vat;

                    if($salesReturnDetails->save())
                    {
                        $table_stock = new Stock;
                        $sales_return_details_id = $salesReturnDetails->sales_return_details_id;
                        $table_stock->item_id = $request->product_id[$key];
                        $table_stock->stock_location_id = ($request->stock_location_id[$key]!='')?$request->stock_location_id[$key]:1;
                        $table_stock->purchase_details_id = 0;
                        $table_stock->sales_details_id = 0;
                        $table_stock->sales_return_details_id = $sales_return_details_id;
                        $table_stock->stock_in = $request->qty[$key];
                        $table_stock->stock_out = 0;
                        $table_stock->stock_change_date = $trans_date;
                        $table_stock->save();

                    }
                }

                if($memo_total>0){
                    
                    $customer_id = $customer_id;
                    $tran_ref_id = 30;
                    $tran_ref_name = 'ReturnProduct';
                    $debit = 0;
                    $credit = $memo_total;
                    $transaction_date = $trans_date;                 
                    // Via QB
                    DB::table('tbl_customer_ledger')->insert(
                        [
                        'voucher_ref'=>"$voucher_ref",
                        'customer_id'=>"$customer_id",
                        'tran_ref_id'=>"$tran_ref_id",
                        'tran_ref_name'=>"$tran_ref_name",
                        'debit'=>"$debit",
                        'credit'=>"$credit",
                        'transaction_date'=>"$transaction_date",
                        
                        ]
                        );
                }

                if($advanced_paid>0){

                    $customer_id = $customer_id;
                    $tran_ref_id = 31;
                    $tran_ref_name = 'ReturnedAmount';
                    $debit = $advanced_paid;
                    $credit = 0;
                    $transaction_date = $trans_date;                
                    // Via QB
                    DB::table('tbl_customer_ledger')->insert(
                        [
                        'voucher_ref'=>"$voucher_ref",
                        'customer_id'=>"$customer_id",
                        'tran_ref_id'=>"$tran_ref_id",
                        'tran_ref_name'=>"$tran_ref_name",
                        'debit'=>"$debit",
                        'credit'=>"$credit",
                        'transaction_date'=>"$transaction_date",
                                           ]
                        );
                }

                if($discount_amount>0){

                    $customer_id = $customer_id;
                    $tran_ref_id = 32;
                    $tran_ref_name = 'ReturnedDiscount';
                    $debit = $discount_amount;
                    $credit = 0;
                    $transaction_date = $trans_date;                  
                    // Via QB
                    DB::table('tbl_customer_ledger')->insert(
                        [
                        'voucher_ref'=>"$voucher_ref",
                        'customer_id'=>"$customer_id",
                        'tran_ref_id'=>"$tran_ref_id",
                        'tran_ref_name'=>"$tran_ref_name",
                        'debit'=>"$debit",
                        'credit'=>"$credit",
                        'transaction_date'=>"$transaction_date",
                        ]
                        );

                }

            }
        }

        $sms = $this->sendSms($sales_return->sales_return_master_id); // returns true or false

        return redirect('/sales-return')->with('success','Sales Return info Stored');
    }


    public function sendSms($sales_return_master_id){

        $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();

        $sales_return = SalesReturn::find($sales_return_master_id);
        $customer = Customer::find($sales_return->customer_id);
        $total = number_format($sales_return->memo_total,2);
        $paid = number_format($sales_return->advanced_amount,2);
        $due = number_format($sales_return->due,2);
        //start send sms
        $numbers = $customer->mobile_no;
        $text = "Sales Return completed successfully. We are sorry for the inconvenience. #$globalSettings->company_name";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://107.20.199.106/restapi/sms/1/text/single");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"from\":\"InfoSMS\",\"to\":[$numbers],\"text\":\"$text\" }");
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
            //return redirect('/sales/sales-details')->with('danger',$array['status']['name']);
            return false;
        }
        curl_close ($ch);
        //end send sms

        //return redirect('/sales/sales-details')->with('success',"'SMS sent successfully'");
        return true;
    }


    public function showAll(){

        $all_sales_return = DB::table('tbl_sales_return_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_return_master.customer_id')
        ->leftJoin('users','users.id','=','tbl_sales_return_master.received_by')
        ->selectRaw('tbl_sales_return_master.*,tbl_customer.customer_name,users.name as received_by')
        ->orderBy('tbl_sales_return_master.sales_return_master_id', 'desc')
        ->get();

        return view('sales_return.list' , compact('all_sales_return'));
    }

    public function show($sales_return_master_id){
        
        $sales_return = SalesReturn::find($sales_return_master_id);

        $singleSalesReturn = DB::table('tbl_sales_return_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_return_master.customer_id')
        ->selectRaw('tbl_sales_return_master.*,tbl_customer.*')
        ->where('tbl_sales_return_master.sales_return_master_id', '=', $sales_return_master_id)
        ->first();

        $received_by = DB::table('users')->where('users.id', '=', $singleSalesReturn->received_by)->first();

        $customerLedger = DB::table('tbl_customer_ledger')
        ->where('voucher_ref', '=', $sales_return->voucher_ref)
        ->get();


        $due = $singleSalesReturn->memo_total - $singleSalesReturn->advanced_amount - $singleSalesReturn->discount;

        $salesReturnDetails = DB::table('tbl_sales_return_details')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_return_details.item_id')
        //->join('tbl_item_unit','tbl_item_unit.tbl_item_unit_id', '=', 'tbl_sales_details.tbl_item_unit_id')
        //->join('tbl_stock_location','tbl_stock_location.stock_location_id', '=', 'tbl_sales_details.stock_location_id')
        ->where('sales_return_master_id', '=', $sales_return_master_id)
        ->get();


        $items = Item::all()->sortBy("item_name");
        $stock_locations = StockLocation::all()->sortBy("stock_location_name");

        $customers = Customer::all()->sortBy('customer_name');
        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();
        //var_dump($salesMaster);
        return view('sales_return.inventory-sales-return-details', compact('customers','singleSalesReturn','received_by','customerLedger', 'salesReturnDetails','due','items','stock_locations','accounts','stock_locations','references'))->with('sales_return_master_id',$sales_return_master_id);
    }

    public function updatePayment(Request $request,$sales_return_master_id){
        
        $due = $request->due;
        $discount = ($request->discount!='')?$request->discount:0;;
        $paid = ($request->paid!='')?$request->paid:0;
        
        //Update Sales Master
        $sales_return = SalesReturn::find($sales_return_master_id);
        $previous_advanced_amount = $sales_return->advanced_amount; 
        $previous_discount = $sales_return->discount;
        $sales_return->advanced_amount = $request->input('paid') + $previous_advanced_amount;
        $sales_return->discount = $request->input('discount')+ $previous_discount;

        if(($request->due)>0){
            $sales_return->status = 'Due';    
        }else{
            $sales_return->status = 'Paid';
        }
        
        $sales_return->update();

        //Update Customer Ledger
        if($request->input('paid')>0){

            # Start Bank Transaction
            $transaction = new BankTransaction;
            $transaction->voucher_ref = $sales_return->voucher_ref;
            if($request->bank_account_id!=''){
                $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
            }
            else{
                $transaction->bank_account_id = 4;
            }
            $transaction->transaction_date = $request->input('updated_at');
            $transaction->transaction_description = "Sales Return Due Paid for Voucher Ref $sales_return->voucher_ref";
            $transaction->deposit = 0;
            $transaction->expense = $paid;
            $transaction->save();
            # End Bank Transaction

            $customer_ledger = new CustomerLedger;
            $customer_ledger->voucher_ref = $sales_return->voucher_ref;
            $customer_ledger->customer_id = $request->input('customer_id');
            $customer_ledger->tran_ref_id = 33;
            $customer_ledger->tran_ref_name = 'PreviousReturnDuePaid';
            $customer_ledger->debit =  $request->input('paid');
            $customer_ledger->credit = 0;
            $customer_ledger->transaction_date = $request->input('updated_at');       
            $customer_ledger->save();
        }

        if($request->input('discount')>0){
            $customer_ledger = new CustomerLedger;
            $customer_ledger->voucher_ref = $sales_return->voucher_ref;
            $customer_ledger->customer_id = $request->input('customer_id');
            $customer_ledger->tran_ref_id = 32;
            $customer_ledger->tran_ref_name = 'ReturnedDiscount';
            $customer_ledger->debit = $request->input('discount');
            $customer_ledger->credit = 0;
            $customer_ledger->transaction_date = $request->input('updated_at');       
            $customer_ledger->save();

        }
        
        return redirect('sales-return/list')->with('update','Sales Return Info Updated');

    }

    public function destroy(Request $request,$sales_return_master_id){
        
        $sales_return_master = SalesReturn::find($sales_return_master_id)->delete();
        $customer_ledger = CustomerLedger::where('voucher_ref','=', "$request->voucher_ref")->delete();
        $bank_transaction = BankTransaction::where('voucher_ref','=',"$request->voucher_ref")->delete();
        $voucher = Voucher::where('voucher_ref','=',"$request->voucher_ref")->delete();

        $all_sales_return_details = DB::table('tbl_sales_return_details')
        ->selectRaw('tbl_sales_return_details.sales_return_details_id')
        ->where('sales_return_master_id','=',$sales_return_master_id)
        ->get();
        //var_dump($all_sales_details_id);

        foreach ($all_sales_return_details as $key => $data) {
            
            $sales_return_details_id = $data->sales_return_details_id;
            $stock = Stock::where('sales_return_details_id','=',$sales_return_details_id);
            $stock->delete();
            
        }

        $all_sales_return_details = SalesReturnDetails::where('sales_return_master_id','=', $sales_return_master_id)->delete();

        return redirect('/sales-return/list')->with('success','Sales Return Info Deleted');
    }

}
