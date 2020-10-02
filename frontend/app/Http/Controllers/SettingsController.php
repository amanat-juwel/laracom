<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use App\Setting;

use Carbon\Carbon;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function tableName(){
        $obj = new Setting;
        return $table_name = $obj->getTable();

    }

    public function index()
    {
        $settings = DB::table('settings')->where('setting_id','=','1')->first();

        /* START GET USER PERMISSION  */
        // $permission = new \App\Permission;
        // $user_permissions = $permission->getPermission($this->tableName());
        // if(!in_array("browse", $user_permissions)){
        //     return view('errors.permission');
        // }
        /* END GET USER PERMISSION  */

        return view('admin.settings.index',compact('settings','user_permissions'));
    }

    public function update(Request $request)
    {

        //Handling Logo
        if($request->file('logo_new')!=''){
            $logo = $request->file('logo_new');
            $name = time().$logo->getClientOriginalName();
            $uploadPath = 'public/admin/images/';
            $logo->move($uploadPath,$name);
            $logo = $uploadPath.$name;
        }
        else{
            $logo="$request->logo_old";
        }

        //Handling system logo
        if($request->file('system_logo_new')!=''){
            $system_logo = $request->file('system_logo_new');
            $name = time().$system_logo->getClientOriginalName();
            $uploadPath = 'public/admin/images/';
            $system_logo->move($uploadPath,$name);
            $system_logo = $uploadPath.$name;
        }
        else{
            $system_logo="$request->system_logo_old";
        }

        //Handling system logo
        if($request->file('favicon_new')!=''){
            $favicon = $request->file('favicon_new');
            $name = time().$favicon->getClientOriginalName();
            $uploadPath = 'public/admin/images/';
            $favicon->move($uploadPath,$name);
            $favicon = $uploadPath.$name;
        }
        else{
            $favicon="$request->favicon_old";
        }

        DB::table('settings')
            ->where('setting_id', 1)
            ->update(['company_name' => "$request->company_name",
                      'address' => "$request->address",
                      'phone' => "$request->phone",
                      'mobile' => "$request->mobile",
                      'email' => "$request->email",
                      'fax' => "$request->fax",
                      'currency' => "$request->currency",
                      'logo' => "$logo",
                      'system_logo' => "$system_logo",
                      'favicon' => "$favicon",
                      'full_sidebar' => "$request->full_sidebar",
                      'theme' => "$request->theme",
                      'website' => "$request->website",
                      'sms_sender'=>"$request->sms_sender",
                      'sms_api_key'=>"$request->sms_api_key"]);
                      
        \Cache::forget('globalSettings');

        return redirect('/admin/settings')->with('success', 'Settings Updated Successfully');

    }

    public function homepageBannerIndex()
    {
        $settings = DB::table('settings')->where('setting_id','=','1')->first();

        return view('admin.settings.web-homepage.index',compact('settings'));
    }

    public function homepageBannerUpdate(Request $request)
    {
        
        $settings = DB::table('settings')->where('setting_id','=','1')->first();

        //Handling image
        if($request->file('banner_1')!=''){
            $banner = $request->file('banner_1');
            $name = time().$banner->getClientOriginalName();
            $uploadPath = 'public/frontend/images/banner/';
            $banner->move($uploadPath,$name);
            $banner_1 = $uploadPath.$name;
        }
        else{
            $banner_1 = $settings->banner_1;
        }

        //Handling image
        if($request->file('banner_2')!=''){
            $banner = $request->file('banner_2');
            $name = time().$banner->getClientOriginalName();
            $uploadPath = 'public/frontend/images/banner/';
            $banner->move($uploadPath,$name);
            $banner_2 = $uploadPath.$name;
        }
        else{
            $banner_1 = $settings->banner_1;
        }

        //Handling image
        if($request->file('banner_3')!=''){
            $banner = $request->file('banner_3');
            $name = time().$banner->getClientOriginalName();
            $uploadPath = 'public/frontend/images/banner/';
            $banner->move($uploadPath,$name);
            $banner_3 = $uploadPath.$name;
        }
        else{
            $banner_3 = $settings->banner_3;
        }

        DB::table('settings')
            ->where('setting_id', 1)
            ->update([
                      'banner_1' => $banner_1,
                      'banner_2'=> $banner_2,
                      'banner_3'=> $banner_3
                      ]);
                      
        \Cache::forget('globalSettings');

        return redirect()->back()->with('success', 'Settings Updated Successfully');

    }

    public function clearCache() {
        
        \Cache::forget('globalSettings');
        \Cache::forget('sliders');
        \Cache::forget('pages_front_view');
        \Cache::forget('brands_front_view');
        \Cache::forget('frontend_payment_methods');

        echo 'Cache Cleared';
    }

}
