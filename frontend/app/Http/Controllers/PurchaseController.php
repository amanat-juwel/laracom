<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\Supplier;
use App\Item;
use App\ItemUnit;
use App\StockLocation;
use App\PurchaseDetails;
use App\SupplierLedger;
use App\StockMaster;
use App\Stock;
use App\BankTransaction;
use App\BankAccountLedger;
use App\Income;
use App\Voucher;
use DB;
use Exception;
use Illuminate\Support\Facades\Session;
use Auth;
use Validator;
use App\Batch;

class PurchaseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $suppliers = Supplier::all()->sortBy("sup_name");
        $items = Item::all()->sortBy("item_name");
        $stock_locations = StockLocation::all()->sortBy("stock_location_name");
        $flag = 0;
        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();

        try{
        $lastPurchaseMasterNo = Purchase::all()->last()->purchase_master_id;    
        $lastMemoNo = Purchase::all()->last()->memo_no;
        $lastMemoNo += 1;
        $flag=1;
        }
        catch(Exception $e) {
            //echo 'Message: ' .$e->getMessage();
        }
        finally{
            if ($flag == 0) {
                $lastPurchaseMasterNo = 0;
                $lastMemoNo = 1;
            }
        }

        return view('admin.purchase.index' , compact('lastMemoNo','lastPurchaseMasterNo','suppliers','items','accounts','stock_locations'));
    }


    public function create()
    {
        //
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
        
        if($request->due < 0  || ($request->advanced_amount > 0 && $request->bank_account_id == '')){
                return redirect('/purchase')->with('delete','Failed : Due can not be less than 0');
        }
        elseif($request->advanced_amount > 0 && $request->bank_account_id == ''){
                return redirect('/purchase')->with('delete','Failed : Please select payment method');
        }


        //Handling attachment
        if($request->file('attachment') != ""){
        $attachmentImage = $request->file('attachment');
        $name = time().$attachmentImage->getClientOriginalName();
        $uploadPath = 'public/images/purchase_attachments/';
        $attachmentImage->move($uploadPath,$name);
        $attachmentImageUrl = $uploadPath.$name;
        }
        else{
            $attachmentImageUrl = "";
        }
        
        $voucher = DB::table('vouchers')->orderBy('id', 'DESC')->first();
        if($voucher!=NULL){
            $voucher_ref = ++$voucher->id;
        }
        else{
            $voucher_ref = "1";   
        }
        
        $voucher = new Voucher;
        $voucher->voucher_ref = $voucher_ref;
        $voucher->type = "Purchase";
        $voucher->save();

        $purchase = new Purchase;
        
        if($request->supplier_id != ''){
                $purchase->supplier_id = $request->supplier_id;
            }
            elseif($request->mobile_no != ''){
                $supplier = new Supplier;
                $supplier->sup_name = $request->supplier_name;
                $supplier->sup_phone_no = "88".$request->mobile_no;
                $supplier->save();
                $purchase->supplier_id = $supplier->supplier_id;
            }
            else{
                return redirect('/purchase')->with('delete','Failed : Please select a Supplier');
            }

        $purchase->bill_no = $request->bill_no;
        $purchase->memo_total = $request->memo_total;
        ($request->advanced_amount!='')?$purchase->advanced_amount = $request->advanced_amount:$purchase->advanced_amount = 0;
        $purchase->discount = ($request->discount!='')?$request->discount:0;
        $purchase->purchase_date = $request->purchase_date;
        $purchase->attachment = $attachmentImageUrl;
        $purchase->voucher_ref = $voucher_ref;
        $purchase->remarks = $request->remarks;
        
        if(($request->due)>0){
            $purchase->status = 'Due';    
        }else{
            $purchase->status = 'Paid';
        }
        $purchase->purchased_by = Auth::user()->id;
        if($purchase->save())
        {   
            //START STOCK 
            $stock_master = New StockMaster;
            $stock_master->date = $request->purchase_date;
            $stock_master->description = "";
            $stock_master->type = "Purchase";
            $stock_master->ref_id = $purchase->purchase_master_id;
            $stock_master->user_id = Auth::user()->id;
            $stock_master->save();

            $string_item = '';
            foreach ($request->product_id as $key => $i) 
            {
                $item_info = Item::find($request->product_id[$key]);
                $string_item .= $item_info->item_name.',';
                //SAVE BATCH INFO
                $auto_generated_batch_code = $this->generateBatchCode($request->product_id[$key]);

                $batch = new Batch;
                $batch->code = $auto_generated_batch_code;
                $batch->item_id = $request->product_id[$key];
                $batch->purchase_rate = $request->price[$key];
                $batch->save();

                //STOCK 
                $table_stock = new Stock;
                $table_stock->stock_master_id = $stock_master->id;
                $table_stock->batch_id = $batch->id;
                $table_stock->item_id = $request->product_id[$key];
                $table_stock->stock_in = $request->qty[$key];
                $table_stock->stock_out = 0;
                $table_stock->particulars = "Bill #$purchase->bill_no ".$request->particulars[$key];
                $table_stock->save();
                //START STOCK 
            }

            if($request->memo_total>0){
                $supplier_ledger = new SupplierLedger;
                $supplier_ledger->purchase_master_id = $purchase->purchase_master_id;
                $supplier_ledger->voucher_ref = $voucher_ref;
                $supplier_ledger->supplier_id = $purchase->supplier_id;
                $supplier_ledger->tran_ref_id = 1;
                $supplier_ledger->tran_ref_name = 'BuyProduct';
                $supplier_ledger->debit = 0;
                $supplier_ledger->credit = $request->memo_total;
                $supplier_ledger->voucher_ref = $voucher_ref;
                $supplier_ledger->transaction_date = $purchase->purchase_date;  
                $supplier_ledger->save();

                $transaction = new BankTransaction;
                $transaction->voucher_ref = $voucher_ref;
                $transaction->bank_account_id = 1; //4=purcahse ac.
                $transaction->transaction_date = $request->purchase_date;
                $transaction->transaction_description = "Purchase: #".$purchase->purchase_master_id;
                $transaction->deposit = $request->memo_total;
                $transaction->expense = 0;
                $transaction->save();
            }

            if($request->advanced_amount>0){
                $supplier_ledger = new SupplierLedger;
                $supplier_ledger->purchase_master_id = $purchase->purchase_master_id;
                $supplier_ledger->voucher_ref = $voucher_ref;
                $supplier_ledger->supplier_id = $purchase->supplier_id;
                $supplier_ledger->tran_ref_id = 5;
                $supplier_ledger->tran_ref_name = 'AdvancedPaid';
                $supplier_ledger->debit = $request->advanced_amount;
                $supplier_ledger->credit = 0;
                $supplier_ledger->voucher_ref = $voucher_ref;
                $supplier_ledger->transaction_date = $purchase->purchase_date;            
                $supplier_ledger->save();

                # Start Bank Transaction
                $transaction = new BankTransaction;
                $transaction->voucher_ref = $voucher_ref;
                $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
                $transaction->transaction_date = $request->purchase_date;
                $transaction->transaction_description = "Purchase: ".substr($string_item, 0, -1);
                $transaction->deposit = 0;
                $transaction->expense = $request->advanced_amount;
                $transaction->save();
                # End Bank Transaction
            }

            if($request->discount>0){
                $supplier_ledger = new SupplierLedger;
                $supplier_ledger->purchase_master_id = $purchase->purchase_master_id;
                $supplier_ledger->voucher_ref = $voucher_ref;
                $supplier_ledger->supplier_id = $purchase->supplier_id;
                $supplier_ledger->tran_ref_id = 3;
                $supplier_ledger->tran_ref_name = 'Discount';
                $supplier_ledger->debit = $request->discount;
                $supplier_ledger->credit = 0;
                
                $supplier_ledger->transaction_date = $purchase->purchase_date;  
                               

                $supplier_ledger->save();

            }

            }
            
        
        //$sms = $this->sendSms($purchase->purchase_master_id); // returns true or false

        return redirect('admin/purchase')->with('success','Purchase : Successful');

    }

    public function show($purchase_master_id){
        $purchase_master =DB::table('tbl_purchase_master')
        ->join('users','users.id','=','tbl_purchase_master.purchased_by')
        ->join('tbl_supplier','tbl_supplier.supplier_id','=','tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_purchase_master.*,users.name as user_name,tbl_supplier.sup_name')
        ->where('purchase_master_id',$purchase_master_id)
        ->first();
        $stock_master =StockMaster::where('type','Purchase')->where('ref_id',$purchase_master_id)->first();
        $stock=DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id','=','tbl_stock.item_id')
        ->join('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
        ->selectRaw('tbl_stock.*,tbl_item.*,tbl_batch.*')
        ->where('stock_master_id',$stock_master->id)
        ->get();
        $items=Item::all();
        $suppliers = Supplier::all();
        return view('admin.purchase.show',compact('purchase_master','stock_master','stock','items','suppliers'));
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function purchaseDetails()
    {
        $purchase_detail = DB::table('tbl_purchase_master')
        ->leftJoin('tbl_supplier','tbl_supplier.supplier_id','=','tbl_purchase_master.supplier_id')
        ->leftJoin('users','users.id','=','tbl_purchase_master.purchased_by')
        ->selectRaw('tbl_purchase_master.*,tbl_supplier.*,users.*')
        ->orderBy('tbl_purchase_master.purchase_master_id', 'desc')
        ->get();
        
        return view('admin.purchase.purchase-details' , compact('purchase_detail'));
    }

    public function inventoryPurchaseDetails($id)
    {
        $purchase_master = DB::table('tbl_purchase_master')
        ->leftJoin('tbl_supplier','tbl_supplier.supplier_id','=','tbl_purchase_master.supplier_id')
        ->leftJoin('users','users.id','=','tbl_purchase_master.purchased_by')
        ->selectRaw('tbl_purchase_master.*,tbl_supplier.*,users.name as user_name')
        ->where('tbl_purchase_master.purchase_master_id', '=', $id)
        ->first();


        $stock = DB::table('tbl_stock')
        ->leftJoin('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
        ->leftJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->leftJoin('tbl_batch','tbl_batch.id', '=', 'tbl_stock.batch_id')
        ->where('type', '=', 'Purchase')
        ->where('ref_id', '=', $id)
        ->get();

        $items = Item::all()->sortBy("item_name");
        $stock_locations = StockLocation::all()->sortBy("stock_location_name");

        $suppliers = Supplier::all()->sortBy('sup_name');

        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();
        //var_dump($salesMaster);

        $references = DB::table('employees')
        ->orderBy('name', 'asc')
        ->get();
        return view('admin.purchase.show', compact('purchase_master','suppliers', 'stock','items','stock_locations','accounts','stock_locations'))->with('id',$id);

    }


    public function addItemToExistingBill(Request $request){
        
        $auto_generated_batch_code = $this->generateBatchCode($request->item_id);
        $batch = new Batch;
        $batch->code = $auto_generated_batch_code;
        $batch->item_id = $request->item_id;
        $batch->purchase_rate = $request->purchase_rate;
        $batch->save();

        $stock_master_id = StockMaster::where('type','Purchase')->where('ref_id',$request->purchase_master_id)->value('id');

        //STOCK 
        $table_stock = new Stock;
        $table_stock->stock_master_id = $stock_master_id;
        $table_stock->batch_id = $batch->id;
        $table_stock->item_id = $request->item_id;
        $table_stock->stock_in = $request->stock_in;
        $table_stock->stock_out = 0;
        $table_stock->particulars = $request->particulars;
        $table_stock->save();

        $purchase_master = Purchase::find($request->purchase_master_id);
        $purchase_master->memo_total += ($request->purchase_rate * $request->stock_in); 
        $purchase_master->status = 'Due';
        $purchase_master->update();

        $supplier_ledger = SupplierLedger::where('purchase_master_id',$request->purchase_master_id)->where('tran_ref_id',1)->first();
        $ledger = SupplierLedger::find($supplier_ledger->supplier_ledger_id);
        $ledger->credit += ($request->purchase_rate * $request->stock_in);
        $ledger->update();

        return redirect()->back()->with('success','New Item Added');   
        
    }

    public function purchaseInfoUpdate(Request $request)
    {
        //
        $purchase = Purchase::find($request->purchase_master_id);
        $purchase->purchase_date = $request->purchase_date;
        $purchase->bill_no = $request->bill_no;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->update();
        return redirect()->back()->with('success','Info Updated');

    }

    public function editStock($id){
        $stock =Stock::find($id);
        $batch =Batch::find($stock->batch_id);
        $items=Item::all();
        return view('admin.purchase.edit_stock',compact('stock','batch','items'));
    }

    public function stockUpdate(Request $request)
    {
        $stock = Stock::find($request->stock_id);
        $batch = Batch::find($stock->batch_id);

        $old_amount = $stock->stock_in * $batch->purchase_rate; 
        $new_amount = $request->stock_in * $request->purchase_rate;
        $diff_amount = $new_amount - $old_amount;

        $stock_master = StockMaster::find($stock->stock_master_id);
        $purchase_master = Purchase::find($stock_master->ref_id);
        $purchase_master->memo_total += $diff_amount;
        if($purchase_master->memo_total>$purchase_master->advanced_amount){
            $purchase_master->status = 'Due';
        }
        else{
            $purchase_master->status = 'Paid';
        }
        $purchase_master->update();

        $supplier_ledger = SupplierLedger::where('purchase_master_id',$purchase_master->purchase_master_id)->where('tran_ref_id',1)->first();
        $ledger = SupplierLedger::find($supplier_ledger->supplier_ledger_id);
        $ledger->credit += $diff_amount;
        $ledger->update();

        $stock = Stock::find($request->stock_id);
        $stock->item_id = $request->item_id;
        $stock->stock_in = $request->stock_in;
        $stock->particulars = $request->particulars;
        $stock->update();
        $batch = Batch::find($stock->batch_id);
        $batch->item_id = $request->item_id;
        $batch->purchase_rate = $request->purchase_rate;
        $batch->update();



        $stock_master = StockMaster::find($stock->stock_master_id);
        return redirect('admin/purchase/memo_details/'.$stock_master->ref_id)->with('success','Stock Updated');

    }
    public function singleStockDestroy($stock_id){


        $stock = Stock::find($stock_id);
        $batch = Batch::where('id',$stock->batch_id)->first();

        $stock_master = StockMaster::find($stock->stock_master_id);


        $purchase_master = Purchase::find($stock_master->ref_id);
        $purchase_master->memo_total -= ($stock->stock_in * $batch->purchase_rate); 
        if($purchase_master->memo_total>$purchase_master->advanced_amount){
            $purchase_master->status = 'Due';
        }
        else{
            $purchase_master->status = 'Paid';
        }
        $purchase_master->update();

        $supplier_ledger = SupplierLedger::where('purchase_master_id',$purchase_master->purchase_master_id)->where('tran_ref_id',1)->first();
        $ledger = SupplierLedger::find($supplier_ledger->supplier_ledger_id);
        $ledger->credit -= ($stock->stock_in * $batch->purchase_rate); 
        $ledger->update();

        $batch = Batch::where('id',$stock->batch_id)->delete();
        $stock = Stock::where('stock_id',$stock_id)->delete();

        return redirect()->back()->with('success','Stock Deleted');   
    }

    public function destroy($purchase_master_id)
    {

        
        $stock_master= StockMaster::where('type','=','Purchase')
        ->where('ref_id','=', $purchase_master_id)
        ->first(); 
        $stock=Stock::where('stock_master_id','=', $stock_master->id)->get();
    
        foreach ($stock as $key => $data) {
            $batch = Batch::where('id','=',$data->batch_id);
            $batch->delete();
        }
        $stock=Stock::where('stock_master_id','=', $stock_master->id)->delete();
        $stock_master= StockMaster::where('type','=','Purchase')->where('ref_id','=', $purchase_master_id)->delete(); 
        $purchase_master = Purchase::where('purchase_master_id','=', $purchase_master_id)->first();
        $transaction=BankTransaction::where('voucher_ref','=', $purchase_master->voucher_ref)->delete();
        $purchase_master = Purchase::where('purchase_master_id','=', $purchase_master_id)->delete();
        return redirect('admin/purchase/purchase-details')->with('success','Purchase Info Deleted');

    }


    public function updateDocument(Request $request, $id)
    {
        
        $document = Purchase::find($id);
        
        //Handling photo
        if($request->file('new_attachment') != ""){
            $docImage = $request->file('new_attachment');
            $name = time().$docImage->getClientOriginalName();
            $uploadPath = 'public/images/purchase_attachments/';
            $docImage->move($uploadPath,$name);
            $docImageUrl = $uploadPath.$name;
        }
        else{
            $docImageUrl = $request->old_attachment;
        }
        //Handling photo
        $document->attachment = $docImageUrl;
        $document->update();

        return redirect("admin/purchase/memo_details/$id")->with('update','Document Updated');
    }
    


}
