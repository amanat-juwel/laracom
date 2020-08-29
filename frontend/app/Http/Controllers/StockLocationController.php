<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StockLocationRequest;
use App\StockLocation;
use App\Stock;
use App\StockTransfer;
use App\Item;
use DB;
use Auth;

class StockLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $stock_location = DB::table('tbl_stock_location')
        ->orderBy('stock_location_id', 'desc')
        ->get();
        return view('stock_location.index', compact('stock_location'));
    }

 
    public function create()
    {
        return view('stock_location.create');
    }

    public function store(StockLocationRequest $request)
    {
        $stock_location = StockLocation::create($request->all());
        return redirect()->route('stock_location.index')->with('success','New Stock Location Added');
    }

    public function show($id)
    {
        $stock_location = DB::table('tbl_stock_location')
        ->orderBy('stock_location_id', 'desc')
        ->get();
        return view('stock_location.index', compact('stock_location'));
    }

    public function edit($id)
    {
        $stock_locations = StockLocation::find($id);
        return view('stock_location.edit',compact('stock_locations'))->with('id',$id);
    }

    
    public function update(StockLocationRequest $request, $id)
    {
        $stock_locations = StockLocation::find($id);
        
        $stock_locations->stock_location_name = $request->input('stock_location_name');
        $stock_locations->code = $request->input('code');
        $stock_locations->address = $request->input('address');

        $stock_locations->update();

        return redirect()->route('stock_location.index')->with('update','Stock Location Updated');
    }

   
    public function destroy($id)
    {
        $in_use = DB::table('tbl_stock')
        ->where('stock_location_id',$id)
        ->count();
        
        if($in_use == 0){
          $stock_locations = StockLocation::find($id);
          $stock_locations->delete();
          return redirect()->route('stock_location.index')->with('delete','Stock Location Deleted');
        }
        else{
          return redirect()->back()->with('delete','Stock Location is in use, you can not delete this at this moment');
        }


        
    }

    public function movement(){

        $stock_locations = StockLocation::all();
        $items = Item::all();
        $stock_transfers = StockTransfer::all();

        return view('stock_location.movement',['stock_locations'=>$stock_locations,'items'=>$items,'stock_transfers'=>$stock_transfers]);
    }

    public function storeMovement(Request $request){

        $item_id = $request->item_id;
        $quantity = $request->quantity;
        $from = $request->from;
        $to = $request->to;
        $date = $request->date;

        $stock_out = new Stock;
        $stock_out->stock_location_id = $from;
        $stock_out->item_id = $item_id;
        $stock_out->stock_out = $quantity;
        $stock_out->stock_in = 0;
        $stock_out->stock_change_date = $date;
        $stock_out->save();

        $stock_in = new Stock;
        $stock_in->stock_location_id = $to;
        $stock_in->item_id = $item_id;
        $stock_in->stock_out = 0;
        $stock_in->stock_in = $quantity;
        $stock_in->stock_change_date = $date;
        $stock_in->save();

        $stock_transfer = new StockTransfer;
        $stock_transfer->from_stock_id = $stock_out->stock_id;
        $stock_transfer->to_stock_id = $stock_in->stock_id;
        $stock_transfer->from_stock_location_id = $from;
        $stock_transfer->to_stock_location_id = $to;
        $stock_transfer->item_id = $item_id;
        $stock_transfer->quantity = $quantity;
        $stock_transfer->date = $date;
        $stock_transfer->user_id = Auth::user()->id;;
        $stock_transfer->save();


        return redirect()->back()->with('success','Stock Movement Successful');

    }

    public function getAvailableQty($item_id,$stock_location_id){

        $available_quantity = DB::table('tbl_stock')
        ->selectRaw('SUM(stock_in) as stock_in,SUM(stock_out) as stock_out')
        ->where('stock_location_id',$stock_location_id)
        ->where('item_id',$item_id)
        ->first();

        return response()->json([
                'qty' => $available_quantity->stock_in - $available_quantity->stock_out,
            ]);
    }

    public function destroyTransfer($id){

        $stock_transfer = StockTransfer::find($id);
        
        $stock_in = Stock::find($stock_transfer->from_stock_id);
        $stock_in->delete();

        $stock_out = Stock::find($stock_transfer->to_stock_id);
        $stock_out->delete();

        $stock_transfer->delete();

        return redirect()->back()->with('success','Stock Movement Deleted Successful');

    }

}
