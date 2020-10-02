<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests;

use DB;
use App\Slider;
use App\Category;
use App\Subcategory;
use App\Item;

class WebController extends Controller
{

    public function index()
    {      

        $sliders = \Cache::remember('sliders', 2*60, function() {
            return Slider::orderBy('slider_order','asc')->where('active', 1)->where('type', 'main')->get();
        });

        $newly_arrived_items = \Cache::remember('newly_arrived_items', 2*60, function() {
            return Item::orderBy('item_id','dsc')->limit(6)->get();
        });
        
        // Home product category block 1 start
        $home_view_block_1_category_id = 2;
        $home_view_block_1_category = $this->blockCategory($home_view_block_1_category_id);
        $home_view_block_1_subcategories = $this->blockSubCategory($home_view_block_1_category_id);
        $sub_sub_block_1_category_items = $this->blockItem($home_view_block_1_category_id);
        // Home product category block 1 end

        // Home product category block 1 start
        $home_view_block_2_category_id = 1;
        $home_view_block_2_category = null; //$this->blockCategory($home_view_block_2_category_id);
        $sub_sub_block_2_category_items = null; //$this->blockItem($home_view_block_2_category_id);
        // Home product category block 1 end
   		
   		//return dd($sub_sub_block_2_category_items);
        return view('frontend.home',compact(
        	'sliders',
        	'newly_arrived_items',
        	'home_view_block_1_category',
        	'home_view_block_1_subcategories',
        	'sub_sub_block_1_category_items',
        	'home_view_block_2_category',
        	'sub_sub_block_2_category_items'
        ));
    }

    public function blockCategory($category_id){
    	$data = Category::where('cata_id',$category_id)->orderBy('cata_name','asc')->first();
    	return $data;
    }

    public function blockSubCategory($category_id){
    	$data = Subcategory::where('cata_id',$category_id)->orderBy('name','asc')->get();
    	return $data;
    }

    public function blockItem($category_id){
    	$data = Item::where('cata_id',$category_id)->orderBy('item_name','asc')->get();
    	return $data;
    }

}
