<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CategoryRequest;

use App\Category;

use DB;

use Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $category = DB::table('tbl_category')
        ->orderBy('cata_name', 'asc')
        ->get();
        
        return view('admin.category.index', compact('category'));
        //return view('admin.category.index')->with('Countries', $Countries->getData()->Data);
        //$data = $request->json()->all();
    }


    public function create()
    {
        return view('admin.category.create');
    }


    // public function store(CategoryRequest $request)
    // {

    //     $category = Category::create($request->all());

    //     return redirect('admin/category')->with('success','New Category Added');
    // }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
                    'cata_name' => 'unique:tbl_category',
                ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::create($request->all());
        return redirect()->back()->with('success','New Category Added');
    }

    public function show($id)
    {
        $category = DB::table('tbl_category')
        ->orderBy('cata_id', 'desc')
        ->get();

        return view('admin.category.index', compact('category'));
    }


    // public function edit($id)
    // {
    //     $categories = Category::find($id);

    //     return view('admin.category.edit',compact('categories'))->with('id',$id);
    // }

    public function edit(Request $request)
    {
        if($request->ajax())
        {
            $categories = Category::find($request->id);
            return Response($categories);
        }
    }


    public function update(Request $request)
    {
        if($request->ajax())
        {

            $categories = Category::find($request->cata_id);
            $categories->cata_name = $request->input('cata_name');
            $categories->cata_details = $request->input('cata_details');
            $categories->update();
            return Response($categories);
        }
    }


    public function destroy($id)
    {

        $cata_in_use = DB::table('tbl_item')
        ->where('cata_id',$id)
        ->count();
        
        if($cata_in_use == 0){
        
            $categories = Category::find($id);
            $categories->delete();

            return redirect('admin/category')->with('success','Category Deleted');
        }
        else{
            return redirect('admin/category')->with('delete','Category is in use');
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
