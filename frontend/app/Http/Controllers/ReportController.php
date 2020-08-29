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
use App\Stock;
use App\StockTransfer;
use DB;
use PDF;

class ReportController extends Controller
{
    public function todaysSales()
    {   
        $current_date = date("Y-m-d");
        $todays_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_item.item_name,tbl_sales_details.sales_details_id,tbl_sales_details.memo_no,tbl_sales_details.quantity,tbl_sales_details.sales_price,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_sales_master.status,tbl_customer.customer_name,tbl_sales_details.sales_master_id')
        ->where('sales_date', '=', $current_date)
         ->orderBy('tbl_sales_details.sales_master_id', 'desc')
        ->get();
        return view('admin.report.sales.todays-sales' , compact('todays_sales'));
    }

    public function todaysSalesPDF()
    {   
        $current_date = date("Y-m-d");
        $todays_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_item.item_name,tbl_sales_details.sales_details_id,tbl_sales_details.memo_no,tbl_sales_details.quantity,tbl_sales_details.sales_price,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_sales_master.status,tbl_customer.customer_name,tbl_sales_details.sales_master_id')
        ->where('sales_date', '=', $current_date)
        ->orderBy('tbl_sales_details.sales_master_id', 'desc')
        ->get();
        return view('admin.report.sales.todays-sales-pdf' , compact('todays_sales'));
    }  
    
    public function dateToDateSales()
    {   
        return view('admin.report.sales.date-to-date-sales');
    }

