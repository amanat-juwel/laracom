<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Page;


class PageController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function showWebsite($slug)
    {
        $page = Page::where('slug',"$slug")->firstOrFail();

        return view('frontend.page.index', compact('page'));
    }

    public function index()
    {
        $pages = Page::all();

        return view('admin.page.index', compact('pages'));
    }

    public function create()
    {

        return view('admin.page.create');
    }

    public function store(Request $request)
    {   

        //start image upload
        if($request->file('image') != ""){
            $_IMAGE = $request->file('image');
            $name = time().$_IMAGE->getClientOriginalName();
            $uploadPath = 'public/frontend/images/page/';
            $_IMAGE->move($uploadPath,$name);
            $_imageUrl = $uploadPath.$name;
        }
        else{
            $_imageUrl = "public/frontend/images/page/default.jpg";
        }
        //end image upload


        $page = New Page;
        $page->author_id = Auth::user()->id;
        $page->title = $request->title;
        $page->excerpt = $request->excerpt;
        $page->body = $request->body;
        $page->slug = $request->slug;
        $page->image = $_imageUrl;
        $page->status = $request->status;
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->created_at = date('Y-m-d H:i:s');
        $page->save();
        
        return redirect('/admin/pages')->with('success','Data Stored');
    }

    public function summernoteImageUpload(Request $request){

       $image       = $request->file('custom_image');
       $image_name  = time().$image->getClientOriginalName();
       $upload_path = 'public/frontend/images/page/';
       $image->move($upload_path,$image_name); 
       $image_url = $upload_path.$image_name; 
       return response()->json(['image_url'=>$image_url]) ;

    }

    public function show($id)
    {
        $page = Page::find($id);
        
        return view('admin.page.show', compact('page'));
    }

    

    public function edit($id)
    {   
        $page = Page::find($id);

        return view('admin.page.edit', compact('page'));
    }

    public function update(Request $request,$id)
    {   
        $page = Page::find($id);

        //start image upload
        if($request->file('image') != ""){
            $_IMAGE = $request->file('image');
            $name = time().$_IMAGE->getClientOriginalName();
            $uploadPath = 'public/frontend/images/page';
            $_IMAGE->move($uploadPath,$name);
            $_imageUrl = $uploadPath.$name;

            //delete previous image
            if(!empty($page->image)){
                //unlink("$page->image");
            }
            //store updated image
            $page->image = $_imageUrl;

        }
        //end image upload

        $page->title = $request->title;
        $page->excerpt = $request->excerpt;
        $page->body = $request->body;
        $page->slug = $request->slug;
        $page->status = $request->status;
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->updated_at = date('Y-m-d H:i:s');
        $page->save();
        
        return redirect('admin/pages')->with('success',' Data Updated');

    }

    public function destroy(Request $request,$id)
    {
        $page =Page::find($id);
        $page->delete();
        
        return redirect()->back()->with('success',' Data Deleted');

    }

    

}
