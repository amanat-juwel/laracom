<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerCategory;
use DB;
use Validator;

class CustomerCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories = CustomerCategory::orderBy('cat_name')->get();
        return view('admin.customer_category.index', compact('categories'));
        //return view('category.index')->with('Countries', $Countries->getData()->Data);
        //$data = $request->json()->all();
    }


    public function create()
    {
        return view('admin.customer_category.create');
    }


    // public function store(CategoryRequest $request)
    // {

    //     $category = Category::create($request->all());

    //     return redirect()->route('category.index')->with('success','New Category Added');
    // }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
                    'cat_name' => 'unique:tbl_customer_category',
                ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = new CustomerCategory;
        $category->cat_name = $request->cat_name;
        //$category->credit_limit = $request->input('credit_limit') : 100000;
        $category->save();

        return redirect()->back()->with('success','New Category Added');
    }

    public function show($id)
    {
        $category = DB::table('tbl_category')
        ->orderBy('cata_id', 'desc')
        ->get();

        return view('category.index', compact('category'));
    }


    // public function edit($id)
    // {
    //     $categories = Category::find($id);

    //     return view('category.edit',compact('categories'))->with('id',$id);
    // }

    public function edit(Request $request)
    {
        if($request->ajax())
        {
            $category = CustomerCategory::find($request->id);
            return Response($category);
        }
    }


    public function update(Request $request)
    {
       

        $category = CustomerCategory::find($request->id);
        $category->cat_name = $request->input('cat_name');
        //$category->credit_limit = $request->input('credit_limit') : 100000;
        $category->update();
        return redirect()->back()->with('success','Category Updated');
        
    }


    public function destroy($id)
    {

        $cata_in_use = DB::table('tbl_customer')
        ->where('category',$id)
        ->count();
        
        if($cata_in_use == 0){
        
            $category = CustomerCategory::find($id);
            $category->delete();

            return redirect()->back()->with('success','Category Deleted');
        }
        else{
            return redirect()->back()->with('delete','Category is in use');
        }

    }

    // public function destroy(Request $request)
    // {
    //     if($request->ajax())
    //     {
    //         $categories = Category::find($request->id);
    //         $categories->delete();
    //         return Response()->json(['sms'=>'Successfully Deleted']);
    //     }
    // }


}