    public function dateToDateSalesReport(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_item.item_name,tbl_sales_details.sales_details_id,tbl_sales_details.memo_no,tbl_sales_details.quantity,tbl_sales_details.sales_price,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_sales_master.voucher_ref,tbl_customer.customer_name,tbl_sales_details.sales_master_id')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->orderBy('tbl_sales_details.sales_master_id', 'desc')
        ->get();
        
        return view('admin.report.sales.date-to-date-sales',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_sales'=>$date_to_date_sales]);
    }

    public function dateToDateSalesMemoWise()
    {   
        return view('admin.report.sales.date-to-date-sales-memo-wise');
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

    public function dateToDateSalesExecutiveWise()
    {   
        $references = DB::table('employees')
        ->orderBy('name', 'asc')
        ->get();
        return view('admin.report.sales.date-to-date-sales-executive-wise',compact('references'));
    }

    public function dateToDateSalesExecutiveWiseReport(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_sales = DB::table('tbl_sales_master')
        ->join('employees','employees.id','=','tbl_sales_master.reference_by')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('SUM(tbl_sales_master.memo_total) as memo_total,SUM(tbl_sales_master.advanced_amount) as advanced_amount,SUM(tbl_sales_master.discount) as discount,tbl_sales_master.memo_no,tbl_customer.*,employees.name as employee')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->groupBy('tbl_sales_master.reference_by')
        ->get();
        
        $references = DB::table('employees')
        ->orderBy('name', 'asc')
        ->get();

        return view('admin.report.sales.date-to-date-sales-executive-wise',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_sales'=>$date_to_date_sales,'references'=>$references]);
    }

    public function dateToDateSalesMemoWiseReportPDF($start_date,$end_date)
    {   
        

        $date_to_date_sales = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_sales_master.*,tbl_customer.*')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->orderBy('tbl_sales_master.memo_no', 'asc')
        ->get();
        
        return view('admin.report.sales.date-to-date-sales-memo-wise-pdf',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_sales'=>$date_to_date_sales]);
    }

    public function allPurchase()
    {   
        
        $all_purchases = DB::table('tbl_item_unit')
        ->join('tbl_purchase_master','tbl_purchase_master.purchase_master_id', '=', 'tbl_item_unit.purchase_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_item_unit.item_id')
        //->join('tbl_item_unit','tbl_item_unit.item_id', '=', 'tbl_item.item_id')
        ->join('tbl_supplier','tbl_supplier.supplier_id', '=', 'tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_item_unit.*,tbl_purchase_master.*,tbl_item.*,tbl_supplier.*')
        //->where('is_sold', '=' , '0') # To see all purchase History
        ->orderBy('tbl_item_unit.tbl_item_unit_id', 'desc')
        ->get();        
        //echo count($all_purchases);
        return view('admin.report.purchase.all-purchase' , compact('all_purchases'));
    }

    public function todaysPurchase()
    {   
        $current_date = date("Y-m-d");	

        $todays_purchases = DB::table('tbl_purchase_details')
        ->join('tbl_purchase_master','tbl_purchase_master.purchase_master_id', '=', 'tbl_purchase_details.purchase_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_purchase_details.item_id')
        ->join('tbl_supplier','tbl_supplier.supplier_id', '=', 'tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_item.item_name,tbl_purchase_details.purchase_details_id,tbl_purchase_details.item_vat,tbl_purchase_details.memo_no,tbl_purchase_details.quantity,tbl_purchase_details.purchase_price,tbl_purchase_master.purchase_date,tbl_purchase_master.advanced_amount,tbl_purchase_master.discount,tbl_purchase_master.status,tbl_supplier.sup_name,tbl_purchase_details.purchase_master_id')
        ->where('purchase_date', '=', $current_date)
         ->orderBy('tbl_purchase_details.purchase_master_id', 'desc')
        ->get();

        return view('admin.report.purchase.todays-purchase' , compact('todays_purchases'));
    }

    public function todaysPurchasePDF()
    {   
        $current_date = date("Y-m-d");  

        $todays_purchases = DB::table('tbl_purchase_details')
        ->join('tbl_purchase_master','tbl_purchase_master.purchase_master_id', '=', 'tbl_purchase_details.purchase_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_purchase_details.item_id')
        ->join('tbl_supplier','tbl_supplier.supplier_id', '=', 'tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_item.item_name,tbl_purchase_details.purchase_details_id,tbl_purchase_details.item_vat,tbl_purchase_details.memo_no,tbl_purchase_details.quantity,tbl_purchase_details.purchase_price,tbl_purchase_master.purchase_date,tbl_purchase_master.advanced_amount,tbl_purchase_master.discount,tbl_purchase_master.status,tbl_supplier.sup_name,tbl_purchase_details.purchase_master_id')
        ->where('purchase_date', '=', $current_date)
         ->orderBy('tbl_purchase_details.purchase_master_id', 'desc')
        ->get();

        return view('admin.report.purchase.todays-purchase-pdf' , compact('todays_purchases'));
    }

    public function dateToDatePurchase()
    {   
        return view('admin.report.purchase.date-to-date-purchase');
    }

    
    public function dateToDatePurchaseReport(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $purchase_details = DB::table('tbl_purchase_master')
        ->join('tbl_stock_master','tbl_purchase_master.purchase_master_id','=','tbl_stock_master.ref_id')
        ->join('tbl_stock','tbl_stock.stock_master_id','=','tbl_stock_master.id')
        ->join('tbl_item','tbl_item.item_id','=','tbl_stock.item_id')
        ->join('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
        ->selectRaw('tbl_stock.*,tbl_item.*,tbl_batch.*,tbl_purchase_master.purchase_date')
        ->where('tbl_stock_master.type','Purchase')
        ->whereBetween('tbl_purchase_master.purchase_date', [$start_date, $end_date])
        ->get();

      
        return view('admin.report.purchase.date-to-date-purchase',['start_date'=>$start_date,'end_date'=>$end_date, 'purchase_details'=>$purchase_details]);
    }
    public function dateToDatePurchaseReportPDF(Request $request,$start_date ,$end_date )
    {   

        $purchase_details = DB::table('tbl_purchase_master')
        ->join('tbl_stock_master','tbl_purchase_master.purchase_master_id','=','tbl_stock_master.ref_id')
        ->join('tbl_stock','tbl_stock.stock_master_id','=','tbl_stock_master.id')
        ->join('tbl_item','tbl_item.item_id','=','tbl_stock.item_id')
        ->join('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
        ->selectRaw('tbl_stock.*,tbl_item.*,tbl_batch.*,tbl_purchase_master.purchase_date')
        ->where('tbl_stock_master.type','Purchase')
        ->whereBetween('tbl_purchase_master.purchase_date', [$start_date, $end_date])
        ->get();

        return view('admin.report.purchase.date-to-date-purchase-pdf',['start_date'=>$start_date,'end_date'=>$end_date, 'purchase_details'=>$purchase_details]);

    }

    public function dateToDatePurchaseFromSupplier()
    {      
        $suppliers = DB::table('tbl_supplier')
        ->orderBy('sup_name', 'asc')
        ->get();

        return view('admin.report.purchase.from-supplier',compact('suppliers'));
    }

    
    public function dateToDatePurchaseFromSupplierReport(Request $request)
    {   
        $supplier_id = $request->supplier_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $all_purchases = DB::table('tbl_purchase_master')
        ->join('tbl_stock_master','tbl_purchase_master.purchase_master_id','=','tbl_stock_master.ref_id')
        ->join('tbl_stock','tbl_stock.stock_master_id','=','tbl_stock_master.id')
        ->join('tbl_item','tbl_item.item_id','=','tbl_stock.item_id')
        ->join('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
        ->join('tbl_supplier','tbl_supplier.supplier_id', '=', 'tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_stock.*,tbl_item.*,tbl_batch.*,tbl_purchase_master.purchase_date,tbl_purchase_master.purchase_master_id,tbl_supplier.*')
        ->where('tbl_stock_master.type','Purchase')
        ->whereBetween('purchase_date', [$start_date, $end_date])
        ->where('tbl_purchase_master.supplier_id',$supplier_id)
        ->orderBy('tbl_purchase_master.purchase_date', 'asc')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        $suppliers = DB::table('tbl_supplier')
        ->orderBy('sup_name', 'asc')
        ->get();
      
        return view('admin.report.purchase.from-supplier',compact('suppliers','supplier_id','start_date','end_date','all_purchases'));
    }

    public function dateToDateSalesToCustomer()
    {      
        $customers = DB::table('tbl_customer')
        ->orderBy('customer_name', 'asc')
        ->get();

        return view('admin.report.sales.to-customer',compact('customers'));
    }

    
    public function dateToDateSalesToCustomerReport(Request $request)
    {   
        $customer_id = $request->customer_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $all_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_item.item_name,tbl_sales_details.sales_details_id,tbl_sales_details.item_vat,tbl_sales_details.memo_no,tbl_sales_details.quantity,tbl_sales_details.sales_price,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_customer.customer_name,tbl_sales_details.sales_master_id')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->where('tbl_sales_master.customer_id',$customer_id)
        ->orderBy('tbl_sales_master.sales_date', 'asc')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        $customers = DB::table('tbl_customer')
        ->orderBy('customer_name', 'asc')
        ->get();
      
        return view('admin.report.sales.to-customer',compact('customers','customer_id','start_date','end_date','all_sales'));
    }

    public function dateToDateSalesReportPDF(Request $request,$start_date ,$end_date )
    {   
        $date_to_date_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->join('tbl_customer','tbl_customer.customer_id', '=', 'tbl_sales_master.customer_id')
        ->selectRaw('tbl_item.item_name,tbl_sales_details.sales_details_id,tbl_sales_details.memo_no,tbl_sales_details.quantity,tbl_sales_details.sales_price,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_sales_master.status,tbl_customer.customer_name,tbl_sales_master.voucher_ref,tbl_sales_details.sales_master_id')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->orderBy('tbl_sales_details.sales_master_id', 'desc')
        ->get();
        return view('admin.report.sales.date-to-date-sales-pdf',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_sales'=>$date_to_date_sales]);
    }

   public function currentStock()
    {   
    
        $item = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_name,tbl_item.opening_stock_qty,tbl_category.cata_name,tbl_brand.brand_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_name')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        return view('admin.report.stock.current-stock', compact('item'));
    } 
    
    public function currentStockPDF()
    {   
        $item = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_name,tbl_item.opening_stock_qty,tbl_category.cata_name,tbl_brand.brand_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_name')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();
        
        //$pdf = PDF::loadView('report.stock.current-stock-pdf', $item);
        //return $pdf->download('stock-report.pdf');
        return view('admin.report.stock.current-stock-pdf', compact('item'));
    } 

    public function currentStockByCategory()
    {   
    
        $items = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_category.cata_id')
        ->orderBy('tbl_category.cata_name', 'asc')
        ->get();

        return view('admin.report.stock.current-stock-category', compact('items'));
        
    } 

    public function currentStockByCategoryPDF()
    {   
    
        $items = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_category.cata_id')
        ->orderBy('tbl_category.cata_name', 'asc')
        ->get();

        return view('admin.report.stock.current-stock-category-pdf', compact('items'));
        
    } 

    public function currentStockBySubCategory()
    {   
    
        $items = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_sub_category','tbl_sub_category.id','=','tbl_item.sub_cata_id')
        ->selectRaw('tbl_sub_category.name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_sub_category.id')
        ->orderBy('tbl_sub_category.name', 'asc')
        ->get();

        return view('admin.report.stock.current-stock-sub-category', compact('items'));
        
    } 

    public function currentStockByBrand(){
        $brands = DB::table('tbl_brand')
        ->orderBy('tbl_brand.brand_name', 'asc')
        ->get();
        //var_dump($all_item);

        $current_stocks = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->select('tbl_item.item_name','tbl_brand.brand_name',DB::raw('(SUM(stock_in) - SUM(stock_out)) as count'))
        ->groupBy('tbl_item.item_id')
        ->get();

        return view('admin.report.stock.current-stock-by-brand', compact('brands','current_stocks'));
    }

    public function todaysExpenses()
    {   
        $current_date = date("Y-m-d");
        $todays_expenses = DB::table('tbl_expense')
        ->join('tbl_expense_head','tbl_expense_head.expense_head_id', '=', 'tbl_expense.expense_head_id')
        ->selectRaw('tbl_expense.*,tbl_expense_head.expense_head_id,tbl_expense_head.expense_head')
        ->where('date', '=', $current_date)
        ->orderBy('tbl_expense.expense_id', 'desc')
        ->get();
        return view('admin.report.expense.todays-expenses' , compact('todays_expenses'));
    }

    public function todaysExpensesPDF()
    {   
        $current_date = date("Y-m-d");
        $todays_expenses = DB::table('tbl_expense')
        ->join('tbl_expense_head','tbl_expense_head.expense_head_id', '=', 'tbl_expense.expense_head_id')
        ->selectRaw('tbl_expense.*,tbl_expense_head.expense_head_id,tbl_expense_head.expense_head')
        ->where('date', '=', $current_date)
        ->orderBy('tbl_expense.expense_id', 'desc')
        ->get();
        return view('admin.report.expense.todays-expenses-pdf' , compact('todays_expenses'));
    }  

    public function dateToDateExpenses()
    {   
        return view('admin.report.expense.date-to-date-expenses');
    }

    public function dateToDateExpensesReport(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_expenses = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_bank_account.bank_name, sum(deposit) - sum(expense) as balance')
        ->where('account_group_id',  3) // expense grp.
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->groupBy('tbl_bank_transaction.bank_account_id')
        ->orderBy('transaction_date', 'asc')
        ->get();
        
        return view('admin.report.expense.date-to-date-expenses',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_expenses'=>$date_to_date_expenses]);
    }

    public function dateToDateExpensesReportPDF(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_expenses = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_bank_account.bank_name, sum(deposit) - sum(expense) as balance')
        ->where('account_group_id',  3) // expense grp.
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->groupBy('tbl_bank_transaction.bank_account_id')
        ->orderBy('transaction_date', 'asc')
        ->get();
        
        return view('admin.report.expense.date-to-date-expenses-pdf',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_expenses'=>$date_to_date_expenses]);
    }
    
        public function cashInHand()
    {   
        return view('admin.report.statement.cash-in-hand');
    }

    public function cashInHandReport(Request $request)
    {   

        $current_date = $request->reporting_date;

        $sale_today = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'AdvancedPaid')
        ->get();    
        

        $customer_due_today = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit,SUM(debit) as debit')
        ->where('tran_ref_name', '!=', 'PreviousDuePaid')
        ->where('transaction_date', '=', $current_date)
        ->get();  

        $due_received = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'PreviousDuePaid')
        ->get();
        foreach ($due_received as $key => $due) {
            $due_received_today = $due->credit;
        }

        $purchase_today = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(debit) as debit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'AdvancedPaid')
        ->get();    
        //var_dump($purchase_today);

        $supplier_due_today = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(credit) as credit,SUM(debit) as debit')
        ->where('tran_ref_name', '!=', 'PreviousDuePaid')
        ->where('transaction_date', '=', $current_date)
        ->get(); 

        $due_paid = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(debit) as debit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'PreviousDuePaid')
        ->get();
        foreach ($due_paid as $key => $due) {
            $due_paid_today = $due->debit;
        }

        $expense_today = DB::table('tbl_expense')
        ->selectRaw('SUM(amount) as amount')
        ->where('date', '=', $current_date)
        ->get();

        $transactions = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name,tbl_bank_account.bank_account')
        ->where('tbl_bank_transaction.transaction_date','=', $current_date)
        ->orderBy('bank_transaction_id', 'dsc')
        ->get();

        $accounts_summary = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_bank_account.bank_name,SUM(deposit) as deposit,SUM(expense) as expense')
        ->where('tbl_bank_transaction.transaction_date','=', $current_date)
        ->groupBy('tbl_bank_account.bank_name')
        ->orderBy('tbl_bank_account.bank_name', 'asc')
        ->get();  

        ############START CASH/BANK/OTHERS ACCOUNTS##############
        $start_date = $current_date;
        $end_date = $current_date;

        $view_of_accounts = array();
        $all_account = DB::table('tbl_bank_account')
        //->leftJoin('tbl_account_type','tbl_account_type.account_type_id','=','tbl_bank_account.account_type_id')
        ->where('is_payment_method','1')
        ->orderBy('account_type_id', 'asc')
        ->orderBy('bank_account_id', 'asc')
        ->get();

        foreach ($all_account as $key => $single_account) {

        $bank_account_id = $single_account->bank_account_id;
            
        $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
        
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->deposit; 
            $sum_opening_balance_credit += $single_transaction->expense;
        }
        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->deposit; 
            $sum_credit += $single_transaction->expense;
            $sum_transaction_balance_debit += $single_transaction->deposit; 
            $sum_transaction_balance_credit += $single_transaction->expense; 

        }
        $transaction_balance = $sum_transaction_balance_debit-$sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;

            if($transaction_balance >0 || $transaction_balance < 0){
                array_push($view_of_accounts, array('bank_name'=>"$bank_name",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance));
            }
            
        } // end of foreach loop

        ############END CASH/BANK/OTHERS ACCOUNTS##############
        
        return view('admin.report.statement.cash-in-hand',['reporting_date'=>$current_date,'sale_today'=>$sale_today
            ,'customer_due_today'=>$customer_due_today
            ,'due_received_today'=>$due_received_today
            ,'purchase_today'=>$purchase_today
            ,'supplier_due_today'=>$supplier_due_today
            ,'due_paid_today'=>$due_paid_today
            ,'expense_today'=>$expense_today
            ,'transactions'=>$transactions
            ,'accounts_summary'=>$accounts_summary
            ,'view_of_accounts'=>$view_of_accounts]);
    }
    
        public function cashInHandReportPDF(Request $request)
    {   

        $current_date = $request->reporting_date;

        $sale_today = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'AdvancedPaid')
        ->get();    

        $customer_due_today = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit,SUM(debit) as debit')
        ->where('tran_ref_name', '!=', 'PreviousDuePaid')
        ->where('transaction_date', '=', $current_date)
        ->get();  

        $due_received = DB::table('tbl_customer_ledger')
        ->selectRaw('SUM(credit) as credit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'PreviousDuePaid')
        ->get();
        foreach ($due_received as $key => $due) {
            $due_received_today = $due->credit;
        }

        $purchase_today = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(debit) as debit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'AdvancedPaid')
        ->get();    
        //var_dump($purchase_today);

        $supplier_due_today = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(credit) as credit,SUM(debit) as debit')
        ->where('tran_ref_name', '!=', 'PreviousDuePaid')
        ->where('transaction_date', '=', $current_date)
        ->get(); 

        $due_paid = DB::table('tbl_supplier_ledger')
        ->selectRaw('SUM(debit) as debit')
        ->where('transaction_date', '=', $current_date)
        ->where('tran_ref_name', '=', 'PreviousDuePaid')
        ->get();
        foreach ($due_paid as $key => $due) {
            $due_paid_today = $due->debit;
        }

        $expense_today = DB::table('tbl_expense')
        ->selectRaw('SUM(amount) as amount')
        ->where('date', '=', $current_date)
        ->get();

        $transactions = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name,tbl_bank_account.bank_account')
        ->where('tbl_bank_transaction.transaction_date','=', date('Y-m-d'))
        ->orderBy('bank_transaction_id', 'dsc')
        ->get();
        
        $accounts_summary = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id','=','tbl_bank_transaction.bank_account_id')
        ->selectRaw('tbl_bank_account.bank_name,SUM(deposit) as deposit,SUM(expense) as expense')
        ->where('tbl_bank_transaction.transaction_date','=', $current_date)
        ->groupBy('tbl_bank_account.bank_name')
        ->orderBy('tbl_bank_account.bank_name', 'asc')
        ->get(); 
        
        ############START CASH/BANK/OTHERS ACCOUNTS##############
        $start_date = $current_date;
        $end_date = $current_date;

        $view_of_accounts = array();
        $all_account = DB::table('tbl_bank_account')
        //->leftJoin('tbl_account_type','tbl_account_type.account_type_id','=','tbl_bank_account.account_type_id')
        ->where('is_payment_method','1')
        ->orderBy('account_type_id', 'asc')
        ->orderBy('bank_account_id', 'asc')
        ->get();

        foreach ($all_account as $key => $single_account) {

        $bank_account_id = $single_account->bank_account_id;
            
        $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->deposit; 
            $sum_opening_balance_credit += $single_transaction->expense;
        }
        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->deposit; 
            $sum_credit += $single_transaction->expense;
            $sum_transaction_balance_debit += $single_transaction->deposit; 
            $sum_transaction_balance_credit += $single_transaction->expense; 

        }
        $transaction_balance = $sum_transaction_balance_debit-$sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;

            if($transaction_balance >0 || $transaction_balance < 0){
                array_push($view_of_accounts, array('bank_name'=>"$bank_name",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance));
            }
            
        } // end of foreach loop

        ############END CASH/BANK/OTHERS ACCOUNTS##############
        
        return view('admin.report.statement.cash-in-hand-pdf',['reporting_date'=>$current_date,'sale_today'=>$sale_today
            ,'customer_due_today'=>$customer_due_today
            ,'due_received_today'=>$due_received_today
            ,'purchase_today'=>$purchase_today
            ,'supplier_due_today'=>$supplier_due_today
            ,'due_paid_today'=>$due_paid_today
            ,'expense_today'=>$expense_today
            ,'transactions'=>$transactions
            ,'accounts_summary'=>$accounts_summary
            ,'view_of_accounts'=>$view_of_accounts]);
    }


    public function dateToDateprofitByItem()
    {      
        
        return view('admin.report.profit.date-to-date-profit-by-item');
    }

    public function dateToDateprofitByItemReport(Request $request)
    {      
        
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->orderBy('tbl_sales_details.sales_master_id', 'asc')
        ->get(); 

        return view('admin.report.profit.date-to-date-profit-by-item' , compact('date_to_date_sales','start_date','end_date'));
    }

    public function dateToDateprofitByItemReportPDF($start_date,$end_date)
    {      
        
        $date_to_date_sales = DB::table('tbl_sales_details')
        ->join('tbl_sales_master','tbl_sales_master.sales_master_id', '=', 'tbl_sales_details.sales_master_id')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_sales_details.item_id')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->orderBy('tbl_sales_details.sales_master_id', 'asc')
        ->get(); 

        return view('admin.report.profit.date-to-date-profit-by-item-pdf' , compact('date_to_date_sales','start_date','end_date'));
    }  

    public function dateToDateIncome()
    {   
        return view('admin.report.income.date-to-date-income');
    }

    public function dateToDateIncomeReport(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_incomes = DB::table('tbl_income')
        ->leftJoin('tbl_income_head','tbl_income_head.income_head_id', '=', 'tbl_income.income_head_id')
        ->selectRaw('tbl_income.*,tbl_income_head.income_head')
        ->whereBetween('date', [$start_date, $end_date])
        ->orderBy('tbl_income.date', 'asc')
        ->orderBy('tbl_income.income_id', 'asc')
        ->get();
        
        return view('admin.report.income.date-to-date-income',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_incomes'=>$date_to_date_incomes]);
    }

    public function dateToDateIncomeReportPDF(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $date_to_date_incomes = DB::table('tbl_income')
        ->leftJoin('tbl_income_head','tbl_income_head.income_head_id', '=', 'tbl_income.income_head_id')
        ->selectRaw('tbl_income.*,tbl_income_head.income_head')
        ->whereBetween('date', [$start_date, $end_date])
        ->orderBy('tbl_income.date', 'asc')
        ->orderBy('tbl_income.income_id', 'asc')
        ->get();
        
        return view('admin.report.income.date-to-date-income-pdf',['start_date'=>$start_date,'end_date'=>$end_date, 'date_to_date_incomes'=>$date_to_date_incomes]);
    }

    public function salesDuePDF()
    {
        
        $sales_detail = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->selectRaw('tbl_sales_master.sales_master_id,tbl_sales_master.voucher_ref,tbl_sales_master.memo_no,tbl_sales_master.memo_total,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_sales_master.status,tbl_customer.customer_name')
        ->where('tbl_sales_master.status', '=', 'Due')
        ->orderBy('tbl_sales_master.sales_master_id', 'desc')
        ->get();
        return view('admin.report.sales.sales-due-pdf', compact('sales_detail'));
    }  

    public function purchaseDuePDF()
    {

        $purchase_details = DB::table('tbl_purchase_master')
        ->join('tbl_supplier','tbl_supplier.supplier_id','=','tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_purchase_master.purchase_master_id,tbl_purchase_master.voucher_ref,tbl_purchase_master.advanced_amount,tbl_purchase_master.discount,tbl_purchase_master.attachment,tbl_purchase_master.memo_no,tbl_purchase_master.memo_total,tbl_purchase_master.purchase_date,tbl_purchase_master.status,tbl_supplier.sup_name,tbl_supplier.supplier_id')
        ->where('tbl_purchase_master.status', '=', 'Due')
        ->orderBy('tbl_purchase_master.purchase_master_id', 'desc')
        ->get();

        return view('admin.report.purchase.purchase-due-pdf', compact('purchase_details'));
    }

    public function indexIncomeStatement()
    {
        return view('admin.report.statement.income-statement');
    }

    /*
    public function incomeStatement(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $total_income = DB::table('tbl_sales_master')
        ->selectRaw('SUM(advanced_amount) as advanced_amount')
        ->whereBetween('tbl_sales_master.sales_date', [$start_date, $end_date])
        ->first();
        
        $income = $total_income->advanced_amount;
       

        // DEBUGING PURPOSE CODE  
        // ###START COST OF GOODS SOLD###
        // #Step 1
        // $x_total = 0; $x_in = 0; $x_out = 0;
        // $stocks = DB::table('tbl_stock')
        // ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        // ->where('tbl_stock.stock_change_date','<',"$start_date")
        // ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        // ->get();
        // foreach ($stocks as $key => $stock) {
        //    $x_total += $stock->stock_in * $stock->purchase_price;
        // }
        // echo $x_total; echo "x_total ";
        
        // $current_stocks = DB::table('tbl_stock')
        // ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        // ->where('stock_change_date','<',"$start_date")
        // ->get(); 
        // $in = 0; $out = 0;
        // foreach ($current_stocks as $key => $cs) {
        //     $x_in += $cs->stock_in; 
        //     $x_out += $cs->stock_out; 
        // }
        // echo $x_remain = $x_in - $x_out;echo "x_remain ";
        // echo $x_average_rate = $x_total/$x_in;echo "x_average_rate ";
        // echo $begining_inventory = $x_average_rate * $x_remain;echo "begining_inventory <br>";

        // #Step 2
        // $added_inventory = 0; $y_in = 0; $y_out = 0;
        // $stocks = DB::table('tbl_stock')
        // ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        // ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        // ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        // ->get();
        // foreach ($stocks as $key => $stock) {
        //    $added_inventory += $stock->stock_in * $stock->purchase_price; 
        // }

        // $current_stocks = DB::table('tbl_stock')
        // ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        // ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        // ->get(); 
        // $in = 0; $out = 0;
        // foreach ($current_stocks as $key => $cs) {
        //     $y_in += $cs->stock_in; 
        //     $y_out += $cs->stock_out; 
        // }
        
        // echo $y_in;echo "y_in ";
        // echo $y_out;echo "y_out ";
        // echo $added_inventory;echo "added_inventory ";
        // echo $y_remain = $y_in - $y_out;echo "y_remain <br>";
        
        // echo $number_of_item_between_period = $x_remain + $y_in;echo "number_of_item_between_period ";
        // echo $average_rate_between_period = ($added_inventory + $begining_inventory) / $number_of_item_between_period;echo "average_rate_between_period ";
        // echo $stock = $number_of_item_between_period - $y_out;echo "stock ";
        // echo $ending_inventory = $stock * $average_rate_between_period;echo "ending_inventory <br>";

        // echo $cost_of_goods_sold = $begining_inventory + $added_inventory - $ending_inventory;
        // ###END COST OF GOODS SOLD###

        ###START COST OF GOODS SOLD###
        #Step 1
        $x_total = 0; $x_in = 0; $x_out = 0;
        $stocks = DB::table('tbl_stock')
        ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        ->where('tbl_stock.stock_change_date','<',"$start_date")
        ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        ->get();
        foreach ($stocks as $key => $stock) {
           $x_total += $stock->stock_in * $stock->purchase_price;
        }
        $x_total;
        
        $current_stocks = DB::table('tbl_stock')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<',"$start_date")
        ->get(); 
        $in = 0; $out = 0;
        foreach ($current_stocks as $key => $cs) {
            $x_in += $cs->stock_in; 
            $x_out += $cs->stock_out; 
        }
        $x_remain = $x_in - $x_out;
        ($x_remain > 0) ? $x_average_rate = $x_total/$x_in : $x_average_rate = 0;
        $begining_inventory = $x_average_rate * $x_remain;

        #Step 2
        $added_inventory = 0; $y_in = 0; $y_out = 0;
        $stocks = DB::table('tbl_stock')
        ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        ->get();
        foreach ($stocks as $key => $stock) {
           $added_inventory += $stock->stock_in * $stock->purchase_price; 
        }

        $current_stocks = DB::table('tbl_stock')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        ->get(); 
        $in = 0; $out = 0;
        foreach ($current_stocks as $key => $cs) {
            $y_in += $cs->stock_in; 
            $y_out += $cs->stock_out; 
        }
        
        $y_in;
        $y_out;
        $added_inventory;
        $y_remain = $y_in - $y_out;
        
        $number_of_item_between_period = $x_remain + $y_in;
        $average_rate_between_period = ($added_inventory + $begining_inventory) / $number_of_item_between_period;
        $ending_stock = $number_of_item_between_period - $y_out;
        $ending_inventory = $ending_stock * $average_rate_between_period;

        $cost_of_goods = $begining_inventory + $added_inventory - $ending_inventory;
        ###END COST OF GOODS SOLD###

        $total_expense = DB::table('tbl_expense')
        ->selectRaw('SUM(amount) as amount')
        ->whereBetween('tbl_expense.date', [$start_date, $end_date])
        ->get();
        foreach ($total_expense as $key => $exp) {
            $expense = $exp->amount;
        }
        return view('admin.report.statement.income-statement', compact('income','expense','cost_of_goods','start_date','end_date'));
    }

    public function incomeStatementPDF($start_date ,$end_date)
    {

        $total_income = DB::table('tbl_sales_master')
        ->selectRaw('SUM(advanced_amount) as advanced_amount')
        ->whereBetween('tbl_sales_master.sales_date', [$start_date, $end_date])
        ->get();
        foreach ($total_income as $key => $inc) {
            $income = $inc->advanced_amount;
        }

        ###START COST OF GOODS SOLD###
        #Step 1
        $x_total = 0; $x_in = 0; $x_out = 0;
        $stocks = DB::table('tbl_stock')
        ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        ->where('tbl_stock.stock_change_date','<',"$start_date")
        ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        ->get();
        foreach ($stocks as $key => $stock) {
           $x_total += $stock->stock_in * $stock->purchase_price;
        }
        $x_total;
        
        $current_stocks = DB::table('tbl_stock')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<',"$start_date")
        ->get(); 
        $in = 0; $out = 0;
        foreach ($current_stocks as $key => $cs) {
            $x_in += $cs->stock_in; 
            $x_out += $cs->stock_out; 
        }
        $x_remain = $x_in - $x_out;
        ($x_remain > 0) ? $x_average_rate = $x_total/$x_in : $x_average_rate = 0;
        $begining_inventory = $x_average_rate * $x_remain;

        #Step 2
        $added_inventory = 0; $y_in = 0; $y_out = 0;
        $stocks = DB::table('tbl_stock')
        ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        ->get();
        foreach ($stocks as $key => $stock) {
           $added_inventory += $stock->stock_in * $stock->purchase_price; 
        }

        $current_stocks = DB::table('tbl_stock')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        ->get(); 
        $in = 0; $out = 0;
        foreach ($current_stocks as $key => $cs) {
            $y_in += $cs->stock_in; 
            $y_out += $cs->stock_out; 
        }
        
        $y_in;
        $y_out;
        $added_inventory;
        $y_remain = $y_in - $y_out;
        
        $number_of_item_between_period = $x_remain + $y_in;
        $average_rate_between_period = ($added_inventory + $begining_inventory) / $number_of_item_between_period;
        $stock = $number_of_item_between_period - $y_out;
        $ending_inventory = $stock * $average_rate_between_period;

        $cost_of_goods = $begining_inventory + $added_inventory - $ending_inventory;
        ###END COST OF GOODS SOLD###

        $total_expense = DB::table('tbl_expense')
        ->selectRaw('SUM(amount) as amount')
        ->whereBetween('tbl_expense.date', [$start_date, $end_date])
        ->get();
        foreach ($total_expense as $key => $exp) {
            $expense = $exp->amount;
        }
        return view('admin.report.statement.income-statement-pdf', compact('income','expense','cost_of_goods','start_date','end_date'));
    }
    */

    public function incomeStatement(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $total_sales = DB::table('tbl_sales_master')
        ->selectRaw('SUM(memo_total) as memo_total')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->first();
        
        $total_discount = DB::table('tbl_sales_master')
        ->selectRaw('SUM(discount) as discount')
        ->whereBetween('sales_date', [$start_date, $end_date])
        ->first();

        $sales_income = $total_sales->memo_total;
        
        $sales_discount = $total_discount->discount;

        $total_other_income = DB::table('tbl_income')
        ->join('tbl_income_head','tbl_income_head.income_head_id','=','tbl_income.income_head_id')
        ->selectRaw('SUM(amount) as amount,tbl_income_head.income_head')
        ->whereBetween('date', [$start_date, $end_date])
        ->groupBy('tbl_income.income_head_id')
        ->orderBy('income_head','asc')
        ->get();
        //$other_income = $total_other_income->amount;

        # ORIGINAL COSTING RATE
        $item = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,tbl_category.cata_name,tbl_brand.brand_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_code', 'asc')
        ->get();

        $purchase_rate_array = array();

        foreach($item as $data){

            $inventory_history = DB::table('tbl_stock')
            ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->leftJoin('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
            ->leftJoin('tbl_sales_details','tbl_sales_details.sales_details_id','=','tbl_stock.sales_details_id')
            ->leftJoin('tbl_sales_master','tbl_sales_master.sales_master_id','=','tbl_sales_details.sales_master_id')
            ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_purchase_details.purchase_price,tbl_sales_details.sales_price,tbl_sales_master.sales_master_id')
            ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
            ->where('tbl_stock.item_id','=',$data->item_id)
            ->orderBy('tbl_stock.stock_id', 'asc')
            ->get(); 

            $quantity = 0;
            $sold_qty = 0;
            $rate = 0;
            
            foreach($inventory_history as $key => $single_data){
                if($single_data->stock_in > 0){
                    $var_1 = $quantity * $rate;
                    $var_2 = $single_data->stock_in * $single_data->purchase_price;
                    $sum_var = $var_1 + $var_2;

                    $quantity += $single_data->stock_in;
                    $rate = $sum_var / $quantity;
                }
                else{
                    $quantity -= $single_data->stock_out;
                    $sold_qty += $single_data->stock_out;
                }
            }

            array_push($purchase_rate_array, array('item_id'=>"$data->item_id",'sold_qty'=>$sold_qty,'rate'=>$rate));
        }

        $cost_of_goods_sold = 0;
        for($i=0; $i<count($purchase_rate_array); $i++){
            $cost_of_goods_sold += ($purchase_rate_array[$i]['sold_qty'] * $purchase_rate_array[$i]['rate']);
        }

        # ./ORIGINAL COSTING RATE

        $total_expense = DB::table('tbl_expense')
        ->join('tbl_expense_head','tbl_expense_head.expense_head_id','=','tbl_expense.expense_head_id')
        ->selectRaw('SUM(amount) as amount,tbl_expense_head.expense_head')
        ->whereBetween('date', [$start_date, $end_date])
        ->groupBy('tbl_expense.expense_head_id')
        ->orderBy('expense_head','asc')
        ->get();

        //$expense = $total_expense->amount;


        return view('admin.report.statement.income-statement', compact('sales_income','sales_discount','total_other_income','cost_of_goods_sold','total_expense','start_date','end_date'));
    }

    public function incomeStatementPDF($start_date ,$end_date)
    {

        $total_income = DB::table('tbl_sales_master')
        ->selectRaw('SUM(advanced_amount) as advanced_amount')
        ->whereBetween('tbl_sales_master.sales_date', [$start_date, $end_date])
        ->get();
        foreach ($total_income as $key => $inc) {
            $income = $inc->advanced_amount;
        }

        ###START COST OF GOODS SOLD###
        #Step 1
        $x_total = 0; $x_in = 0; $x_out = 0;
        $stocks = DB::table('tbl_stock')
        ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        ->where('tbl_stock.stock_change_date','<',"$start_date")
        ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        ->get();
        foreach ($stocks as $key => $stock) {
           $x_total += $stock->stock_in * $stock->purchase_price;
        }
        $x_total;
        
        $current_stocks = DB::table('tbl_stock')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<',"$start_date")
        ->get(); 
        $in = 0; $out = 0;
        foreach ($current_stocks as $key => $cs) {
            $x_in += $cs->stock_in; 
            $x_out += $cs->stock_out; 
        }
        $x_remain = $x_in - $x_out;
        ($x_remain > 0) ? $x_average_rate = $x_total/$x_in : $x_average_rate = 0;
        $begining_inventory = $x_average_rate * $x_remain;

        #Step 2
        $added_inventory = 0; $y_in = 0; $y_out = 0;
        $stocks = DB::table('tbl_stock')
        ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        ->selectRaw('tbl_stock.*,tbl_purchase_details.purchase_price')
        ->get();
        foreach ($stocks as $key => $stock) {
           $added_inventory += $stock->stock_in * $stock->purchase_price; 
        }

        $current_stocks = DB::table('tbl_stock')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('tbl_stock.stock_change_date', [$start_date, $end_date])
        ->get(); 
        $in = 0; $out = 0;
        foreach ($current_stocks as $key => $cs) {
            $y_in += $cs->stock_in; 
            $y_out += $cs->stock_out; 
        }
        
        $y_in;
        $y_out;
        $added_inventory;
        $y_remain = $y_in - $y_out;
        
        $number_of_item_between_period = $x_remain + $y_in;
        $average_rate_between_period = ($added_inventory + $begining_inventory) / $number_of_item_between_period;
        $stock = $number_of_item_between_period - $y_out;
        $ending_inventory = $stock * $average_rate_between_period;

        $cost_of_goods = $begining_inventory + $added_inventory - $ending_inventory;
        ###END COST OF GOODS SOLD###

        $total_expense = DB::table('tbl_expense')
        ->selectRaw('SUM(amount) as amount')
        ->whereBetween('tbl_expense.date', [$start_date, $end_date])
        ->get();
        foreach ($total_expense as $key => $exp) {
            $expense = $exp->amount;
        }
        return view('admin.report.statement.income-statement-pdf', compact('income','expense','cost_of_goods','start_date','end_date'));
    }


    public function indexAccountStatement()
    {
        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();
        $customers = Customer::all()->sortBy("customer_name");
        $suppliers = Supplier::all()->sortBy("sup_name");


        return view('admin.report.statement.account-statement', compact('accounts','customers','suppliers'));
    }

    public function accountStatement(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if($request->account_id[0]=="G"){
            $bank_account_id = substr($request->account_id,1);
        }
        elseif($request->account_id[0]=="C"){
            $customer_id = substr($request->account_id,1);
        }
        elseif($request->account_id[0]=="S"){
            $supplier_id = substr($request->account_id,1);
        }


        if(!empty($bank_account_id)){
            ############ CASH/BANK #################
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $start_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'asc')
            ->get();

            $account_id = "G".$bank_account_id;
            $account_name = $initial_opening_balance->bank_name;
            ############CASH/BANK#################
        }

        elseif(!empty($customer_id)){
            ############CUSTOMER#################
            $current_customer = Customer::where('customer_id',$customer_id)->first();
          
            $all_transactions_before_start_date = DB::table('tbl_customer_ledger')
            ->where('customer_id', '=', $customer_id)
            ->where('tbl_customer_ledger.transaction_date', '<', $start_date)
            ->get();

            $sum_opening_balance_debit = $current_customer->op_bal_debit; 
            $sum_opening_balance_credit = $current_customer->op_bal_credit;

            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->debit; 
                $sum_opening_balance_credit += $single_transaction->credit;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_customer_ledger')
            ->where('customer_id', '=', $customer_id)
            ->whereBetween('tbl_customer_ledger.transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'asc')
            ->get();

            $account_id = "C".$customer_id;
            $account_name = $current_customer->customer_name;
            ############CUSTOMER#################
        }

        elseif(!empty($supplier_id)){
            ############SUPPLIER#################
            $current_supplier = Supplier::where('supplier_id',$supplier_id)->first();

            $all_transactions_before_start_date = DB::table('tbl_supplier_ledger')
            ->where('supplier_id', '=', $supplier_id)
            ->where('tbl_supplier_ledger.transaction_date', '<', $start_date)
            ->get();

            $sum_opening_balance_debit = $current_supplier->op_bal_debit; 
            $sum_opening_balance_credit = $current_supplier->op_bal_credit;

            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->debit; 
                $sum_opening_balance_credit += $single_transaction->credit;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_supplier_ledger')
            ->where('supplier_id', '=', $supplier_id)
            ->whereBetween('tbl_supplier_ledger.transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'asc')
            ->get();

            $account_id = "S".$supplier_id;
            $account_name = $current_supplier->sup_name;
            ############SUPPLIER#################
        }


        $accounts = DB::table('tbl_bank_account')
        ->orderBy('bank_account_id', 'asc')
        ->get();
        $customers = Customer::all()->sortBy("customer_name");
        $suppliers = Supplier::all()->sortBy("sup_name");


        return view('admin.report.statement.account-statement', compact('accounts','customers','suppliers','account_id','account_name','opening_balance','all_transactions','start_date','end_date'));
    }

    public function accountStatementPDF($start_date ,$end_date, $account_id)
    {

        if($account_id[0]=="G"){
            $bank_account_id = substr($account_id,1);
        }
        elseif($account_id[0]=="C"){
            $customer_id = substr($account_id,1);
        }
        elseif($account_id[0]=="S"){
            $supplier_id = substr($account_id,1);
        }


        if(!empty($bank_account_id)){
            ############ CASH/BANK #################
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $start_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'asc')
            ->get();

            $account_id = "G".$bank_account_id;
            $account_name = $initial_opening_balance->bank_name;
            ############CASH/BANK#################
        }

        elseif(!empty($customer_id)){
            ############CUSTOMER#################
            $current_customer = Customer::where('customer_id',$customer_id)->first();
          
            $all_transactions_before_start_date = DB::table('tbl_customer_ledger')
            ->where('customer_id', '=', $customer_id)
            ->where('tbl_customer_ledger.transaction_date', '<', $start_date)
            ->get();

            $sum_opening_balance_debit = $current_customer->op_bal_debit; 
            $sum_opening_balance_credit = $current_customer->op_bal_credit;

            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->debit; 
                $sum_opening_balance_credit += $single_transaction->credit;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_customer_ledger')
            ->where('customer_id', '=', $customer_id)
            ->whereBetween('tbl_customer_ledger.transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'asc')
            ->get();

            $account_id = "C".$customer_id;
            $account_name = $current_customer->customer_name;
            ############CUSTOMER#################
        }

        elseif(!empty($supplier_id)){
            ############SUPPLIER#################
            $current_supplier = Supplier::where('supplier_id',$supplier_id)->first();

            $all_transactions_before_start_date = DB::table('tbl_supplier_ledger')
            ->where('supplier_id', '=', $supplier_id)
            ->where('tbl_supplier_ledger.transaction_date', '<', $start_date)
            ->get();

            $sum_opening_balance_debit = $current_supplier->op_bal_debit; 
            $sum_opening_balance_credit = $current_supplier->op_bal_credit;

            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->debit; 
                $sum_opening_balance_credit += $single_transaction->credit;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_supplier_ledger')
            ->where('supplier_id', '=', $supplier_id)
            ->whereBetween('tbl_supplier_ledger.transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'asc')
            ->get();

            $account_id = "S".$supplier_id;
            $account_name = $current_supplier->sup_name;
            ############SUPPLIER#################
        }



        return view('admin.report.statement.account-statement-pdf', compact('accounts','account_id','account_name','sum_opening_balance_debit','sum_opening_balance_credit','opening_balance','all_transactions','start_date','end_date'));
    }

    public function indexTrialBalance()
    {

        return view('admin.report.statement.trial-balance');
    }

    public function TrialBalance(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        ############START CASH/BANK/OTHERS ACCOUNTS##############
        $view_of_accounts = array();
        $all_account = DB::table('tbl_bank_account')
        //->leftJoin('tbl_account_type','tbl_account_type.account_type_id','=','tbl_bank_account.account_type_id')
        ->orderBy('account_type_id', 'asc')
        ->orderBy('bank_account_id', 'asc')
        ->get();

        foreach ($all_account as $key => $single_account) {

        $bank_account_id = $single_account->bank_account_id;
            
        $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

        $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->where('tbl_bank_transaction.transaction_date', '<', $start_date)
        ->get();

        $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
        $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;

        $bank_name = $initial_opening_balance->bank_name;


        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->deposit; 
            $sum_opening_balance_credit += $single_transaction->expense;
        }
        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->deposit; 
            $sum_credit += $single_transaction->expense;
            $sum_transaction_balance_debit += $single_transaction->deposit; 
            $sum_transaction_balance_credit += $single_transaction->expense; 

        }
        $transaction_balance_debit = $sum_transaction_balance_debit;
        $transaction_balance_credit = $sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;

        array_push($view_of_accounts, array('bank_name'=>"$bank_name",'opening_balance'=>$opening_balance,'transaction_balance_debit'=>$transaction_balance_debit,'transaction_balance_credit'=>$transaction_balance_credit,'closing_balance'=>$closing_balance));

        } // end of foreach loop

        ############END CASH/BANK/OTHERS ACCOUNTS##############

        ############START SALES ACCOUNTS##############
        $view_of_sales_accounts = array();
        $all_sales_account = DB::table('tbl_customer')
        ->get();

        foreach ($all_sales_account as $key => $single_account) {

        $customer_id = $single_account->customer_id;
        $customer_name = $single_account->customer_name;
            
        $initial_opening_balance = DB::table('tbl_customer')
        ->selectRaw('tbl_customer.*')
        ->where('tbl_customer.customer_id', '=', $customer_id)
        ->first();

        $all_transactions_before_start_date = DB::table('tbl_customer_ledger')
        ->where('customer_id', '=', $customer_id)
        ->where('tbl_customer_ledger.transaction_date', '<', $start_date)
        // ->where('tran_ref_name', '!=', "Discount")
        ->get();
        
        $sum_opening_balance_debit = $initial_opening_balance->op_bal_debit; 
        $sum_opening_balance_credit = $initial_opening_balance->op_bal_credit;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += 0; 
            $sum_opening_balance_credit += $single_transaction->credit;
        }

        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_customer_ledger')
        ->where('customer_id', '=', $customer_id)
        // ->where('tbl_customer_ledger.tran_ref_name', '!=',"Discount")
        // ->where('tbl_customer_ledger.tran_ref_name', '!=',"BuyProduct")
        // ->where('tbl_customer_ledger.tran_ref_id', '!=',"30")
        // ->where('tbl_customer_ledger.tran_ref_id', '!=',"31")
        // ->where('tbl_customer_ledger.tran_ref_id', '!=',"32")
        // ->where('tbl_customer_ledger.tran_ref_id', '!=',"33")
        ->whereBetween('tbl_customer_ledger.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->debit;
            $sum_credit += $single_transaction->credit;
            $sum_transaction_balance_debit += $single_transaction->debit; 
            $sum_transaction_balance_credit += $single_transaction->credit; 

        }
        $transaction_balance_debit = $sum_transaction_balance_debit;
        $transaction_balance_credit = $sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;

            if($opening_balance == 0 && $transaction_balance_debit == 0 && $transaction_balance_credit == 0 && $closing_balance == 0){
                continue;
            }else{
            array_push($view_of_sales_accounts, array('customer_name'=>"$customer_name",'opening_balance'=>$opening_balance,'transaction_balance_debit'=>$transaction_balance_debit,'transaction_balance_credit'=>$transaction_balance_credit,'closing_balance'=>$closing_balance));
            }
        } // end of foreach loop
        //var_dump($view_of_sales_accounts);
        ############END SALES ACCOUNTS##############

        ############Start Sales Return##############
        $view_of_sales_return = array();
        $all_transactions_before_start_date = DB::table('tbl_sales_return_master')
        ->selectRaw('SUM(advanced_amount) as advanced_amount')
        ->where('date', '<', $start_date)
        ->first();
        
        $all_transactions = DB::table('tbl_sales_return_master')
        ->selectRaw('SUM(advanced_amount) as advanced_amount')
        ->whereBetween('date', [$start_date, $end_date])
        ->first();

        $opening_balance = $all_transactions_before_start_date->advanced_amount;
        (empty($opening_balance))? 0 : $opening_balance;
        $transaction_balance = $all_transactions->advanced_amount;
        $closing_balance = $opening_balance + $transaction_balance;

        array_push($view_of_sales_return, array('account_name'=>"Sales Return",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance)); 
        //var_dump($view_of_expense);

        ############END Sales Return##############

        ############START PURCHASE ACCOUNTS##############
        $view_of_purchase_accounts = array();
        $all_purchase_account = DB::table('tbl_supplier')
        ->get();

        foreach ($all_purchase_account as $key => $single_account) {

        $supplier_id = $single_account->supplier_id;
        $sup_name = $single_account->sup_name;
            
        $initial_opening_balance = DB::table('tbl_supplier')
        ->selectRaw('tbl_supplier.*')
        ->where('tbl_supplier.supplier_id', '=', $supplier_id)
        ->first();

        $all_transactions_before_start_date = DB::table('tbl_supplier_ledger')
        ->where('supplier_id', '=', $supplier_id)
        ->where('tbl_supplier_ledger.transaction_date', '<', $start_date)
        // ->where('tran_ref_name', '!=', "Discount")
        // ->where('tran_ref_name', '!=', "BuyProduct")
        ->get();
        
        $sum_opening_balance_debit = $initial_opening_balance->op_bal_debit; 
        $sum_opening_balance_credit = $initial_opening_balance->op_bal_credit;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->debit; 
            $sum_opening_balance_credit += $single_transaction->credit; 
        }

        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit; //For supplier Dr-Cr

        $all_transactions = DB::table('tbl_supplier_ledger')
        ->where('supplier_id', '=', $supplier_id)
        // ->where('tbl_supplier_ledger.tran_ref_name', '!=',"Discount")
        // ->where('tran_ref_name', '!=', "BuyProduct")
        ->whereBetween('tbl_supplier_ledger.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->debit; 
            $sum_credit += $single_transaction->credit; 
            $sum_transaction_balance_debit += $single_transaction->debit; 
            $sum_transaction_balance_credit += $single_transaction->credit;

        }
        // $transaction_balance = $sum_transaction_balance_debit-$sum_transaction_balance_credit; //For supplier Dr-cr
        $transaction_balance_debit = $sum_transaction_balance_debit;
        $transaction_balance_credit = $sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;//For supplier Dr-Cr

        array_push($view_of_purchase_accounts, array('sup_name'=>"$sup_name",'opening_balance'=>$opening_balance,'transaction_balance_debit'=>$transaction_balance_debit,'transaction_balance_credit'=>$transaction_balance_credit,'closing_balance'=>$closing_balance));

        } // end of foreach loop

        ############END PURCHASE ACCOUNTS##############

        ############Start OTHER INCOME ##############
        $view_of_other_income = array();
        $all_incomes = DB::table('tbl_income_head')->get();

        foreach ($all_incomes as $key => $single_income){

        $income_head_id = $single_income->income_head_id;
            
        $initial_opening_balance_income = $single_income->opening_balance;

        $all_transactions_before_start_date = DB::table('tbl_income')
        ->where('income_head_id', '=', $income_head_id)
        ->where('tbl_income.date', '<', $start_date)
        ->get();
        
        $sum_opening_balance_debit = 0;
        $sum_opening_balance_credit = $initial_opening_balance_income;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->reverse_amount; 
            $sum_opening_balance_credit += $single_transaction->amount; 
        }

        $single_income->income_head;
        $opening_balance =  $sum_opening_balance_credit - $sum_opening_balance_debit;

        $all_transactions = DB::table('tbl_income')
        ->where('income_head_id', '=', $income_head_id)
        ->whereBetween('tbl_income.date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_transaction_balance_debit += $single_transaction->reverse_amount; 
            $sum_transaction_balance_credit +=  $single_transaction->amount; 
            $sum_debit += $single_transaction->reverse_amount; 
            $sum_credit += $single_transaction->amount; 
        }
        $transaction_balance = $sum_transaction_balance_credit-$sum_transaction_balance_debit;
        //$closing_balance = $sum_credit-$sum_debit;
        $closing_balance = $opening_balance+$transaction_balance;
        if($opening_balance == 0 && $transaction_balance == 0 && $closing_balance == 0){
            continue;
        }else{
            array_push($view_of_other_income, array('income_head'=>"$single_income->income_head",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance)); 
            }

        }
        
        //var_dump($view_of_expense);

        ############END OTHER INCOME ##############

        ############START EXPENSE ##############
        $view_of_expense = array();
        $all_expenses = DB::table('tbl_expense_head')->get();

        foreach ($all_expenses as $key => $single_expense){

        $expense_head_id = $single_expense->expense_head_id;
            
        $initial_opening_balance_expense = $single_expense->opening_balance;

        $all_transactions_before_start_date = DB::table('tbl_expense')
        ->where('expense_head_id', '=', $expense_head_id)
        ->where('tbl_expense.date', '<', $start_date)
        ->get();
        
        $sum_opening_balance_debit = $initial_opening_balance_expense;
        $sum_opening_balance_credit = 0;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->amount; 
            $sum_opening_balance_credit += $single_transaction->reverse_amount; 
        }

        $single_expense->expense_head;
        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_expense')
        ->where('expense_head_id', '=', $expense_head_id)
        ->whereBetween('tbl_expense.date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_transaction_balance_debit += $single_transaction->amount; 
            $sum_transaction_balance_credit += $single_transaction->reverse_amount; 
            $sum_debit += $single_transaction->amount; 
            $sum_credit += $single_transaction->reverse_amount; 
        }
        $transaction_balance = $sum_transaction_balance_debit-$sum_transaction_balance_credit;
        //$closing_balance = $sum_debit-$sum_credit;   
        $closing_balance = $opening_balance+$transaction_balance;
        if($opening_balance == 0 && $transaction_balance == 0 && $closing_balance == 0){
            continue;
        }else{
            array_push($view_of_expense, array('expense_head'=>"$single_expense->expense_head",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance)); 
            }
        }
        
