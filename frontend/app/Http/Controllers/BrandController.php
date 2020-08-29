<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Brand;
use DB;
use Validator;
use InterventionImage;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index()
    {
        $brand = DB::table('tbl_brand')
        ->orderBy('brand_name', 'asc')
        ->get();
        
        return view('admin.brand.index', compact('brand'));
    }


    public function create()
    {
        return view('admin.brand.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'brand_name' => 'unique:tbl_brand',
                ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        /*INTERVENTION IMAGE START*/
        if($request->file('brand_image') != ""){
            $_IMAGE = $request->file('brand_image');
            $imageName = time().$_IMAGE->getClientOriginalName();
            $uploadPath = 'public/admin/images/brand/';

            $image_size = InterventionImage::make($_IMAGE->getRealPath())->filesize();
            $img_width = InterventionImage::make($_IMAGE->getRealPath())->width();
            $img_height = InterventionImage::make($_IMAGE->getRealPath())->height();

            #START OPTIMIZE
            //Data ranging from 0 (poor quality, small file) to 100 (best quality, big file).
            //check if 2mb
            ($image_size > 2000000)? $compression_rate = 80 : $compression_rate = 60;
            $thumb_img = InterventionImage::make($_IMAGE->getRealPath())->encode('jpg', $compression_rate);
            
            #START RESIZE  
            // resize the image to a width of 700 and constrain aspect ratio (auto height)
            if($img_width > 60 || $img_height > 60){
                $thumb_img = InterventionImage::make($_IMAGE->getRealPath())->resize(60,60);
            }
            #SAVE IMAGE
            $thumb_img->save($uploadPath.'/'.$imageName,$compression_rate);
            $_imageUrl = $uploadPath.$imageName;
        }
        /*INTERVENTION IMAGE END*/
        (!isset($_imageUrl))? $_imageUrl = 'public/admin/images/brand/default.png' : null;

        $brand = new Brand;
        $brand->brand_name = $request->brand_name;
        $brand->brand_image = $_imageUrl;
        $brand->brand_details = $request->brand_details;
        $brand->save();

        return redirect('admin/brand')->with('success','New Brand Added');
    }

    public function show($id)
    {
        $brand = DB::table('tbl_brand')
        ->orderBy('brand_id', 'desc')
        ->get();

        return view('admin.brand.index', compact('brand'));
    }


    public function edit($id)
    {
        $brands = Brand::find($id);

        return view('admin.brand.edit',compact('brands'))->with('id',$id);
    }


    public function update(Request $request, $id)
    {   
        $brands = Brand::find($id);
        /*INTERVENTION IMAGE START*/
        if($request->file('brand_image') != ""){
            $_IMAGE = $request->file('brand_image');
            $imageName = time().$_IMAGE->getClientOriginalName();
            $uploadPath = 'public/admin/images/brand/';

            $image_size = InterventionImage::make($_IMAGE->getRealPath())->filesize();
            $img_width = InterventionImage::make($_IMAGE->getRealPath())->width();
            $img_height = InterventionImage::make($_IMAGE->getRealPath())->height();

            #START OPTIMIZE
            //Data ranging from 0 (poor quality, small file) to 100 (best quality, big file).
            //check if 2mb
            ($image_size > 2000000)? $compression_rate = 80 : $compression_rate = 60;
            $thumb_img = InterventionImage::make($_IMAGE->getRealPath())->encode('jpg', $compression_rate);
            
            #START RESIZE  
            // resize the image to a width of 700 and constrain aspect ratio (auto height)
            if($img_width > 60 || $img_height > 60){
                $thumb_img = InterventionImage::make($_IMAGE->getRealPath())->resize(60,60);
            }
            #SAVE IMAGE
            $thumb_img->save($uploadPath.'/'.$imageName,$compression_rate);
            $_imageUrl = $uploadPath.$imageName;
            $brands->brand_image = $_imageUrl;
        }
        /*INTERVENTION IMAGE END*/

        
        $brands->brand_name = $request->input('brand_name');
        $brands->brand_details = $request->input('brand_details');
        $brands->update();

        return redirect('admin/brand')->with('update','Brand Updated');
    }

    public function destroy($id)
    {

        $in_use = DB::table('tbl_item')
        ->where('brand_id',$id)
        ->count();
        
        if($in_use == 0){
        
            $brands = Brand::find($id);
            $brands->delete();

            return redirect()->back()->with('success','Data Deleted');
        }
        else{
            return redirect()->back()->with('delete','Data is in use');
        }

    }

  
}
