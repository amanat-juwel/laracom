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
use App\Slider;

class BrandController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function index()
    {
        $brands = DB::table('tbl_brand')->orderBy('brand_name')->get();

        return view('frontend.product.brand.index', compact('brands'));
    }

    public function show($brand_name)
    {
        $brand = DB::table('tbl_brand')->where('brand_name',"$brand_name")->first();

        $items = Item::orderBy('item_name','asc')->where('brand_id', $brand->brand_id)->paginate(16);

        $sidebar_sliders = \Cache::remember('sidebar_sliders', 2*60, function() {
            return Slider::orderBy('slider_order','asc')->where('active', 1)->where('type', 'sidebar')->get();
        });

        return view('frontend.product.brand.show', compact('brand','items','sidebar_sliders'));
    }


}
