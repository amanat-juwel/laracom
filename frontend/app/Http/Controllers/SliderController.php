<?php

namespace App\Http\Controllers;
use App\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::orderBy('slider_order','asc')->get();

        return view('admin.slider.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   

        return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //start image upload
        if($request->file('image') != ""){
            $_IMAGE = $request->file('image');
            $name = time().$_IMAGE->getClientOriginalName();
            $uploadPath = 'public/frontend/images/main-slider/';
            $_IMAGE->move($uploadPath,$name);
            $_imageUrl = $uploadPath.$name;
        }
        else{
            $_imageUrl = "";
        }
        //end image upload

        $slider = new Slider;
        $slider->image = $_imageUrl;
        $slider->type = $request->type;
        $slider->title = $request->title;
        $slider->sub_title = $request->sub_title;
        $slider->redirect_link = $request->redirect_link;
        $slider->active = 1;
        $slider->slider_order = 1;
        $slider->save();

        return redirect('/admin/sliders')->with('success','Data Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::find($id);

        return view('admin.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {   
        $slider = Slider::find($id);
        //start image upload
        if($request->file('image') != ""){
            $_IMAGE = $request->file('image');
            $name = time().$_IMAGE->getClientOriginalName();
            $uploadPath = 'public/frontend/images/main-slider/';
            $_IMAGE->move($uploadPath,$name);
            $_imageUrl = $uploadPath.$name;

            //delete previous image
            if(!empty($slider->image)){
                try{
                    unlink("$slider->image");
                }
                catch(\Exception $e){

                }
                finally{
                  $flag = true;  
                }
            }
            //store updated image
            $slider->image = $_imageUrl;

        }
        //end image upload
        $slider->type = $request->type;
        $slider->title = $request->title;
        $slider->sub_title = $request->sub_title;
        $slider->redirect_link = $request->redirect_link;
        $slider->active = $request->active;
        $slider->slider_order = $request->slider_order;
        $slider->save();

        return redirect('/admin/sliders')->with('success',' Data Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $slider =Slider::find($id);
        unlink("$slider->image");
        $slider->delete();
        
        return redirect()->back()->with('success',' Data Deleted');

    }
}
