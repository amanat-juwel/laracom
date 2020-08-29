<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Item;
use App\Category;
use App\Subcategory;
use App\SubSubcategory;
use App\Brand;
use App\Unit;
use App\StockMaster;
use DB;
use Input;
use Validator;
use Auth;
use App\Batch;
use App\Stock;

class ProductController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function show($id, $item_name)
    {
        $item = DB::table('tbl_item')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_sub_category','tbl_sub_category.id','=','tbl_item.sub_cata_id')
        ->leftJoin('tbl_sub_sub_category','tbl_sub_sub_category.id','=','tbl_item.sub_sub_cata_id')
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->leftJoin('tbl_unit','tbl_unit.id','=','tbl_item.unit_id')
        ->selectRaw('tbl_item.*,tbl_category.cata_name,tbl_sub_category.name as sub_cata_name ,tbl_sub_sub_category.name as sub_sub_cata_name ,tbl_brand.brand_name,tbl_unit.name as unit ')
        ->where('item_id',$id)
        ->first();

        $stock_qty = DB::table('tbl_stock')
        ->selectRaw('sum(stock_in) - sum(stock_out) as stock_qty')
        ->where('item_id',$id)
        ->value('stock_qty');

        if(Auth::user()){
            $is_wishlist = DB::table('wishlists')
            ->selectRaw('count(id) as count')
            ->where('item_id', $id)
            ->where('user_id', Auth::user()->id)
            ->value('count');
        }
        else{
            $is_wishlist = null;
        }

        $related_items = DB::table('tbl_item')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_sub_category','tbl_sub_category.id','=','tbl_item.sub_cata_id')
        ->leftJoin('tbl_sub_sub_category','tbl_sub_sub_category.id','=','tbl_item.sub_sub_cata_id')
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->leftJoin('tbl_unit','tbl_unit.id','=','tbl_item.unit_id')
        ->selectRaw('tbl_item.*,tbl_category.cata_id,tbl_category.cata_name,tbl_sub_category.name as sub_cata_name ,tbl_sub_sub_category.name as sub_sub_cata_name ,tbl_brand.brand_name,tbl_unit.name as unit ')
        ->where('tbl_item.cata_id',$item->cata_id)
        ->where('tbl_item.item_id','!=',$item->item_id)
        ->orderBy(DB::raw('RAND()'))
        ->take(8)
        ->get();

        return view('frontend.product.show', compact('item','is_wishlist','stock_qty','related_items'));
    }


}