        //var_dump($view_of_expense);

        ############ END EXPENSE ##############

        return view('admin.report.statement.trial-balance', compact('view_of_accounts','view_of_sales_accounts','view_of_sales_return','view_of_purchase_accounts','view_of_other_income','view_of_expense','start_date','end_date'));
    }

    public function TrialBalancePDF(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        ############START CASH/BANK/OTHERS ACCOUNTS##############
        $view_of_accounts = array();
        $all_account = DB::table('tbl_bank_account')
        //->leftJoin('tbl_account_type','tbl_account_type.account_type_id','=','tbl_bank_account.account_type_id')
        ->orderBy('account_type_id', 'asc')
        ->orderBy('bank_account_id', 'asc')
        ->get();

        foreach ($all_account as $key => $single_account) {

        $bank_account_id = $single_account->bank_account_id;
            
        $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

        $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->where('tbl_bank_transaction.transaction_date', '<', $start_date)
        ->get();

        $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
        $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;

        $bank_name = $initial_opening_balance->bank_name;
        
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->deposit; 
            $sum_opening_balance_credit += $single_transaction->expense;
        }
        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_bank_transaction')
        ->where('bank_account_id', '=', $bank_account_id)
        ->whereBetween('tbl_bank_transaction.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->deposit; 
            $sum_credit += $single_transaction->expense;
            $sum_transaction_balance_debit += $single_transaction->deposit; 
            $sum_transaction_balance_credit += $single_transaction->expense; 

        }
        $transaction_balance_debit = $sum_transaction_balance_debit;
        $transaction_balance_credit = $sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;

        array_push($view_of_accounts, array('bank_name'=>"$bank_name",'opening_balance'=>$opening_balance,'transaction_balance_debit'=>$transaction_balance_debit,'transaction_balance_credit'=>$transaction_balance_credit,'closing_balance'=>$closing_balance));

        } // end of foreach loop

        ############END CASH/BANK/OTHERS ACCOUNTS##############

        ############START SALES ACCOUNTS##############
        $view_of_sales_accounts = array();
        $all_sales_account = DB::table('tbl_customer')
        ->get();

        foreach ($all_sales_account as $key => $single_account) {

        $customer_id = $single_account->customer_id;
        $customer_name = $single_account->customer_name;
            
        $initial_opening_balance = DB::table('tbl_customer')
        ->selectRaw('tbl_customer.*')
        ->where('tbl_customer.customer_id', '=', $customer_id)
        ->first();

        $all_transactions_before_start_date = DB::table('tbl_customer_ledger')
        ->where('customer_id', '=', $customer_id)
        ->where('tbl_customer_ledger.transaction_date', '<', $start_date)
        ->where('tran_ref_name', '!=', "Discount")
        ->get();
        
        $sum_opening_balance_debit = $initial_opening_balance->op_bal_debit; 
        $sum_opening_balance_credit = $initial_opening_balance->op_bal_credit;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += 0; 
            $sum_opening_balance_credit += $single_transaction->credit;
        }

        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_customer_ledger')
        ->where('customer_id', '=', $customer_id)
        ->where('tbl_customer_ledger.tran_ref_name', '!=',"Discount")
        ->where('tbl_customer_ledger.tran_ref_name', '!=',"BuyProduct")
        ->where('tbl_customer_ledger.tran_ref_id', '!=',"30")
        ->where('tbl_customer_ledger.tran_ref_id', '!=',"31")
        ->where('tbl_customer_ledger.tran_ref_id', '!=',"32")
        ->where('tbl_customer_ledger.tran_ref_id', '!=',"33")
        ->whereBetween('tbl_customer_ledger.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->debit;
            $sum_credit += $single_transaction->credit;
            $sum_transaction_balance_debit += $single_transaction->debit; 
            $sum_transaction_balance_credit += $single_transaction->credit; 

        }
        $transaction_balance_debit = $sum_transaction_balance_debit;
        $transaction_balance_credit = $sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;

            if($opening_balance == 0 && $transaction_balance_debit == 0 && $transaction_balance_credit == 0 && $closing_balance == 0){
                continue;
            }else{
            array_push($view_of_sales_accounts, array('customer_name'=>"$customer_name",'opening_balance'=>$opening_balance,'transaction_balance_debit'=>$transaction_balance_debit,'transaction_balance_credit'=>$transaction_balance_credit,'closing_balance'=>$closing_balance));
            }
        } // end of foreach loop
        //var_dump($view_of_sales_accounts);
        ############END SALES ACCOUNTS##############

        ############Start Sales Return##############
        $view_of_sales_return = array();
        $all_transactions_before_start_date = DB::table('tbl_sales_return_master')
        ->selectRaw('SUM(advanced_amount) as advanced_amount')
        ->where('date', '<', $start_date)
        ->first();
        
        $all_transactions = DB::table('tbl_sales_return_master')
        ->selectRaw('SUM(advanced_amount) as advanced_amount')
        ->whereBetween('date', [$start_date, $end_date])
        ->first();

        $opening_balance = $all_transactions_before_start_date->advanced_amount;
        (empty($opening_balance))? 0 : $opening_balance;
        $transaction_balance = $all_transactions->advanced_amount;
        $closing_balance = $opening_balance + $transaction_balance;

        array_push($view_of_sales_return, array('account_name'=>"Sales Return",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance)); 
        //var_dump($view_of_expense);

        ############END Sales Return##############

        ############START PURCHASE ACCOUNTS##############
        $view_of_purchase_accounts = array();
        $all_purchase_account = DB::table('tbl_supplier')
        ->get();

        foreach ($all_purchase_account as $key => $single_account) {

        $supplier_id = $single_account->supplier_id;
        $sup_name = $single_account->sup_name;
            
        $initial_opening_balance = DB::table('tbl_supplier')
        ->selectRaw('tbl_supplier.*')
        ->where('tbl_supplier.supplier_id', '=', $supplier_id)
        ->first();

        $all_transactions_before_start_date = DB::table('tbl_supplier_ledger')
        ->where('supplier_id', '=', $supplier_id)
        ->where('tbl_supplier_ledger.transaction_date', '<', $start_date)
        ->where('tran_ref_name', '!=', "Discount")
        ->where('tran_ref_name', '!=', "BuyProduct")
        ->get();
        
        $sum_opening_balance_debit = $initial_opening_balance->op_bal_debit; 
        $sum_opening_balance_credit = $initial_opening_balance->op_bal_credit;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->debit; 
            $sum_opening_balance_credit += $single_transaction->credit; 
        }

        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit; //For supplier Dr-Cr

        $all_transactions = DB::table('tbl_supplier_ledger')
        ->where('supplier_id', '=', $supplier_id)
        ->where('tbl_supplier_ledger.tran_ref_name', '!=',"Discount")
        ->where('tran_ref_name', '!=', "BuyProduct")
        ->whereBetween('tbl_supplier_ledger.transaction_date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_debit += $single_transaction->debit; 
            $sum_credit += $single_transaction->credit; 
            $sum_transaction_balance_debit += $single_transaction->debit; 
            $sum_transaction_balance_credit += $single_transaction->credit;

        }
        // $transaction_balance = $sum_transaction_balance_debit-$sum_transaction_balance_credit; //For supplier Dr-cr
        $transaction_balance_debit = $sum_transaction_balance_debit;
        $transaction_balance_credit = $sum_transaction_balance_credit;
        $closing_balance = $sum_debit-$sum_credit;//For supplier Dr-Cr

        array_push($view_of_purchase_accounts, array('sup_name'=>"$sup_name",'opening_balance'=>$opening_balance,'transaction_balance_debit'=>$transaction_balance_debit,'transaction_balance_credit'=>$transaction_balance_credit,'closing_balance'=>$closing_balance));

        } // end of foreach loop

        ############END PURCHASE ACCOUNTS##############

        ############Start OTHER INCOME ##############
        $view_of_other_income = array();
        $all_incomes = DB::table('tbl_income_head')->get();

        foreach ($all_incomes as $key => $single_income){

        $income_head_id = $single_income->income_head_id;
            
        $initial_opening_balance_income = $single_income->opening_balance;

        $all_transactions_before_start_date = DB::table('tbl_income')
        ->where('income_head_id', '=', $income_head_id)
        ->where('tbl_income.date', '<', $start_date)
        ->get();
        
        $sum_opening_balance_debit = 0;
        $sum_opening_balance_credit = $initial_opening_balance_income;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->reverse_amount; 
            $sum_opening_balance_credit += $single_transaction->amount; 
        }

        $single_income->income_head;
        $opening_balance =  $sum_opening_balance_credit - $sum_opening_balance_debit;

        $all_transactions = DB::table('tbl_income')
        ->where('income_head_id', '=', $income_head_id)
        ->whereBetween('tbl_income.date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_transaction_balance_debit += $single_transaction->reverse_amount; 
            $sum_transaction_balance_credit +=  $single_transaction->amount; 
            $sum_debit += $single_transaction->reverse_amount; 
            $sum_credit += $single_transaction->amount; 
        }
        $transaction_balance = $sum_transaction_balance_credit-$sum_transaction_balance_debit;
        //$closing_balance = $sum_credit-$sum_debit;
        $closing_balance = $opening_balance+$transaction_balance;
        if($opening_balance == 0 && $transaction_balance == 0 && $closing_balance == 0){
            continue;
        }else{
            array_push($view_of_other_income, array('income_head'=>"$single_income->income_head",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance)); 
            }

        }
        
        //var_dump($view_of_expense);

        ############END OTHER INCOME ##############

        ############START EXPENSE ##############
        $view_of_expense = array();
        $all_expenses = DB::table('tbl_expense_head')->get();

        foreach ($all_expenses as $key => $single_expense){

        $expense_head_id = $single_expense->expense_head_id;
            
        $initial_opening_balance_expense = $single_expense->opening_balance;

        $all_transactions_before_start_date = DB::table('tbl_expense')
        ->where('expense_head_id', '=', $expense_head_id)
        ->where('tbl_expense.date', '<', $start_date)
        ->get();
        
        $sum_opening_balance_debit = $initial_opening_balance_expense;
        $sum_opening_balance_credit = 0;
        foreach($all_transactions_before_start_date as $key => $single_transaction){
            $sum_opening_balance_debit += $single_transaction->amount; 
            $sum_opening_balance_credit += $single_transaction->reverse_amount; 
        }

        $single_expense->expense_head;
        $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

        $all_transactions = DB::table('tbl_expense')
        ->where('expense_head_id', '=', $expense_head_id)
        ->whereBetween('tbl_expense.date', [$start_date, $end_date])
        ->get();

        //Calculation Start
        $sum_debit = $sum_opening_balance_debit; 
        $sum_credit = $sum_opening_balance_credit;
        $sum_transaction_balance_debit = 0; 
        $sum_transaction_balance_credit = 0;

        foreach($all_transactions as $key => $single_transaction){
            $sum_transaction_balance_debit += $single_transaction->amount; 
            $sum_transaction_balance_credit += $single_transaction->reverse_amount; 
            $sum_debit += $single_transaction->amount; 
            $sum_credit += $single_transaction->reverse_amount; 
        }
        $transaction_balance = $sum_transaction_balance_debit-$sum_transaction_balance_credit;
        //$closing_balance = $sum_debit-$sum_credit;   
        $closing_balance = $opening_balance+$transaction_balance;
        if($opening_balance == 0 && $transaction_balance == 0 && $closing_balance == 0){
            continue;
        }else{
            array_push($view_of_expense, array('expense_head'=>"$single_expense->expense_head",'opening_balance'=>$opening_balance,'transaction_balance'=>$transaction_balance,'closing_balance'=>$closing_balance)); 
            }
        }
        
        //var_dump($view_of_expense);

        ############ END EXPENSE ##############

        return view('admin.report.statement.trial-balance-pdf', compact('view_of_accounts','view_of_sales_accounts','view_of_sales_return','view_of_purchase_accounts','view_of_other_income','view_of_expense','start_date','end_date'));
    }
    public function warehouseReport(){

        $warehouses = DB::table('tbl_stock_location')
        ->join('tbl_stock','tbl_stock.stock_location_id','=','tbl_stock_location.stock_location_id')
        ->join('tbl_item','tbl_item.item_id','=','tbl_stock.item_id')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out,tbl_stock_location.stock_location_name,tbl_item.item_name')
        ->groupBy('tbl_stock_location.stock_location_id')
        ->groupBy('tbl_stock.item_id')
        ->get();

        // foreach ($warehouses as $key => $value) {
        //     echo $value->stock_location_id."-".$value->item_id."-".$value->stock_in."-".$value->stock_out."<br>";
        // }
        return view('admin.report.stock.warehouse', compact('warehouses'));
    }

    public function receivable(){

        $receivables = DB::table('tbl_sales_master')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_sales_master.customer_id')
        ->selectRaw('tbl_sales_master.sales_master_id,tbl_sales_master.voucher_ref,tbl_sales_master.memo_no,tbl_sales_master.memo_total,tbl_sales_master.sales_date,tbl_sales_master.advanced_amount,tbl_sales_master.discount,tbl_customer.customer_name')
        ->where(DB::raw('tbl_sales_master.memo_total - tbl_sales_master.discount - tbl_sales_master.advanced_amount'), '>', 0)
        ->orderBy('tbl_sales_master.sales_master_id', 'desc')
        ->get();
        //var_dump($receivables);
        return view('admin.report.sales.receivable', compact('receivables'));
    }

    public function grandReceivable(){

        $grandReceivable = DB::table('tbl_customer')
        ->leftJoin('tbl_customer_ledger','tbl_customer_ledger.customer_id','=','tbl_customer.customer_id')
        ->selectRaw('tbl_customer.*,SUM(credit) as credit,SUM(debit) as debit')
        ->groupBy('tbl_customer.customer_id')
        ->orderBy('customer_code', 'asc')
        ->get();
        //var_dump($receivables);
        return view('admin.report.sales.grand-receivable', compact('grandReceivable'));
    }

    public function payable(){

        $payables = DB::table('tbl_purchase_master')
        ->join('tbl_supplier','tbl_supplier.supplier_id','=','tbl_purchase_master.supplier_id')
        ->selectRaw('tbl_purchase_master.purchase_master_id,tbl_purchase_master.voucher_ref,tbl_purchase_master.advanced_amount,tbl_purchase_master.discount,tbl_purchase_master.attachment,tbl_purchase_master.memo_no,tbl_purchase_master.memo_total,tbl_purchase_master.purchase_date,tbl_purchase_master.status,tbl_supplier.sup_name,tbl_supplier.supplier_id')
        ->where('tbl_purchase_master.status', '=', 'Due')
        ->orderBy('tbl_purchase_master.purchase_master_id', 'desc')
        ->get();

        return view('admin.report.purchase.payable', compact('payables'));
    }


    public function inventoryDescriptive(){


        $stock_location = DB::table('tbl_stock_location')
        ->orderBy('stock_location_id', 'desc')
        ->get();

        return view('admin.report.stock.inventory-descriptive', compact('stock_location'));
    }

    public function inventoryDescriptiveReport(Request $request){

        $stock_location_id = $request->stock_location_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $inventory = array();
        $opening_inventory_array = array();
        $transaction_inventory_array = array();
        $closing_inventory_array = array();
        #############opening_inventory#############
        $opening_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<', $start_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_id', 'dsc')
        ->get();

        $qty = 0;
        $amount = 0;
        $srl = 0;

         $categories = DB::table('tbl_category') 
        ->orderBy('cata_name', 'asc')
        ->get();

        foreach($categories as $key => $singleCategory){
            $inner_qty = 0; $inner_amount = 0;
            foreach($opening_inventory as $key => $value){
                if($singleCategory->cata_name == $value->cata_name){
                    
                        $inner_qty += $value->stock_in-$value->stock_out;
                        $inner_amount += $value->mrp * ($value->stock_in-$value->stock_out);
                        $qty += $value->stock_in-$value->stock_out;
                        $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($opening_inventory_array, array('cata_name'=>"$singleCategory->cata_name",'op_qty'=>$inner_qty,'op_amount'=>number_format($inner_amount,2))); 

        }
        #############opening_inventory#############
         
        #############transaction_inventory#############
        $transaction_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('stock_change_date', [$start_date,$end_date])
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_id', 'dsc')
        ->get();

        $qty = 0;
        $amount = 0;
        $srl = 0;

         $categories = DB::table('tbl_category') 
        ->orderBy('cata_name', 'asc')
        ->get();

        foreach($categories as $key => $singleCategory){
            $inner_qty_in = 0; $inner_amount_in = 0;
            $inner_qty_out = 0; $inner_amount_out = 0;
            foreach($transaction_inventory as $key => $value){
                if($singleCategory->cata_name == $value->cata_name){
                    
                        $inner_qty_in += $value->stock_in;
                        $inner_amount_in += $value->mrp * ($value->stock_in);
                        $inner_qty_out += $value->stock_out;
                        $inner_amount_out += $value->mrp * ($value->stock_out);

                        $qty += $value->stock_in-$value->stock_out;
                        $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($transaction_inventory_array, array('cata_name'=>"$singleCategory->cata_name",'tr_qty_in'=>$inner_qty_in,'tr_amount_in'=>number_format($inner_amount_in,2),'tr_qty_out'=>$inner_qty_out,'tr_amount_out'=>number_format($inner_amount_out,2))); 

        }
        #############transaction_inventory#############
        #############closing_inventory#############
        $closing_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<=', $end_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_id', 'dsc')
        ->get();

        $qty = 0;
        $amount = 0;
        $srl = 0;

         $categories = DB::table('tbl_category') 
        ->orderBy('cata_name', 'asc')
        ->get();

        foreach($categories as $key => $singleCategory){
            $inner_qty = 0; $inner_amount = 0;
            foreach($closing_inventory as $key => $value){
                if($singleCategory->cata_name == $value->cata_name){
                    
                        $inner_qty += ($value->stock_in-$value->stock_out);
                        $inner_amount += $value->mrp * ($value->stock_in-$value->stock_out);
                        $qty += ($value->stock_in-$value->stock_out);
                        $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($closing_inventory_array, array('cata_name'=>"$singleCategory->cata_name",'cl_qty'=>$inner_qty,'cl_amount'=>number_format($inner_amount,2))); 

        }
        #############closing_inventory#############

        for ($i=0; $i < count($opening_inventory_array); $i++) { 
            
            $cata_name = $opening_inventory_array[$i]['cata_name'];
            $opening_qty = $opening_inventory_array[$i]['op_qty'];
            $opening_amount = $opening_inventory_array[$i]['op_amount'];

            $transaction_qty_in = $transaction_inventory_array[$i]['tr_qty_in'];
            $transaction_amount_in = $transaction_inventory_array[$i]['tr_amount_in'];
            $transaction_qty_out = $transaction_inventory_array[$i]['tr_qty_out'];
            $transaction_amount_out = $transaction_inventory_array[$i]['tr_amount_out'];

            $closing_qty = $closing_inventory_array[$i]['cl_qty'];
            $closing_amount = $closing_inventory_array[$i]['cl_amount'];

            array_push($inventory, array('cata_name'=>"$cata_name",'opening_qty'=>$opening_qty,'opening_amount'=>$opening_amount,'transaction_qty_in'=>$transaction_qty_in,'transaction_amount_in'=>$transaction_amount_in,'transaction_qty_out'=>$transaction_qty_out,'transaction_amount_out'=>$transaction_amount_out,'closing_qty'=>$closing_qty,'closing_amount'=>$closing_amount));
        }

        ############## TOTAL #############
        $inventory_today = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.mrp,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('stock_change_date', [$start_date ,$end_date])
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->get();
        $stock_in_total = 0; $stock_out_total = 0;
        $stock_in_today = 0; $stock_out_today = 0;
        foreach ($inventory_today as $key => $value) {
            $stock_in_today += $value->stock_in;
            $stock_in_total += ($value->stock_in * $value->mrp);
        }

        foreach ($inventory_today as $key => $value) {
            $stock_out_today += $value->stock_out;
            $stock_out_total += ($value->stock_out * $value->mrp);
        }

        $opening_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.mrp,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<', $start_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->get();
        $op_inventory_total_amount = 0; $op_inventory_total_qty = 0;
        foreach ($opening_inventory as $key => $value) {
            $op_inventory_total_qty += ($value->stock_in-$value->stock_out);
            $op_inventory_total_amount += (($value->stock_in-$value->stock_out) * $value->mrp);
        }

        $total_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.mrp,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<=', $end_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->get();
        $closing_inventory_total_amount = 0; $closing_inventory_total_qty = 0;
        foreach ($total_inventory as $key => $value) {
            $closing_inventory_total_qty += ($value->stock_in-$value->stock_out);
            $closing_inventory_total_amount += (($value->stock_in-$value->stock_out) * $value->mrp);
        }


        ############# TOTAL ###############

        $stock_location = DB::table('tbl_stock_location')
        ->orderBy('stock_location_id', 'desc')
        ->get();

        return view('admin.report.stock.inventory-descriptive', compact('op_inventory_total_qty','op_inventory_total_amount','stock_in_today','stock_in_total','stock_out_today','stock_out_total','closing_inventory_total_qty','closing_inventory_total_amount','start_date','end_date','inventory','stock_location','stock_location_id'));
    }

    public function inventoryDescriptivePrint($start_date,$end_date,$stock_location_id){


        $inventory = array();
        $opening_inventory_array = array();
        $transaction_inventory_array = array();
        $closing_inventory_array = array();
        #############opening_inventory#############
        $opening_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<', $start_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_id', 'dsc')
        ->get();

        $qty = 0;
        $amount = 0;
        $srl = 0;

         $categories = DB::table('tbl_category') 
        ->orderBy('cata_name', 'asc')
        ->get();

        foreach($categories as $key => $singleCategory){
            $inner_qty = 0; $inner_amount = 0;
            foreach($opening_inventory as $key => $value){
                if($singleCategory->cata_name == $value->cata_name){
                    
                        $inner_qty += $value->stock_in-$value->stock_out;
                        $inner_amount += $value->mrp * ($value->stock_in-$value->stock_out);
                        $qty += $value->stock_in-$value->stock_out;
                        $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($opening_inventory_array, array('cata_name'=>"$singleCategory->cata_name",'op_qty'=>$inner_qty,'op_amount'=>number_format($inner_amount,2))); 

        }
        #############opening_inventory#############
         
        #############transaction_inventory#############
        $transaction_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('stock_change_date', [$start_date,$end_date])
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_id', 'dsc')
        ->get();

        $qty = 0;
        $amount = 0;
        $srl = 0;

         $categories = DB::table('tbl_category') 
        ->orderBy('cata_name', 'asc')
        ->get();

        foreach($categories as $key => $singleCategory){
            $inner_qty_in = 0; $inner_amount_in = 0;
            $inner_qty_out = 0; $inner_amount_out = 0;
            foreach($transaction_inventory as $key => $value){
                if($singleCategory->cata_name == $value->cata_name){
                    
                        $inner_qty_in += $value->stock_in;
                        $inner_amount_in += $value->mrp * ($value->stock_in);
                        $inner_qty_out += $value->stock_out;
                        $inner_amount_out += $value->mrp * ($value->stock_out);

                        $qty += $value->stock_in-$value->stock_out;
                        $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($transaction_inventory_array, array('cata_name'=>"$singleCategory->cata_name",'tr_qty_in'=>$inner_qty_in,'tr_amount_in'=>number_format($inner_amount_in,2),'tr_qty_out'=>$inner_qty_out,'tr_amount_out'=>number_format($inner_amount_out,2))); 

        }
        #############transaction_inventory#############
        #############closing_inventory#############
        $closing_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,tbl_category.cata_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<=', $end_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_id', 'dsc')
        ->get();

        $qty = 0;
        $amount = 0;
        $srl = 0;

         $categories = DB::table('tbl_category') 
        ->orderBy('cata_name', 'asc')
        ->get();

        foreach($categories as $key => $singleCategory){
            $inner_qty = 0; $inner_amount = 0;
            foreach($closing_inventory as $key => $value){
                if($singleCategory->cata_name == $value->cata_name){
                    
                        $inner_qty += $value->stock_in-$value->stock_out;
                        $inner_amount += $value->mrp * ($value->stock_in-$value->stock_out);
                        $qty += $value->stock_in-$value->stock_out;
                        $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($closing_inventory_array, array('cata_name'=>"$singleCategory->cata_name",'cl_qty'=>$inner_qty,'cl_amount'=>number_format($inner_amount,2))); 

        }
        #############closing_inventory#############

        for ($i=0; $i < count($opening_inventory_array); $i++) { 
            
            $cata_name = $opening_inventory_array[$i]['cata_name'];
            $opening_qty = $opening_inventory_array[$i]['op_qty'];
            $opening_amount = $opening_inventory_array[$i]['op_amount'];

            $transaction_qty_in = $transaction_inventory_array[$i]['tr_qty_in'];
            $transaction_amount_in = $transaction_inventory_array[$i]['tr_amount_in'];
            $transaction_qty_out = $transaction_inventory_array[$i]['tr_qty_out'];
            $transaction_amount_out = $transaction_inventory_array[$i]['tr_amount_out'];

            $closing_qty = $closing_inventory_array[$i]['cl_qty'];
            $closing_amount = $closing_inventory_array[$i]['cl_amount'];

            array_push($inventory, array('cata_name'=>"$cata_name",'opening_qty'=>$opening_qty,'opening_amount'=>$opening_amount,'transaction_qty_in'=>$transaction_qty_in,'transaction_amount_in'=>$transaction_amount_in,'transaction_qty_out'=>$transaction_qty_out,'transaction_amount_out'=>$transaction_amount_out,'closing_qty'=>$closing_qty,'closing_amount'=>$closing_amount));
        }

        ############## TOTAL #############
        $inventory_today = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.mrp,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('stock_change_date', [$start_date ,$end_date])
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->get();
        $stock_in_total = 0; $stock_out_total = 0;
        $stock_in_today = 0; $stock_out_today = 0;
        foreach ($inventory_today as $key => $value) {
            $stock_in_today += $value->stock_in;
            $stock_in_total += ($value->stock_in * $value->mrp);
        }

        foreach ($inventory_today as $key => $value) {
            $stock_out_today += $value->stock_out;
            $stock_out_total += ($value->stock_out * $value->mrp);
        }

        $opening_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.mrp,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<', $start_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->get();
        $op_inventory_total_amount = 0; $op_inventory_total_qty = 0;
        foreach ($opening_inventory as $key => $value) {
            $op_inventory_total_qty += ($value->stock_in-$value->stock_out);
            $op_inventory_total_amount += (($value->stock_in-$value->stock_out) * $value->mrp);
        }

        $total_inventory = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.mrp,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<=', $end_date)
        ->where('stock_location_id', $stock_location_id)
        ->groupBy('tbl_item.item_id')
        ->get();
        $closing_inventory_total_amount = 0; $closing_inventory_total_qty = 0;
        foreach ($total_inventory as $key => $value) {
            $closing_inventory_total_qty += ($value->stock_in-$value->stock_out);
            $closing_inventory_total_amount += (($value->stock_in-$value->stock_out) * $value->mrp);
        }


        ############# TOTAL ###############

        $stock_location = DB::table('tbl_stock_location')
        ->where('stock_location_id', $stock_location_id)
        ->first();

        return view('admin.report.stock.inventory-descriptive-print', compact('op_inventory_total_qty','op_inventory_total_amount','stock_in_today','stock_in_total','stock_out_today','stock_out_total','closing_inventory_total_qty','closing_inventory_total_amount','start_date','end_date','inventory','stock_location'));
    }

    public function inventoryLocate(){

        $items = Item::where('is_active',1)->orderBy("item_name")->get();
        $stock_locations = DB::table('tbl_stock_location')->get();    
        $stock_array = array();


        foreach($items as $key => $singleItem){
            $stock = DB::table('tbl_stock')
            ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->join('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
            ->join('tbl_stock_location','tbl_stock_location.stock_location_id','=','tbl_stock.stock_location_id')
            ->selectRaw('tbl_item.item_id,tbl_item.item_name, tbl_item.item_code, tbl_stock.stock_location_id,tbl_stock_location.stock_location_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
            ->where('tbl_stock.item_id', $singleItem->item_id)
            ->groupBy('tbl_stock.stock_location_id')
            ->orderBy('tbl_stock.stock_location_id', 'asc')
            ->get();
            $inner_array = array();

            foreach($stock as $key => $data){
                if($data->stock_in-$data->stock_out > 0){
                    array_push($inner_array, array('item_id'=>"$data->item_id",'item_code'=>"$data->item_code",'item_name'=>"$data->item_name",'stock_location_name'=>$data->stock_location_name,'stock'=>$data->stock_in-$data->stock_out));
                }
            }

            if(count($inner_array)){
                for ($i=0; $i < count($inner_array); $i++) { 
                    array_push($stock_array, array('item_id'=>intVal($inner_array[$i]['item_id']),'item_code'=>$inner_array[$i]['item_code'],'item_name'=>$inner_array[$i]['item_name'],'stock_location_name'=>$inner_array[$i]['stock_location_name'],'stock'=>$inner_array[$i]['stock']));
                }
            }
        }

        return view('admin.report.stock.locate-item',compact('stock_array'));
    }


    public function cashBook()
    {   
        return view('admin.report.statement.cash-book');
    }

    public function cashBookReport(Request $request)
    {  
        $reporting_date = $request->reporting_date;

        $accountGroups = DB::table('tbl_bank_account')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->where('tbl_bank_account.account_group_id','1')
        ->get();

        $opening_balance_array = array();
        foreach ($accountGroups as $key => $value) {

            $bank_account_id = $value->bank_account_id;
            #start opening balance of accounts
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;

            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;
            array_push($opening_balance_array,array("opening_balance"=>$opening_balance));
            #end opening balance of accounts
        }

        $group_account_ledgers_income = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id', '=', 'tbl_bank_transaction.bank_account_id')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name')
        ->where('tbl_account_group.id',1)
        ->where('tbl_bank_transaction.deposit','>',0)
        ->whereBetween('tbl_bank_transaction.transaction_date',[$reporting_date,$reporting_date])
        ->orderBy('tbl_bank_transaction.voucher_ref', 'asc')
        ->get();

        $group_account_ledgers_expense = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id', '=', 'tbl_bank_transaction.bank_account_id')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name')
        ->where('tbl_account_group.id',1)
        ->where('tbl_bank_transaction.expense','>',0)
        ->whereBetween('tbl_bank_transaction.transaction_date',[$reporting_date,$reporting_date])
        ->orderBy('tbl_bank_transaction.voucher_ref', 'asc')
        ->get();
        
        ###########################
        //INCOME BALANCE
        ###########################
        $income_balance_array = array();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;

            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$reporting_date, $reporting_date])
            ->groupBy('bank_account_id')
            ->first();

            if($all_transactions){
                $income_balance = $opening_balance + $all_transactions->debit ;
            }
            else{
                 $income_balance = $opening_balance ;
            }
            array_push($income_balance_array,array("income_balance"=>$income_balance));
        }

        ###########################
        //EXPENSE BALANCE
        ###########################
        $expense_balance_array = array();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$reporting_date, $reporting_date])
            ->groupBy('bank_account_id')
            ->first();

            if($all_transactions){
                $expense_balance = $all_transactions->credit ;
            }
            else{
                $expense_balance = 0 ;
            }
            array_push($expense_balance_array,array("expense_balance"=>$expense_balance));
        }
        
        ###########################
        //CLOSING BALANCE
        ###########################
        $closing_balance_array = array();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$reporting_date, $reporting_date])
            ->groupBy('bank_account_id')
            ->first();

             
            if($all_transactions){
                $closing_balance = $opening_balance + $all_transactions->debit - $all_transactions->credit;
            }
            else{
                 $closing_balance = $opening_balance;
            }

            array_push($closing_balance_array,array("closing_balance"=>$closing_balance));
        }


        return view('admin.report.statement.cash-book',compact('accountGroups','opening_balance_array','income_balance_array','expense_balance_array','closing_balance_array','group_account_ledgers_income','group_account_ledgers_expense','reporting_date'));
    }

    public function cashBookReportPDF($reporting_date)
    {  
        
        $accountGroups = DB::table('tbl_bank_account')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->where('tbl_bank_account.account_group_id','1')
        ->get();

        $opening_balance_array = array();
        foreach ($accountGroups as $key => $value) {

            $bank_account_id = $value->bank_account_id;
            #start opening balance of accounts
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;
            array_push($opening_balance_array,array("opening_balance"=>$opening_balance));
            #end opening balance of accounts
        }

        $group_account_ledgers_income = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id', '=', 'tbl_bank_transaction.bank_account_id')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name')
        ->where('tbl_account_group.id',1)
        ->where('tbl_bank_transaction.deposit','>',0)
        ->whereBetween('tbl_bank_transaction.transaction_date',[$reporting_date,$reporting_date])
        ->orderBy('tbl_bank_transaction.voucher_ref', 'asc')
        ->get();

        $group_account_ledgers_expense = DB::table('tbl_bank_transaction')
        ->join('tbl_bank_account','tbl_bank_account.bank_account_id', '=', 'tbl_bank_transaction.bank_account_id')
        ->join('tbl_account_group','tbl_account_group.id', '=', 'tbl_bank_account.account_group_id')
        ->selectRaw('tbl_bank_transaction.*,tbl_bank_account.bank_name')
        ->where('tbl_account_group.id',1)
        ->where('tbl_bank_transaction.expense','>',0)
        ->whereBetween('tbl_bank_transaction.transaction_date',[$reporting_date,$reporting_date])
        ->orderBy('tbl_bank_transaction.voucher_ref', 'asc')
        ->get();

        ###########################
        //INCOME BALANCE
        ###########################
        $income_balance_array = array();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$reporting_date, $reporting_date])
            ->groupBy('bank_account_id')
            ->first();

            if($all_transactions){
                $income_balance = $opening_balance + $all_transactions->debit ;
            }
            else{
                 $income_balance = $opening_balance ;
            }
            array_push($income_balance_array,array("income_balance"=>$income_balance));
        }

        ###########################
        //EXPENSE BALANCE
        ###########################
        $expense_balance_array = array();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$reporting_date, $reporting_date])
            ->groupBy('bank_account_id')
            ->first();

            if($all_transactions){
                $expense_balance = $all_transactions->credit ;
            }
            else{
                $expense_balance = 0 ;
            }
            array_push($expense_balance_array,array("expense_balance"=>$expense_balance));
        }

        
        ###########################
        //CLOSING BALANCE
        ###########################
        $closing_balance_array = array();

        foreach ($accountGroups as $key => $value) {
            $bank_account_id = $value->bank_account_id;
            $initial_opening_balance = DB::table('tbl_bank_account')
            ->where('tbl_bank_account.bank_account_id', '=', $bank_account_id)
            ->first();

            $all_transactions_before_start_date = DB::table('tbl_bank_transaction')
            ->where('bank_account_id', '=', $bank_account_id)
            ->where('tbl_bank_transaction.transaction_date', '<', $reporting_date)
            ->get();

            $sum_opening_balance_debit = $initial_opening_balance->op_bal_dr; 
            $sum_opening_balance_credit = $initial_opening_balance->op_bal_cr;
            foreach($all_transactions_before_start_date as $key => $single_transaction){
                $sum_opening_balance_debit += $single_transaction->deposit; 
                $sum_opening_balance_credit += $single_transaction->expense;
            }
            $opening_balance = $sum_opening_balance_debit - $sum_opening_balance_credit;

            $all_transactions = DB::table('tbl_bank_transaction')
            ->selectRaw('SUM(deposit) as debit,SUM(expense) as credit')
            ->where('bank_account_id', '=', $bank_account_id)
            ->whereBetween('tbl_bank_transaction.transaction_date', [$reporting_date, $reporting_date])
            ->groupBy('bank_account_id')
            ->first();

             
            if($all_transactions){
                $closing_balance = $opening_balance + $all_transactions->debit - $all_transactions->credit;
            }
            else{
                 $closing_balance = $opening_balance;
            }

            array_push($closing_balance_array,array("closing_balance"=>$closing_balance));
        }


        return view('admin.report.statement.cash-book-pdf',compact('accountGroups','opening_balance_array','income_balance_array','expense_balance_array','closing_balance_array','group_account_ledgers_income','group_account_ledgers_expense','reporting_date'));
    }

    public function stockTransfer()
    {   
        return view('admin.report.stock.stock-transfer');
    }

    public function stockTransferReport(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $stock_transfers = StockTransfer::whereBetween('date', [$start_date, $end_date])->get();
       
        return view('admin.report.stock.stock-transfer',compact('start_date','end_date','stock_transfers'));
    }

    public function stockTransferReportPrint($start_date, $end_date)
    {   

        $stock_transfers = StockTransfer::whereBetween('date', [$start_date, $end_date])->get();
       
        return view('admin.report.stock.stock-transfer-pdf',compact('start_date','end_date','stock_transfers'));
    }

    ########################################################
    ## START DESCRIPTIVE - PRODUCT
    #######################################################

    public function inventoryDescriptiveProduct(){

        return view('admin.report.stock.inventory-descriptive-product');
    }

    public function inventoryDescriptiveReportProduct(Request $request){

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return $this->inventoryDescriptiveReportProductFunction($start_date,$end_date,$type="show");

    }

    public function inventoryDescriptiveProductPrint($start_date,$end_date){

        return $this->inventoryDescriptiveReportProductFunction($start_date,$end_date,$type="print");

    }

    public function inventoryDescriptiveReportProductFunction($start_date,$end_date,$type){

        $items = DB::table('tbl_item')
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        $inventory = array();
        $opening_inventory_array = array();
        $transaction_inventory_array = array();
        $closing_inventory_array = array();
        #############opening_inventory#############
        $opening_inventory = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<', $start_date)

        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();
        
        $qty = 0; $amount = 0;

        foreach($items as $singleItem){
            $inner_qty = 0; $inner_amount = 0;
            foreach($opening_inventory as $key => $value){
                if("$singleItem->item_name" == "$value->item_name"){
                    $inner_qty += $value->stock_in-$value->stock_out;
                    $inner_amount += $value->mrp * ($value->stock_in-$value->stock_out);

                    $qty += $value->stock_in-$value->stock_out;
                    $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($opening_inventory_array, array('item_name'=>"$singleItem->item_name",'op_qty'=>$inner_qty,'op_amount'=>$inner_amount)); 
        }
        

        #############opening_inventory#############
         
        #############transaction_inventory#############
        $transaction_inventory = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->whereBetween('stock_change_date', [$start_date,$end_date])

        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        foreach($items as $singleItem){
            $inner_qty_in = 0; $inner_amount_in = 0;
            $inner_qty_out = 0; $inner_amount_out = 0;
            foreach($transaction_inventory as $key => $value){
                if("$singleItem->item_name" == "$value->item_name"){
                    $inner_qty_in += $value->stock_in;
                    $inner_amount_in += $value->mrp * ($value->stock_in);
                    $inner_qty_out += $value->stock_out;
                    $inner_amount_out += $value->mrp * ($value->stock_out);

                    $qty += $value->stock_in-$value->stock_out;
                    $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                    
                }
            }
            array_push($transaction_inventory_array, array('item_name'=>"$singleItem->item_name",'tr_qty_in'=>$inner_qty_in,'tr_amount_in'=>$inner_amount_in,'tr_qty_out'=>$inner_qty_out,'tr_amount_out'=>$inner_amount_out)); 
        }
            

        
        #############transaction_inventory#############
        #############closing_inventory#############
        $closing_inventory = DB::table('tbl_stock')
        ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_code,tbl_item.item_name,tbl_item.mrp,tbl_item.opening_stock_qty,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_change_date','<=', $end_date)

        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();

        $qty = 0;
        $amount = 0;
        $srl = 0;
        foreach($items as $singleItem){
            $inner_qty = 0; $inner_amount = 0;
            foreach($closing_inventory as $key => $value){
                if("$singleItem->item_name" == "$value->item_name"){
                    $inner_qty += ($value->stock_in-$value->stock_out);
                    $inner_amount += $value->mrp * ($value->stock_in-$value->stock_out);
                    $qty += ($value->stock_in-$value->stock_out);
                    $amount += $value->mrp * ($value->stock_in-$value->stock_out);
                }
            }
            array_push($closing_inventory_array, array('item_name'=>"$singleItem->item_name",'cl_qty'=>$inner_qty,'cl_amount'=>$inner_amount)); 
        }

        #############closing_inventory#############

        //return array("XXXXXXXXX"=>$opening_inventory_array,"YYYYYYYYY"=>$transaction_inventory_array,"KKKKKKKKK"=>$closing_inventory_array);

        for ($i=0; $i < count($opening_inventory_array); $i++) { 
            
            $item_name = $opening_inventory_array[$i]['item_name'];
            $opening_qty = $opening_inventory_array[$i]['op_qty'];
            $opening_amount = $opening_inventory_array[$i]['op_amount'];

            $transaction_qty_in = $transaction_inventory_array[$i]['tr_qty_in'];
            $transaction_amount_in = $transaction_inventory_array[$i]['tr_amount_in'];
            $transaction_qty_out = $transaction_inventory_array[$i]['tr_qty_out'];
            $transaction_amount_out = $transaction_inventory_array[$i]['tr_amount_out'];

            $closing_qty = $closing_inventory_array[$i]['cl_qty'];
            $closing_amount = $closing_inventory_array[$i]['cl_amount'];

            array_push($inventory, array('item_name'=>"$item_name",'opening_qty'=>$opening_qty,'opening_amount'=>$opening_amount,'transaction_qty_in'=>$transaction_qty_in,'transaction_amount_in'=>$transaction_amount_in,'transaction_qty_out'=>$transaction_qty_out,'transaction_amount_out'=>$transaction_amount_out,'closing_qty'=>$closing_qty,'closing_amount'=>$closing_amount));
        }

        if($type=="show"){
            return view('admin.report.stock.inventory-descriptive-product', compact('start_date','end_date','inventory'));
        }
        else{
            return view('admin.report.stock.inventory-descriptive-product-pdf', compact('start_date','end_date','inventory'));
        }
    }

    ########################################################
    ## END DESCRIPTIVE - PRODUCT
    #######################################################

    

}
