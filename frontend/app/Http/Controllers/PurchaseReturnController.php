<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\PurchaseReturn;
use App\PurchaseReturnDetails;
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

class PurchaseReturnController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $suppliers = Supplier::all();
        $items = Item::all()->sortBy("item_name");
        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();
        return view('admin.purchase_return.index' , compact('suppliers','items','accounts'));
    }

    public function store(Request $request){
        if($request->due < 0 || ($request->advanced_amount > 0 && $request->bank_account_id == '')){
            return redirect('/purchase-return')->with('delete','Purchase Return : Failed');
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
            $voucher->type = "PurchaseReturn";
            $voucher->save();

            $purchase_return = new PurchaseReturn;
            $purchase_return->memo_total = $request->memo_total;
            ($request->advanced_amount!='')?$purchase_return->advanced_amount = $request->advanced_amount:$purchase_return->advanced_amount = 0;
            $purchase_return->discount = ($request->discount!='')?$request->discount:0;
            $purchase_return->note = $request->note;
            $purchase_return->supplier_id = $request->supplier_id;
            $purchase_return->date = $request->date;
            $purchase_return->voucher_ref = $voucher_ref;
            $purchase_return->user_id = Auth::user()->id;

            if($purchase_return->save())
            {
 
                $purchase_return_master_id =  $purchase_return->purchase_return_master_id;
                $supplier_id = $purchase_return->supplier_id;
                $memo_total = $purchase_return->memo_total;
                $advanced_paid = $purchase_return->advanced_amount;
                $discount_amount = $purchase_return->discount;
                $trans_date = $purchase_return->date;

                //START STOCK MASTER
                  $stock_master=new StockMaster;
                  $stock_master->date = $request->date;
                  $stock_master->description = 'Purchase Return';
                  $stock_master->type = 'Purchase Return';
                  $stock_master->user_id = Auth::user()->id;
                  $stock_master->ref_id = $purchase_return_master_id;
                  $stock_master->save();
                  //END STOCK MASTER
                
                foreach ($request->product_id as $key => $i) 
                {

                    $PurchaseReturnDetails = new PurchaseReturnDetails;
                    $PurchaseReturnDetails->item_id = $request->product_id[$key];
                    $PurchaseReturnDetails->purchase_return_master_id = $purchase_return_master_id;
                    $PurchaseReturnDetails->quantity = $request->qty[$key];
                    $PurchaseReturnDetails->rate = $request->price[$key];
                    if($PurchaseReturnDetails->save())
                    {
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
                            $stock->particulars="Purchase Return #$purchase_return_master_id";
                            $stock->save();
                            break;
                          }
                          else{
                            $stock=new Stock;
                            $stock->stock_master_id=$stock_master->id;
                            $stock->batch_id=$batch->batch_id;
                            $stock->item_id=$request->product_id[$key];
                            $stock->stock_out=$batch->stock;
                            $stock->particulars="Purchase Return #$purchase_return_master_id";
                            $stock->save();
                            $required_qty -= $batch->stock;
                          }
                        }

                    }
                }

                if($memo_total>0){

                    $supplier_id = $supplier_id;
                    $tran_ref_id = 30;
                    $tran_ref_name = 'ReturnProduct';
                    $debit = $memo_total;
                    $credit = 0;
                    $transaction_date = $trans_date;                 
                    // Via QB
                    DB::table('tbl_supplier_ledger')->insert(
                        [
                        'voucher_ref'=>"$voucher_ref",
                        'supplier_id'=>"$supplier_id",
                        'tran_ref_id'=>"$tran_ref_id",
                        'tran_ref_name'=>"$tran_ref_name",
                        'debit'=>"$debit",
                        'credit'=>"$credit",
                        'transaction_date'=>"$transaction_date",
                        'particulars'=>"Return Item",
                        
                        ]
                        );
                }

            }
        }

       
        return redirect('admin/purchase-return')->with('success','Sales Return info Stored');
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


    public function showAll(){

        $all_purchase_return = DB::table('tbl_purchase_return_master')
        ->join('tbl_supplier','tbl_supplier.supplier_id','=','tbl_purchase_return_master.supplier_id')
        ->join('tbl_supplier_ledger','tbl_supplier_ledger.supplier_id','=','tbl_purchase_return_master.supplier_id')
        ->leftJoin('users','users.id','=','tbl_purchase_return_master.user_id')
        ->selectRaw('tbl_purchase_return_master.*,tbl_supplier.sup_name,users.name as received_by')
        ->where('tbl_supplier_ledger.tran_ref_name','ReturnProduct')
        ->orderBy('tbl_purchase_return_master.purchase_return_master_id', 'desc')
        ->get();

        return view('admin.purchase_return.list' , compact('all_purchase_return'));
    }

    public function show($purchase_return_master_id){
        
        $purchase_return = PurchaseReturn::find($purchase_return_master_id);

        $singlePurchaseReturn = DB::table('tbl_purchase_return_master')
        ->join('tbl_supplier','tbl_supplier.supplier_id','=','tbl_purchase_return_master.supplier_id')
        ->join('tbl_supplier_ledger','tbl_supplier_ledger.supplier_id','=','tbl_purchase_return_master.supplier_id')
        ->selectRaw('tbl_purchase_return_master.*,tbl_supplier_ledger.*,tbl_supplier.sup_name')
        ->where('tbl_purchase_return_master.purchase_return_master_id', '=', $purchase_return_master_id)
        ->first();

        $received_by = DB::table('users')->where('users.id', '=', $singlePurchaseReturn->user_id)->first();

        $supplierLedger = DB::table('tbl_supplier_ledger')
        ->where('voucher_ref', '=', $purchase_return->voucher_ref)
        ->get();

        $due = $singlePurchaseReturn->memo_total - $singlePurchaseReturn->advanced_amount - $singlePurchaseReturn->discount;

        $purchaseReturnDetails = DB::table('tbl_purchase_return_details')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_purchase_return_details.item_id')
        //->join('tbl_item_unit','tbl_item_unit.tbl_item_unit_id', '=', 'tbl_sales_details.tbl_item_unit_id')
        //->join('tbl_stock_location','tbl_stock_location.stock_location_id', '=', 'tbl_sales_details.stock_location_id')
        ->where('purchase_return_master_id', '=', $purchase_return_master_id)
        ->get();

        $items = Item::all()->sortBy("item_name");
        $stock_locations = StockLocation::all()->sortBy("stock_location_name");

        $suppliers = Supplier::all()->sortBy('customer_name');
        $accounts = DB::table('tbl_bank_account')
        ->where('is_payment_method',1)
        ->orderBy('bank_account_id', 'asc')
        ->get();
        //var_dump($salesMaster);
        return view('admin.purchase_return.inventory-purchase-return-details', compact('suppliers','singlePurchaseReturn','received_by','purchaseLedger', 'purchaseReturnDetails','due','items','stock_locations','accounts','stock_locations','references'))->with('purchase_return_master_id',$purchase_return_master_id);
    }

    public function updatePayment(Request $request,$purchase_return_master_id){
        
        $due = $request->due;
        $discount = ($request->discount!='')?$request->discount:0;;
        $paid = ($request->paid!='')?$request->paid:0;
        
        //Update Purchase Master
        $purchase_return = PurchaseReturn::find($purchase_return_master_id);
        $previous_advanced_amount = $purchase_return->advanced_amount; 
        $previous_discount = $purchase_return->discount;
        $purchase_return->advanced_amount = $request->input('paid') + $previous_advanced_amount;
        $purchase_return->discount = $request->input('discount')+ $previous_discount;
        $purchase_return->update();

        //Update Supplier Ledger
        if($request->input('paid')>0){

            # Start Bank Transaction
            $transaction = new BankTransaction;
            $transaction->voucher_ref = $purchase_return->voucher_ref;
            if($request->bank_account_id!=''){
                $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
            }
            else{
                $transaction->bank_account_id = 4;
            }
            $transaction->transaction_date = $request->input('updated_at');
            $transaction->transaction_description = "Purchase Return Due Paid for Voucher Ref $purchase_return->voucher_ref";
            $transaction->deposit = 0;
            $transaction->expense = $paid;
            $transaction->save();
            # End Bank Transaction

            $supplier_ledger = new SupplierLedger;
            $supplier_ledger->voucher_ref = $purchase_return->voucher_ref;
            $supplier_ledger->supplier_id = $request->input('supplier_id');
            $supplier_ledger->tran_ref_id = 100;
            $supplier_ledger->tran_ref_name = 'PreviousReturnDuePaid';
            $supplier_ledger->debit =  $request->input('paid');
            $supplier_ledger->credit = 0;
            $supplier_ledger->transaction_date = $request->input('updated_at');       
            $supplier_ledger->save();
        }

        if($request->input('discount')>0){
            $supplier_ledger = new SupplierLedger;
            $supplier_ledger->voucher_ref = $purchase_return->voucher_ref;
            $supplier_ledger->supplier_id = $request->input('supplier_id');
            $supplier_ledger->tran_ref_id = 101;
            $supplier_ledger->tran_ref_name = 'ReturnedDiscount';
            $supplier_ledger->debit = $request->input('discount');
            $supplier_ledger->credit = 0;
            $supplier_ledger->transaction_date = $request->input('updated_at');       
            $supplier_ledger->save();

        }
        
        return redirect('admin/purchase-return/list')->with('update','Purchase Return Info Updated');

    }

    public function destroy(Request $request,$purchase_return_master_id){
        
        $purchase_return_master = PurchaseReturn::find($purchase_return_master_id)->delete();
        $supplier_ledger = SupplierLedger::where('voucher_ref','=', "$request->voucher_ref")->where('tran_ref_name','ReturnProduct')->delete();
       // $bank_transaction = BankTransaction::where('voucher_ref','=',"$request->voucher_ref")->delete();
        $voucher = Voucher::where('voucher_ref','=',"$request->voucher_ref")->delete();

        $all_purchase_return_details = DB::table('tbl_purchase_return_details')
        ->selectRaw('tbl_purchase_return_details.purchase_return_details_id')
        ->where('purchase_return_master_id','=',$purchase_return_master_id)
        ->get();
        
        $stock_master = DB::table('tbl_stock_master')
            ->selectRaw('tbl_stock_master.id')
            ->where('type','=','Purchase Return')
            ->where('ref_id','=',$purchase_return_master_id)
            ->first();

        $stock = Stock::where('stock_master_id','=',$stock_master->id);
        $stock->delete();
                
        $stock_master = StockMaster::where('type','=','Purchase Return')->where('ref_id','=',$purchase_return_master_id)->delete();


        $all_purchase_return_details = PurchaseReturnDetails::where('purchase_return_master_id','=', $purchase_return_master_id)->delete();

        return redirect('admin/purchase-return/list')->with('success','Purchase Return Info Deleted');
    }

}
