<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $items = DB::table('tbl_item')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_sub_category','tbl_sub_category.id','=','tbl_item.sub_cata_id')
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->leftJoin('tbl_unit','tbl_unit.id','=','tbl_item.unit_id')
        ->selectRaw('tbl_item.*,tbl_unit.name as unit,tbl_category.cata_name,tbl_brand.brand_name,tbl_sub_category.name as sub_cata_name')
        ->groupBy('tbl_item.item_name')
        ->orderBy('tbl_item.item_name', 'asc')
        ->get();
        return view('admin.item.index', compact('items'));
    }


    public function create()
    {
        $categories = Category::all()->sortBy('cata_name');
        $sub_categories = Subcategory::all()->sortBy('name');
        $sub_sub_categories = SubSubcategory::all()->sortBy('name');
        $brands = Brand::all()->sortBy('brand_id');
        $units = Unit::all()->sortBy('id');

        return view('admin.item.create', compact('categories','brands','sub_categories','sub_sub_categories','units'));
    }
   
    public function generateBatchCode($item_id){

        $item_id = Item::where('item_id', $item_id)->value('item_id');
        $current_month =  date('m');
        $current_year  =  date("y");
        $batch_count   =  Batch::where('item_id', $item_id)->count();
        if($batch_count >= 0){
            $batch_count++;
        }else{
            $batch_count = 1;
        }
        $generateBatchCode = $current_year.$current_month.'-'.$item_id.'-'.$batch_count;
        return $generateBatchCode;

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'item_name' => 'unique:tbl_item',
                ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
            /*
            // JSON Response
            return response()->json(['message' => "Invalid input",'class' => 'danger']);
            */
        }

        $item_info = DB::table('tbl_item')
        ->orderBy('tbl_item.item_code', 'dsc')
        ->limit(1)
        ->first();

        if(!isset($item_info)){
            $item_code = 1000;
        }
        else{
            $item_code = $item_info->item_code;
        }

        $item = new Item();  
        //Handling Item image
        if($request->file('item_image') != ""){
            $item_image = $request->file('item_image');
            $name = time().$item_image->getClientOriginalName();
            $uploadPath = 'public/admin/images/item_images/';
            $item_image->move($uploadPath,$name);
            $item_imageUrl = $uploadPath.$name;
        }
        else{
            $item_imageUrl = "";
        }      
        $item->item_name = $request->item_name;
        $item->item_code = $item_code;
        $item->cata_id = $request->cata_id;
        $item->sub_cata_id = $request->sub_cata_id;
        $item->sub_sub_cata_id = $request->sub_sub_cata_id;
        $item->brand_id = $request->brand_id;
        $item->description = $request->description;
        $item->specification = $request->specification;
        $item->item_image = $item_imageUrl;
        $item->mrp = $request->mrp;
        $item->discounted_price = $request->input('discounted_price');
        $item->reorder_level_min = $request->reorder_level_min;
        $item->created_at = $request->date;
        $item->save();

        //opening balance
        if($request->opening_qty>0){
            $stock_master = New StockMaster;
            $stock_master->date = $request->date;
            $stock_master->description = "$request->code";
            $stock_master->type = "Opening Balance";
            $stock_master->ref_id = $item->item_id;
            $stock_master->user_id = Auth::user()->id;

            if($stock_master->save()){
                $auto_generated_batch_code = $this->generateBatchCode($item->item_id);

                $batch = new Batch;
                $batch->code = $auto_generated_batch_code;
                $batch->item_id = $item->item_id;
                $batch->purchase_rate = $request->purchase_rate;
                $batch->save();

                $stock_details = New Stock;
                $stock_details->stock_master_id = $stock_master->id;
                $stock_details->item_id = $item->item_id;
                $stock_details->batch_id = $batch->id;
                $stock_details->particulars = "Opening Balance";
                $stock_details->stock_in = $request->input('opening_qty')? : 0;
                $stock_details->save();
            }
        }
        
        if ($request->get('action') == 'save') {
            return redirect('admin/item/create')->with('success','New Item Added');
        } elseif ($request->get('action') == 'save_and_close') {
            return redirect('admin/item')->with('success','New Item Added');
        }
            
        

    }

    public function show($id)
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

        return view('admin.item.show', compact('item'));
    }


    public function edit($id)
    {
        $item = DB::table('tbl_item')
        ->leftJoin('tbl_stock_master','tbl_stock_master.ref_id','=','tbl_item.item_id')
        ->leftJoin('tbl_stock','tbl_stock.item_id','=','tbl_item.item_id')
        ->leftJoin('tbl_batch','tbl_batch.id','=','tbl_stock.batch_id')
        ->selectRaw('tbl_item.*,tbl_stock_master.date as date,tbl_stock.*,tbl_stock.batch_id as batch_id')
        ->where('tbl_item.item_id', '=', $id)
        ->where('tbl_stock.particulars', '=', 'Opening Balance')
        ->first();
        
        if(!isset($item)){

            $item = DB::table('tbl_item')
            ->where('tbl_item.item_id', '=', $id)
            ->first();
        }

        $categories = Category::all();
        $sub_categories = Subcategory::all();
        $sub_sub_categories = SubSubcategory::all()->sortBy('name');
        $brands = Brand::all();
        $units = Unit::all()->sortBy('name');

        $batches = DB::table('tbl_batch')
        ->join('tbl_item','tbl_item.item_id','=','tbl_batch.item_id')
        ->where('tbl_batch.item_id',$id)
        ->get();

        

        return view('admin.item.edit',compact('item','categories','brands','sub_categories','sub_sub_categories','batches','units'))->with('id',$id);
    }

    public function update(Request $request, $id)
    {

        $items = Item::find($id);
        //Handling supplier PHOTO
        if($request->file('item_image')!=''){
            $item_image = $request->file('item_image');
            $name = time().$item_image->getClientOriginalName();
            $uploadPath = 'public/admin/images/item_images/';
            $item_image->move($uploadPath,$name);
            $item_imageUrl = $uploadPath.$name;
            $items->item_image = $item_imageUrl;
        }
        else{
            $items->item_image = $request->input('item_image_old');
        }
        $items->item_name = $request->input('item_name'); 
        $items->item_code = $request->input('item_code'); 
        $items->cata_id = $request->input('cata_id');
        $items->sub_cata_id = $request->input('sub_cata_id');
        $items->sub_sub_cata_id = $request->sub_sub_cata_id;
        $items->brand_id = $request->input('brand_id');
        $items->description = $request->input('description');
        $items->specification = $request->specification;
        $items->mrp = $request->input('mrp');
        $items->discounted_price = $request->input('discounted_price');
        $items->reorder_level_min = $request->reorder_level_min;
        $items->is_active = $request->input('is_active');
        $items->update();

        //update

        $item = DB::table('tbl_item')
        ->leftJoin('tbl_stock_master','tbl_stock_master.ref_id','=','tbl_item.item_id')
        ->leftJoin('tbl_stock','tbl_stock.item_id','=','tbl_item.item_id')
        ->selectRaw('tbl_item.*,tbl_stock_master.id as stock_master_id,tbl_stock.stock_id')
        ->where('tbl_item.item_id', '=', $id)
        ->where('tbl_stock.particulars', '=', 'Opening Balance')
        ->first();

        //return dd(isset($item->item_id));

        if(isset($item->item_id)){
            //opening balance update
            $obj = StockMaster::find($item->stock_master_id);
            $obj->date = $request->date;
            $obj->update();

            $details = Stock::find($item->stock_id);
            $details->batch_id = $request->batch_id;
            $details->stock_in = $request->opening_qty;
            $details->update();
        }
        else{
            $item = DB::table('tbl_item')
            ->where('tbl_item.item_id', '=', $id)
            ->first();
            //opening balance insert
            if($request->opening_qty>0){
                $stock_master = New StockMaster;
                $stock_master->date = $request->date;
                $stock_master->description = "$request->code";
                $stock_master->type = "Opening Balance";
                $stock_master->ref_id = $item->item_id;
                $stock_master->user_id = Auth::user()->id;

                if($stock_master->save()){

                    $auto_generated_batch_code = $this->generateBatchCode($item->item_id);
                    $batch = new Batch;
                    $batch->code = $auto_generated_batch_code;
                    $batch->item_id = $item->item_id;
                    $batch->purchase_rate = $request->purchase_rate;
                    $batch->save();

                    $stock_details = New Stock;
                    $stock_details->stock_master_id = $stock_master->id;
                    $stock_details->item_id = $item->item_id;
                    $stock_details->batch_id = $batch->id;
                    $stock_details->particulars = "Opening Balance";
                    $stock_details->stock_in = $request->input('opening_qty')? : 0;
                    $stock_details->save();
                }
            }
        }


        return redirect('admin/item')->with('update','Item Info Updated');
    }

    public function destroy($id)
    {
        $salesDetail = DB::table('tbl_sales_details')
        ->where('item_id',$id)
        ->count();
        
        $purchaseDetail = DB::table('tbl_purchase_details')
        ->where('item_id',$id)
        ->count();
        
        if($salesDetail==0 && $purchaseDetail == 0){
            
            DB::table('tbl_batch')->where('item_id',$id)->delete();

            DB::table('tbl_stock_master')->where('ref_id',$id)->where('type','Opening Balance')->delete();

            DB::table('tbl_stock')->where('item_id',$id)->delete();

            $items = Item::find($id);
    
            $items->delete();
            
            return redirect('admin/item')->with('success','Item Deleted');
        }
        else{
            return redirect('admin/item')->with('delete','Item is in use!');
        }
    }

    public function inventory($item_id)
    {
        $singleItem = Item::find($item_id);
        $item_name = $singleItem->item_name;
        $opening_stock_qty = $singleItem->opening_stock_qty;

        $inventory_history_in = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_purchase_details','tbl_purchase_details.purchase_details_id','=','tbl_stock.purchase_details_id')
        ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_purchase_details.purchase_price')
        ->where('tbl_stock.item_id','=',$item_id)
        ->get();        
        //var_dump($current_stocks);
        $inventory_history_out = DB::table('tbl_stock')
        ->join('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
        ->join('tbl_sales_details','tbl_sales_details.sales_details_id','=','tbl_stock.sales_details_id')
        ->selectRaw('tbl_item.item_name,tbl_stock.*,tbl_sales_details.sales_price')
        ->where('tbl_stock.item_id','=',$item_id)
        ->get(); 
                
        return view('admin.item.inventory' , compact('item_name','opening_stock_qty','inventory_history_in','inventory_history_out'));
    }

    public function summernoteImageUpload(Request $request){

       $image       = $request->file('custom_image');
       $image_name  = time().$image->getClientOriginalName();
       $upload_path = 'public/admin/images/item_images/';
       $image->move($upload_path,$image_name); 
       $image_url = $upload_path.$image_name; 
       return response()->json(['image_url'=>$image_url]) ;

    }
}
