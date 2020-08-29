<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Unit;
use DB;
use Validator;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {	
    	$units = Unit::all()->sortBy('name');
        
        return view('admin.unit.index', compact('units'));
    }

    public function showSubCat($id){

        return json_encode(Unit::where('id',$id)->orderBy('name','ASC')->get());     
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'name' => 'unique:tbl_unit',
                ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $unit = new Unit;
        $unit->name = $request->name;
        $unit->save();

        return redirect()->back()->with('success','Data Inserted');
    }

    public function edit(Request $request)
    {
        if($request->ajax())
        {
            $unit = Unit::find($request->id);
            return Response($unit);
        }
    }

    public function update(Request $request)
    {

        $unit = Unit::find($request->id);
        $unit->name = $request->input('name');
        $unit->update();
        return redirect()->back()->with('success','Data Updated');
        
    }


    public function delete($id)
    {

        $in_use = DB::table('tbl_item')
        ->where('unit_id',$id)
        ->count();
        
        if($in_use == 0){
        
            $unit = Unit::find($id);
            $unit->delete();
            return redirect()->back()->with('success','Data Deleted');
        }
        else{
            return redirect()->back()->with('delete','Data is in use');
        }

    }
}
