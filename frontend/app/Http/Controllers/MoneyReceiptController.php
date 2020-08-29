<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\Sales;
use App\SalesMasterAddLess;
use App\Brand;
use App\Supplier;
use App\Item;
use App\ItemUnit;
use App\StockLocation;
use App\PurchaseDetails;
use App\SalesDetails;
use App\SupplierLedger;
use App\CustomerLedger;
use App\Customer;
use App\Stock;
use App\BankTransaction;
use App\BankAccountLedger;
use App\Voucher;
use App\Income;
use App\MoneyReceipt;
use DB;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderShipped;
use App\Mail\SalesInvoice;
// use Illuminate\Support\Facades\Mail;
use Mail;
use Auth;

class MoneyReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {

        $money_receipts = DB::table('money_receipts')
        ->leftJoin('tbl_sales_master','tbl_sales_master.sales_master_id','=','money_receipts.sales_master_id')
        ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->leftJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','money_receipts.payment_method')
        ->leftJoin('users','users.id','=','money_receipts.user_id')
        ->orderBy('date', 'dsc')
        ->get();

        return view('admin.money_receipt.index', compact('money_receipts'));
    }


    public function create()
    {
        
        $flag = 0;
        try{
            $lastMoneyReceiptId = MoneyReceipt::all()->last()->mr_id;
            $flag=1;
        }
        catch(Exception $e) {
            //echo 'Message: ' .$e->getMessage();
         }
        finally{
            if ($flag == 0) {
                $lastMoneyReceiptId = 0;
            }
        }

        $customers = Customer::all();

        $sales = DB::table('tbl_sales_master')
        ->where(DB::raw('tbl_sales_master.memo_total - tbl_sales_master.discount - tbl_sales_master.advanced_amount'), '>', 0)
        ->orderBy('sales_master_id', 'asc')
        ->get();

        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();

        $customers = Customer::all();

        return view('admin.money_receipt.create', compact('sales','accounts','customers','lastMoneyReceiptId'));
    }


    public function showInvoiceInfo($sales_master_id)
    {   

        $salesMasters = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->selectRaw('tbl_sales_master.*,tbl_customer.*')
        ->where('tbl_sales_master.sales_master_id', '=', $sales_master_id)
        ->where(DB::raw('tbl_sales_master.memo_total - tbl_sales_master.discount - tbl_sales_master.advanced_amount'), '>', 0)
        ->first();

        $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();
        $formatted_customer_code = $globalSettings->invoice_prefix."-".str_pad($salesMasters->customer_code, 8, '0', STR_PAD_LEFT);

        return response()->json([
                'sales_master_id' => $sales_master_id,
                'customer_name' =>$formatted_customer_code.' | '.$salesMasters->customer_name,
                'address' => $salesMasters->address,
                'amount_receivable' => $salesMasters->memo_total - $salesMasters->advanced_amount - $salesMasters->discount,
            ]);
                
    }

    public function customerBalanceInfo($customer_id)
    {   
        $customer = DB::table('tbl_customer')
        ->where('customer_id',$customer_id)
        ->first();

        $customer_ledger = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(debit) as debit,SUM(credit) as credit')
        ->groupBy('customer_id')
        ->where('customer_id', '=', $customer_id)
        ->first();
        if(!empty($customer_ledger)){
            $balance = $customer_ledger->debit + $customer->op_bal_debit - $customer_ledger->credit + $customer->op_bal_credit;
        }else{
            $balance = $customer->op_bal_debit - $customer->op_bal_credit;
        }
        return response()->json([
                'amount_receivable' => $balance,
            ]);
                
    }

    public function store(Request $request)
    {   
        $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();

        ### START INVOICED M.R ###
        if($request->type == 'invoiced'){
            $salesMasters = DB::table('tbl_sales_master')
            ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
            ->selectRaw('tbl_sales_master.*,tbl_customer.*')
            ->where('tbl_sales_master.sales_master_id', '=', $request->sales_master_id)
            ->where(DB::raw('tbl_sales_master.memo_total - tbl_sales_master.discount - tbl_sales_master.advanced_amount'), '>', 0)
            ->first();

            if($request->discount){
                $voucher = DB::table('vouchers')->orderBy('id', 'DESC')->first();
                if($voucher!=NULL){
                    $voucher_ref = ++$voucher->id;
                }
                else{
                    $voucher_ref = "1";   
                }

                $voucher = new Voucher;
                $voucher->voucher_ref = $voucher_ref;
                $voucher->type = "Sales Discount";
                $voucher->voucher_description = "PAYMENT";
                $voucher->save();
                
                $formatted_sales_invoice = $globalSettings->invoice_prefix."-BI-".str_pad($request->sales_master_id, 8, '0', STR_PAD_LEFT);

                ##MONEY RECEIPT
                $money_receipt = New MoneyReceipt;
                $money_receipt->sales_master_id = $request->sales_master_id;
                $money_receipt->voucher_ref = $voucher_ref;
                $money_receipt->payment_method = 0;
                $money_receipt->payment_by = 'N/A';
                $money_receipt->amount = $request->discount;
                $money_receipt->narration = 'DISCOUNT';
                $money_receipt->on_account_of_supply = $formatted_sales_invoice." (".number_format($request->amount_receivable+$request->discount,2).")";
                $money_receipt->date = $request->date;
                $money_receipt->user_id = Auth::user()->id;
                $money_receipt->type = "invoiced";
                $money_receipt->save();


                ##CUSTOMER LEDGER
                $customer_ledger = new CustomerLedger;
                $customer_ledger->sales_master_id = $request->sales_master_id;
                $customer_ledger->customer_id = $salesMasters->customer_id;
                $customer_ledger->tran_ref_id = 3;
                $customer_ledger->tran_ref_name = 'Discount';
                $customer_ledger->particulars = "Discount | ".$formatted_sales_invoice."(".number_format($request->amount_receivable,2).")";
                $customer_ledger->debit = 0;
                $customer_ledger->credit = $request->discount;
                $customer_ledger->voucher_ref = $voucher_ref;
                $customer_ledger->transaction_date = $request->date;       
                $customer_ledger->save();

                ##UPDATE SALES MASTER
                $sales = Sales::find($request->sales_master_id);
                $previous_discount = $sales->discount;
                $sales->discount = $request->discount + $previous_discount;
                $sales->update();

                $income = new Income;
                $income->voucher_ref = $sales->voucher_ref;
                $income->income_head_id = 1; // 1 = sales ac.
                $income->date =  $request->date;
                $income->amount = 0;
                $income->reverse_amount = $request->discount;
                $income->description = "Discount; invoice $sales->sales_master_id";
                $income->admin_id = Auth::user()->id;
                $income->save();
            }

            if($request->amount){
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
                $voucher->voucher_description = "PAYMENT";
                $voucher->save();

                $formatted_sales_invoice = $globalSettings->invoice_prefix."-BI-".str_pad($request->sales_master_id, 8, '0', STR_PAD_LEFT);

                ##MONEY RECEIPT
                $money_receipt = New MoneyReceipt;
                $money_receipt->sales_master_id = $request->sales_master_id;
                $money_receipt->voucher_ref = $voucher_ref;
                $money_receipt->payment_method = $request->bank_account_id;
                $money_receipt->payment_by = $request->payment_by;
                $money_receipt->amount = $request->amount;
                $money_receipt->narration = "PAYMENT";
                $money_receipt->on_account_of_supply = $formatted_sales_invoice." (".number_format($request->amount_receivable,2).")";
                $money_receipt->date = $request->date;
                $money_receipt->user_id = Auth::user()->id;
                $money_receipt->type = "invoiced";
                $money_receipt->save();


                ##CUSTOMER LEDGER
                $customer_ledger = new CustomerLedger;
                $customer_ledger->sales_master_id = $request->sales_master_id;
                $customer_ledger->customer_id = $salesMasters->customer_id;
                $customer_ledger->tran_ref_id = 7;
                $customer_ledger->tran_ref_name = 'PreviousDuePaid';
                $customer_ledger->particulars = "PAYMENT"." | ".$formatted_sales_invoice."(".number_format($request->amount_receivable,2).")";
                $customer_ledger->debit = 0;
                $customer_ledger->credit = $request->amount;
                $customer_ledger->voucher_ref = $voucher_ref;
                $customer_ledger->transaction_date = $request->date;       
                $customer_ledger->save();

                ##TRANSACTION LEDGER
                $transaction = new BankTransaction;
                $transaction->voucher_ref = $voucher_ref;
                $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
                $transaction->transaction_date = $request->date;
                $transaction->transaction_description = "PAYMENT"." | ".$formatted_sales_invoice."(".number_format($request->amount_receivable,2).")";
                $transaction->deposit = $request->amount;
                $transaction->expense = 0;
                $transaction->save();

                ##UPDATE SALES MASTER
                $sales = Sales::find($request->sales_master_id);
                $previous_advanced_amount = $sales->advanced_amount; 
                $previous_discount = $sales->discount;
                $sales->advanced_amount = $request->amount + $previous_advanced_amount;
                $sales->discount = 0 + $previous_discount;

                
                $sales->update();
            }
        }
        ### END INVOICED M.R ###

        ### START UN-INVOICED M.R ###
        else{
            if($request->discount){
                $voucher = DB::table('vouchers')->orderBy('id', 'DESC')->first();
                if($voucher!=NULL){
                    $voucher_ref = ++$voucher->id;
                }
                else{
                    $voucher_ref = "1";   
                }

                $voucher = new Voucher;
                $voucher->voucher_ref = $voucher_ref;
                $voucher->type = "Sales Discount";
                $voucher->voucher_description = "PAYMENT";
                $voucher->save();

                $formatted_sales_invoice = $globalSettings->invoice_prefix."-BI-".str_pad($request->sales_master_id, 8, '0', STR_PAD_LEFT);

                ##MONEY RECEIPT
                $money_receipt = New MoneyReceipt;
                //$money_receipt->sales_master_id = $request->sales_master_id;
                $money_receipt->voucher_ref = $voucher_ref;
                $money_receipt->payment_method = 0;
                $money_receipt->payment_by = 'N/A';
                $money_receipt->amount = $request->discount;
                $money_receipt->narration = 'DISCOUNT';
                $money_receipt->on_account_of_supply = "Discount for Previous Due";
                $money_receipt->date = $request->date;
                $money_receipt->user_id = Auth::user()->id;
                $money_receipt->type = "un-invoiced";
                $money_receipt->save();


                ##CUSTOMER LEDGER
                $customer_ledger = new CustomerLedger;
                $customer_ledger->sales_master_id = 0;
                $customer_ledger->customer_id = $request->customer_id;
                $customer_ledger->tran_ref_id = 3;
                $customer_ledger->tran_ref_name = 'Discount';
                $customer_ledger->particulars = "Discount for Previous Due";
                $customer_ledger->debit = 0;
                $customer_ledger->credit = $request->discount;
                $customer_ledger->voucher_ref = $voucher_ref;
                $customer_ledger->transaction_date = $request->date;       
                $customer_ledger->save();

            }

            if($request->amount){
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
                $voucher->voucher_description = "PAYMENT";
                $voucher->save();

                $formatted_sales_invoice = $globalSettings->invoice_prefix."-BI-".str_pad($request->sales_master_id, 8, '0', STR_PAD_LEFT);

                ##MONEY RECEIPT
                $money_receipt = New MoneyReceipt;
                //$money_receipt->sales_master_id = $request->sales_master_id;
                $money_receipt->voucher_ref = $voucher_ref;
                $money_receipt->payment_method = $request->bank_account_id;
                $money_receipt->payment_by = $request->payment_by;
                $money_receipt->amount = $request->amount;
                $money_receipt->narration = "PAYMENT";
                $money_receipt->on_account_of_supply = "Previous Sales";
                $money_receipt->date = $request->date;
                $money_receipt->user_id = Auth::user()->id;
                $money_receipt->type = "un-invoiced";
                $money_receipt->save();


                ##CUSTOMER LEDGER
                $customer_ledger = new CustomerLedger;
                $customer_ledger->sales_master_id = 0;
                $customer_ledger->customer_id = $request->customer_id;
                $customer_ledger->tran_ref_id = 7;
                $customer_ledger->tran_ref_name = 'PreviousDuePaid';
                $customer_ledger->particulars = "PAYMENT"." | Previous Sales";
                $customer_ledger->debit = 0;
                $customer_ledger->credit = $request->amount;
                $customer_ledger->voucher_ref = $voucher_ref;
                $customer_ledger->transaction_date = $request->date;       
                $customer_ledger->save();

                $customer = Customer::find($request->customer_id);
                ##TRANSACTION LEDGER
                $transaction = new BankTransaction;
                $transaction->voucher_ref = $voucher_ref;
                $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
                $transaction->transaction_date = $request->date;
                $transaction->transaction_description =  $customer->customer_name;
                $transaction->deposit = $request->amount;
                $transaction->expense = 0;
                $transaction->save();

            }
        }
        ### END UN-INVOICED M.R ###

        return redirect()->back()->with('success','Transaction Saved');
    }

    public function print($mr_id)
    {   
        $mr =  DB::table('money_receipts')
        ->where('mr_id', $mr_id)
        ->first();

        if($mr->type == "invoiced"){
            $money_receipt = DB::table('money_receipts')
            ->leftJoin('tbl_sales_master','tbl_sales_master.sales_master_id','=','money_receipts.sales_master_id')
            ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
            ->leftJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','money_receipts.payment_method')
            ->leftJoin('users','users.id','=','money_receipts.user_id')
            ->where('mr_id', $mr_id)
            ->first();

            $customer =  DB::table('tbl_customer_ledger')
            ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_customer_ledger.customer_id')
            ->where('tbl_customer_ledger.voucher_ref',$money_receipt->voucher_ref)
            ->first();

            return view('admin.money_receipt.print', compact('money_receipt','customer'));
        }
        else{
            $money_receipt = DB::table('money_receipts')
            ->leftJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','money_receipts.payment_method')
            ->leftJoin('users','users.id','=','money_receipts.user_id')
            ->where('mr_id', $mr_id)
            ->first();

            $customer =  DB::table('tbl_customer_ledger')
            ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_customer_ledger.customer_id')
            ->where('tbl_customer_ledger.voucher_ref',$money_receipt->voucher_ref)
            ->first();

            return view('admin.money_receipt.print', compact('money_receipt','customer'));
        }
    }

    public function edit($mr_id)
    {

        $mr =  DB::table('money_receipts')
        ->where('mr_id', $mr_id)
        ->first();

        if($mr->type == "invoiced"){
            $money_receipt = DB::table('money_receipts')
            ->leftJoin('tbl_sales_master','tbl_sales_master.sales_master_id','=','money_receipts.sales_master_id')
            ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
            ->leftJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','money_receipts.payment_method')
            ->leftJoin('users','users.id','=','money_receipts.user_id')
            ->where('mr_id', $mr_id)
            ->first();

            $customer =  DB::table('tbl_customer_ledger')
            ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_customer_ledger.customer_id')
            ->where('tbl_customer_ledger.voucher_ref',$money_receipt->voucher_ref)
            ->first();

            $customers = Customer::all();

            $sales = DB::table('tbl_sales_master')
            ->where(DB::raw('tbl_sales_master.memo_total - tbl_sales_master.discount - tbl_sales_master.advanced_amount'), '>', 0)
            ->orderBy('sales_master_id', 'asc')
            ->get();


            $accounts = DB::table('tbl_bank_account')
            ->where('is_payment_method',1)
            ->orderBy('bank_account_id', 'asc')
            ->get();

            return view('admin.money_receipt.edit-invoiced', compact('money_receipt','customer','sales','customers','accounts'));
        }
        else{
            $money_receipt = DB::table('money_receipts')
            ->leftJoin('tbl_bank_account','tbl_bank_account.bank_account_id','=','money_receipts.payment_method')
            ->leftJoin('users','users.id','=','money_receipts.user_id')
            ->where('mr_id', $mr_id)
            ->first();

            $customer =  DB::table('tbl_customer_ledger')
            ->leftJoin('tbl_customer','tbl_customer.customer_id','=','tbl_customer_ledger.customer_id')
            ->where('tbl_customer_ledger.voucher_ref',$money_receipt->voucher_ref)
            ->first();

            $customers = Customer::all();

            $sales = DB::table('tbl_sales_master')
            ->where(DB::raw('tbl_sales_master.memo_total - tbl_sales_master.discount - tbl_sales_master.advanced_amount'), '>', 0)
            ->orderBy('sales_master_id', 'asc')
            ->get();

            $accounts = DB::table('tbl_bank_account')
            ->where('is_payment_method',1)
            ->orderBy('bank_account_id', 'asc')
            ->get();

            return view('admin.money_receipt.edit-un-invoiced', compact('money_receipt','customer','sales','customers','accounts'));
        }
        
    }

    public function update(Request $request, $mr_id)
    {   
        
            $money_receipt = MoneyReceipt::find($mr_id);
            $money_receipt->payment_method = $request->bank_account_id;
            $previous_amount = $money_receipt->amount;
            $money_receipt->amount = $request->amount;
            $money_receipt->date = $request->date;
            $money_receipt->update();

            $customer_ledger = CustomerLedger::where('voucher_ref','=', "$money_receipt->voucher_ref")->first();
            $customer_ledger->debit = 0;
            $customer_ledger->credit = $request->amount;
            $customer_ledger->transaction_date = $request->date;       
            $customer_ledger->update();

            $transaction = BankTransaction::where('voucher_ref','=',"$money_receipt->voucher_ref")->first();
            $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
            $transaction->transaction_date = $request->date;
            $transaction->deposit = $request->amount;
            $transaction->update();

        if($request->type == 'invoiced'){
            $current_amount_change = $previous_amount - $request->amount;
            $sales_master = Sales::find($money_receipt->sales_master_id);
            $sales_master->advanced_amount -= $current_amount_change;
            if(($sales_master->advanced_amount + $sales_master->discount) != $sales_master->memo_total){
                $sales_master->status = 'Due'; 
            }
            else{ 
                $sales_master->status = 'Paid'; 
            }
            $sales_master->update();
            
        }

        return redirect('admin/money-receipt')->with('update','Info Updated');
    }

    public function destroy($id)
    {
        $money_receipt = MoneyReceipt::find($id);
        
        $customer_ledger = CustomerLedger::where('voucher_ref','=', "$money_receipt->voucher_ref")->delete();
        $bank_transaction = BankTransaction::where('voucher_ref','=',"$money_receipt->voucher_ref")->delete();
        $voucher = Voucher::where('voucher_ref','=',"$money_receipt->voucher_ref")->delete();

        ##UPDATE SALES MASTER
        $sales = Sales::find($money_receipt->sales_master_id);
        if($sales!=NULL){
            $previous_advanced_amount = $sales->advanced_amount; 
            $previous_discount = $sales->discount;
            if($money_receipt->narration != 'DISCOUNT'){
                $sales->advanced_amount = $previous_advanced_amount - $money_receipt->amount;
            }
            if($money_receipt->narration == 'DISCOUNT'){
                $sales->discount = $previous_discount - $money_receipt->amount;

                $sales_acc = DB::table('tbl_income')
                ->where('voucher_ref',$sales->voucher_ref)
                ->where('reverse_amount','>',0)
                ->where('date',"$money_receipt->date")
                ->get();

                foreach($sales_acc as $data){
                    if($data->reverse_amount==$money_receipt->amount){
                        $row = Income::where('income_id', $data->income_id)->delete();
                    }
                }
            }
            $sales->update();
        }

        $money_receipt->delete();


        return redirect()->back()->with('success','Data Deleted');
    }
}
