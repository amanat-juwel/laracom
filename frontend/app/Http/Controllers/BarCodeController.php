<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use DB;

class BarCodeController extends Controller
{
    public function index(){

    	$purchase_invoices = Purchase::all()->sortByDesc('purchase_master_id');
    	return view('barcode.index',compact('purchase_invoices'));
    }

    public function create(Request $request){

        $barcode_array = array();

        $items = DB::table('tbl_item')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_sub_category','tbl_sub_category.id','=','tbl_item.sub_cata_id') // leftJoin will ignore the null value of Sub-Category in the Item List
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->leftJoin('tbl_unit','tbl_unit.id','=','tbl_item.unit_id')
        ->selectRaw('tbl_item.*,tbl_category.cata_name,tbl_brand.brand_name,tbl_sub_category.name as sub_cata_name,tbl_unit.name as unit')
        ->groupBy('tbl_item.item_id')
        ->orderBy('tbl_item.item_code', 'asc')
        ->get();

        $count = count($items);

        foreach($items as $data){
	        array_push($barcode_array, array('item_name'=>"$data->item_name",'item_code'=>"$data->item_code",'price'=>"$data->mrp"));
	    }

    	return view('barcode.print',compact('barcode_array'));
    }

}
