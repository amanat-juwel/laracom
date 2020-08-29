<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Subcategory;
use App\SubSubcategory;
use DB;
use Validator;

class SubSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {	
    	$sub_categories = Subcategory::all()->sortBy('name');
        $sub_sub_categories = DB::table('tbl_sub_sub_category')
        ->leftJoin('tbl_sub_category','tbl_sub_category.id','tbl_sub_sub_category.sub_cata_id')
        ->selectRaw('tbl_sub_sub_category.*, tbl_sub_category.name as sub_cata_name')
        ->orderBy('tbl_sub_sub_category.name', 'asc')
        ->get();
        
        return view('admin.sub-sub-category.index', compact('sub_sub_categories','sub_categories'));
    }

    public function showSubCat($sub_cata_id){

        return json_encode(SubSubcategory::where('sub_cata_id',$sub_cata_id)->orderBy('name','ASC')->get());     
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'name' => 'unique:tbl_sub_sub_category',
                ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sc = new SubSubcategory;
        $sc->name = $request->name;
        $sc->sub_cata_id = $request->sub_cata_id;
        $sc->save();

        return redirect()->back()->with('success','Data Inserted');
    }

    public function edit(Request $request)
    {
        if($request->ajax())
        {
            $data = SubSubcategory::find($request->id);
            return Response($data);
        }
    }

    public function update(Request $request)
    {

        $sc = SubSubcategory::find($request->id);
        $sc->name = $request->name;
        $sc->sub_cata_id = $request->sub_cata_id;
        $sc->update();
        return redirect()->back()->with('success','Data Updated');
        
    }


    public function delete($id)
    {

        $sub_cata_in_use = DB::table('tbl_item')
        ->where('sub_cata_id',$id)
        ->count();
        
        if($sub_cata_in_use == 0){
        
            $sc = SubSubcategory::find($id);
            $sc->delete();
            return redirect()->back()->with('success','Data Deleted');
        }
        else{
            return redirect()->back()->with('delete','Data is in use');
        }

    }
}
