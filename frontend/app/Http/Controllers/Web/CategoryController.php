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

class CategoryController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function show($cata_name)
    {

        $category = DB::table('tbl_category')->where('cata_name',"$cata_name")->first();

        if($category != null) {
            $items = Item::orderBy('item_name','asc')->where('cata_id', $category->cata_id)->paginate(16);
        } else {
            $category = DB::table('tbl_sub_category')->where('name',"$cata_name")->first();
            $items = Item::orderBy('item_name','asc')->where('sub_cata_id', $category->id)->paginate(16);
        }


        //return dd($items);

        return view('frontend.product.category.show', compact('category','items'));
    }


}
