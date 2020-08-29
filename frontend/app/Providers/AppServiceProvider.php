<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use View;
use DB;
use App\Page;
use App\Category;
use App\Subcategory;
use App\SubSubcategory;
use App\Brand;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('*',function($view){
            $globalSettings = \Cache::remember('globalSettings', 24*60, function() {
                return DB::table('settings')->where('setting_id','=','1')->first();
            });
            $view->with('globalSettings',$globalSettings);
        });

        View::composer(['frontend.partials.footer'],function($view){
            $pages_front_view = \Cache::remember('pages_front_view', 24*60, function() {
                return Page::where('status',"PUBLISHED")->get();
            });
            $view->with('pages_front_view',$pages_front_view);
        });

        View::composer(['frontend.partials.main-menu','frontend.home'],function($view){
            $brands_front_view = \Cache::remember('brands_front_view', 24*60, function() {
                return Brand::where('is_active',1)->orderBy('brand_name')->get();
            });
            $view->with('brands_front_view',$brands_front_view);
        });


        
        View::composer('*',function($view){
            // $notification_items = DB::table('tbl_stock')
            // ->rightJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            // ->selectRaw('tbl_item.*,SUM(stock_in) - SUM(stock_out) as current_stock')
            // ->where('tbl_item.is_active',1)
            // ->groupBy('tbl_item.item_id')
            // ->orderBy('tbl_item.item_name', 'asc')
            // ->get();
            $notification_items = null;

            $view->with('notification_items',$notification_items);
        });

        View::composer('*',function($view){
            // $last_stock_out = DB::table('tbl_stock')
            // ->leftJoin('tbl_stock_master','tbl_stock_master.id', '=', 'tbl_stock.stock_master_id')
            // ->leftJoin('tbl_item','tbl_item.item_id', '=', 'tbl_stock.item_id')
            // ->leftJoin('tbl_unit','tbl_unit.id','=','tbl_item.unit_id')
            // ->select('tbl_item.item_id','tbl_unit.name as unit','tbl_item.item_name',DB::raw('(SUM(stock_in) - SUM(stock_out)) as current_stock'),DB::raw('max(tbl_stock_master.date) as stock_change_date'))
            // ->groupBy('item_id')
            // ->orderBy('stock_change_date')
            // ->havingRaw('(SUM(stock_in) - SUM(stock_out)) > ?', [0])
            // ->get();
            $last_stock_out = null;
            $view->with('last_stock_out',$last_stock_out);
        });

        View::composer(['frontend.partials.category-menu','frontend.partials.main-menu'],function($view){

            
            $all_categories_menu = \Cache::remember('all_categories_menu', 24*60, function() {
                $categories = Category::orderBy('cata_name','asc')->get();
                $sub_categories = Subcategory::orderBy('name','asc')->get();
                $sub_sub_categories = SubSubcategory::orderBy('name','asc')->get();

                $all_categories_menu = array();
                foreach ($categories as $cat) {
                    $sub_sub_array = array();
                    foreach ($sub_categories as $sub) {
                        if($sub->cata_id == $cat->cata_id){
                            $my_array_2 = array();
                            foreach ($sub_sub_categories as $sub_sub) {
                                if($sub_sub->sub_cata_id == $sub->id){
                                    array_push($my_array_2,array('sub_sub_name'=>"$sub_sub->name"));
                                }
                            }
                            $custom_array = array(array('sub_category'=>"$sub->name"),$my_array_2);  
                            array_push($sub_sub_array,$custom_array);
                        }
                    }
                    $sub_array = array(array('category'=>"$cat->cata_name"),$sub_sub_array);        
                    
                    array_push($all_categories_menu,$sub_array);
                }
                return $all_categories_menu;
            });
            $view->with('all_categories_menu',$all_categories_menu);
        });

        View::composer('*',function($view){
            $wishlist_count = 0;

            if(Auth::user()!==null){
                $wishlist_count = DB::table('wishlists')
                ->selectRaw('count(wishlists.id) as count')
                ->where('wishlists.user_id', Auth::user()->id)
                ->value('count');
            }

            $view->with('wishlist_count',$wishlist_count);
        });

        View::composer(['frontend.partials.footer','frontend.cart.checkout'],function($view){
            $frontend_payment_methods = \Cache::remember('frontend_payment_methods', 24*60, function() {
                return DB::table('frontend_payment_methods')->where('is_active','1')->get();
            });
            $view->with('frontend_payment_methods',$frontend_payment_methods);
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
