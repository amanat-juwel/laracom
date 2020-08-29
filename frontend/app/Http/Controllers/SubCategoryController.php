<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use DB;
use Validator;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {	
    	$categories = Category::all()->sortBy('cata_name');
        $sub_category = DB::table('tbl_sub_category')
        ->join('tbl_category','tbl_category.cata_id','tbl_sub_category.cata_id')
        ->orderBy('name', 'asc')
        ->get();
        
        return view('admin.sub-category.index', compact('sub_category','categories'));
    }

    public function showSubCat($cata_id){

        return json_encode(Subcategory::where('cata_id',$cata_id)->orderBy('name','ASC')->get());     
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'name' => 'unique:tbl_sub_category',
                ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sc = new Subcategory;
        $sc->name = $request->name;
        $sc->cata_id = $request->cata_id;
        $sc->description = $request->description;
        $sc->save();

        return redirect()->back()->with('success','Data Inserted');
    }

    public function edit(Request $request)
    {
        if($request->ajax())
        {
            $sub_categories = Subcategory::find($request->id);
            return Response($sub_categories);
        }
    }

    public function update(Request $request)
    {

        $sc = Subcategory::find($request->id);
        $sc->name = $request->input('name');
        $sc->cata_id = $request->input('cata_id');
        $sc->description = $request->input('description');
        $sc->update();
        return redirect()->back()->with('success','Data Updated');
        
    }


    public function delete($id)
    {

        $sub_cata_in_use = DB::table('tbl_item')
        ->where('sub_cata_id',$id)
        ->count();
        
        if($sub_cata_in_use == 0){
        
            $sc = Subcategory::find($id);
            $sc->delete();
            return redirect()->back()->with('success','Data Deleted');
        }
        else{
            return redirect()->back()->with('delete','Data is in use');
        }

    }
}
