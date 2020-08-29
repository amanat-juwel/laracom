<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\StockMaster;
use App\Stock;
use App\Item;
use App\Category;
use DB;
use PDF;

class FinishedProductReportController  extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function stockRegister(){
        $items = Item::all()->sortBy('name');
        return view('admin.report.finished-product.stock-register',compact('items'));
    }

    public function stockRegisterReport(Request $request){

        $item_id = $request->item_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $opening_stock_obj = DB::table('tbl_stock')
        ->leftJoin('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
        ->selectRaw('SUM(stock_in) as stock_in, SUM(stock_out) as stock_out')
        ->where('tbl_stock_master.date', '<', $start_date)
        ->where('item_id',$item_id)
        ->groupBy('item_id')
        ->first();

        if(isset($opening_stock_obj)){
            $opening_stock = $opening_stock_obj->stock_in - $opening_stock_obj->stock_out;
        }
        else{
            $opening_stock = 0;
        }

        $item_registers = DB::table('tbl_stock')
        ->leftJoin('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
        ->whereBetween('tbl_stock_master.date', [$start_date, $end_date])
        ->where('item_id',$item_id)
        ->orderBy('tbl_stock_master.date', 'asc')
        ->get();
     
        $items = Item::all()->sortBy('name');

        return view('admin.report.finished-product.stock-register',compact('items','opening_stock','item_registers','start_date','end_date','item_id'));
    }

    public function stockRegisterPrint($item_id, $start_date, $end_date){

        $opening_stock_obj = DB::table('tbl_stock')
        ->leftJoin('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
        ->selectRaw('SUM(stock_in) as stock_in, SUM(stock_out) as stock_out')
        ->where('tbl_stock_master.date', '<', $start_date)
        ->where('item_id',$item_id)
        ->groupBy('item_id')
        ->first();

        if(isset($opening_stock_obj)){
            $opening_stock = $opening_stock_obj->stock_in - $opening_stock_obj->stock_out;
        }
        else{
            $opening_stock = 0;
        }

        $item_registers = DB::table('tbl_stock')
        ->leftJoin('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
        ->whereBetween('tbl_stock_master.date', [$start_date, $end_date])
        ->where('item_id',$item_id)
        ->orderBy('tbl_stock_master.date', 'asc')
        ->get();
     
        $item = Item::find($item_id);

        return view('admin.report.finished-product.stock-register-print',compact('item','opening_stock','item_registers','start_date','end_date','item_id'));
    }

    public function currentStock()
    {   
    
        $item = DB::table('tbl_stock')
        ->leftJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_name,tbl_category.cata_name,tbl_brand.brand_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();
        return view('admin.report.finished-product.current-stock', compact('item'));
        
    } 
    
    public function currentStockPrint()
    {   
        $item = DB::table('tbl_stock')
        ->leftJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->selectRaw('tbl_item.item_id,tbl_item.item_name,tbl_category.cata_name,tbl_brand.brand_name,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();
        
        //$pdf = PDF::loadView('report.stock.current-stock-pdf', $item);
        //return $pdf->download('stock-report.pdf');
        return view('admin.report.finished-product.current-stock-print', compact('item'));
    } 

    ########################################################
    ## START DESCRIPTIVE - PRODUCT
    #######################################################

    public function inventory(){

        $categories = Category::all()->sortBy('cata_name');
        return view('admin.report.finished-product.inventory-descriptive-product', compact('categories'));
    }

    public function inventoryReport(Request $request){

        $cata_id = $request->cata_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return $this->inventoryReportFunction($cata_id,$start_date,$end_date,$type="show");

    }

    public function inventoryReportPrint($start_date,$end_date){

        return $this->inventoryReportFunction($start_date,$end_date,$type="print");

    }

    public function inventoryReportFunction($cata_id,$start_date,$end_date,$type){

        $categories = Category::all()->sortBy('cata_name');

        if($cata_id == 0){
            $item = DB::table('tbl_stock')
            ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->selectRaw('tbl_item.item_id,tbl_item.item_name,tbl_item.item_code,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
            ->groupBy('tbl_item.item_id')
            ->orderBy('tbl_item.item_name', 'asc')
            ->get();
        }

        else{
            $item = DB::table('tbl_stock')
            ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->selectRaw('tbl_item.item_id,tbl_item.item_name,tbl_item.item_code,SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
            ->where('tbl_item.cata_id',$cata_id)
            ->groupBy('tbl_item.item_id')
            ->orderBy('tbl_item.item_name', 'asc')
            ->get();
        }

        //return dd($item);

        /*
        *   Opening Invemtory
        *
        */
        $opening = array();

        foreach($item as $data){

            $inventory_history = DB::table('tbl_stock')
            ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->leftJoin('tbl_stock_master','tbl_stock_master.id','=','tbl_stock.stock_master_id')
            ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
            ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_stock_master.date,tbl_batch.purchase_rate as rate')
            ->where('tbl_stock.item_id','=',$data->item_id)
            ->where('tbl_stock_master.date','<',$start_date)
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

            array_push($opening, array('item_id'=>"$data->item_id",'name'=>"$data->item_name",'code'=>"$data->item_code",'quantity'=>$quantity,'rate'=>$rate, 'amount'=>$quantity * $rate));
        }
        //dd($opening);

        /*
        *   Transaction Inventory
        *
        */

        $transit_in = array();

        foreach($item as $data){

            $inventory_history = DB::table('tbl_stock')
            ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->leftJoin('tbl_stock_master','tbl_stock_master.id','=','tbl_stock.stock_master_id')
            ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
            ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_stock_master.date,tbl_batch.purchase_rate as rate')
            ->where('tbl_stock.item_id','=',$data->item_id)
            ->whereBetween('tbl_stock_master.date',[$start_date,$end_date])
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
                            //$quantity -= $single_data->stock_out;
                        }
                    }
                }
                array_push($transit_in, array('item_id'=>"$data->item_id",'name'=>"$data->item_name",'code'=>"$data->item_code",'quantity'=>$quantity,'rate'=>$rate, 'amount'=>$quantity * $rate));
            }

        $transit_out = array();

        foreach($item as $data){

            $inventory_history = DB::table('tbl_stock')
            ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->leftJoin('tbl_stock_master','tbl_stock_master.id','=','tbl_stock.stock_master_id')
            ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
            ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_stock_master.date,tbl_batch.purchase_rate as rate')
            ->where('tbl_stock.item_id','=',$data->item_id)
            ->whereBetween('tbl_stock_master.date',[$start_date,$end_date])
            ->orderBy('tbl_stock.stock_id', 'asc')
            ->get(); 
            
            $quantity = 0;
            $rate = 0;
            
            if(count($inventory_history)>0){
                foreach($inventory_history as $key => $single_data){
                    if($single_data->stock_in>0){
                        // $var_1 = $quantity * $rate;
                        // $var_2 = $single_data->stock_in * $single_data->rate;
                        // $sum_var = $var_1 + $var_2;

                        // $quantity += $single_data->stock_in;
                        // $rate = $sum_var / $quantity;
                    }
                    else{
                        $quantity -= $single_data->stock_out;
                    }
                }
            }
            array_push($transit_out, array('item_id'=>"$data->item_id",'name'=>"$data->item_name",'code'=>"$data->item_code",'quantity'=>$quantity,'rate'=>$rate, 'amount'=>$quantity * $rate));

            
        }

        //dd($transit);

        /*
        *   Closing Invemtory
        *
        */

        $closing = array();

        foreach($item as $data){

            $inventory_history = DB::table('tbl_stock')
            ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            ->leftJoin('tbl_stock_master','tbl_stock_master.id','=','tbl_stock.stock_master_id')
            ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
            ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_stock_master.date,tbl_batch.purchase_rate as rate')
            ->where('tbl_stock.item_id','=',$data->item_id)
            ->where('tbl_stock_master.date','<=',$end_date)
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
            array_push($closing, array('item_id'=>"$data->item_id",'name'=>"$data->item_name",'code'=>"$data->item_code",'quantity'=>$quantity,'rate'=>$rate, 'amount'=>$quantity * $rate));
        }

        //dd($closing);

        return view('admin.report.finished-product.inventory-descriptive-product', compact('categories','cata_id','item','opening','transit_in','transit_out','closing','start_date','end_date'));
       
    }




    

}
