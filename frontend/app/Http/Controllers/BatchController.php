<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Batch;
use App\Item;
use DB;
use Auth;
class batchController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function create(){

        $items = Item::all()->sortBy('item_name');
        return view('admin.batch.create',compact('items'));
    }

    public function index(){

        $batches = DB::table('tbl_batch')
        ->join('tbl_item','tbl_item.item_id','=','tbl_batch.item_id')
        ->select('tbl_batch.*','tbl_item.item_name',
        DB::raw('(SELECT SUM(tbl_stock.stock_in-tbl_stock.stock_out) FROM tbl_stock WHERE tbl_stock.batch_id = tbl_batch.id
         GROUP BY tbl_stock.batch_id) as stock'))
        ->get();
        $items = Item::all()->sortBy('item_name');
        return view('admin.batch.index',compact('batches','items'));
    }

    public function store(Request $request)
    {   
        $obj = new Batch;
        $obj->code = $request->code;
        $obj->item_id = $request->item_id;
        $obj->purchase_rate = $request->purchase_rate;
        $obj->save();
        
        return redirect()->back()->with('success','Batch Added');
    }

    public function edit(Request $request, $id)
    {
        $batch = Batch::find($id);
        $items = Item::all()->sortBy('item_name');

        return view('admin.batch.edit',compact('batch','items'));
    }

    public function update(Request $request)
    {

        $batch = Batch::find($request->id);
        $batch->code = $request->code;
        $batch->item_id = $request->item_id;
        $batch->purchase_rate = $request->purchase_rate;
        $batch->update();
        
        return redirect('admin/batch')->with('success','Batch Updated');
        
    }

    public function destroy(Request $request)
    {
        $batch = Batch::find($request->id);
        $batch->delete();
        return redirect()->back()->with('success','Batch Deleted');
    }
}
