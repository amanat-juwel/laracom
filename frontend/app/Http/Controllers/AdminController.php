<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\Item;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $new_order = Order::where('status','Processing')->count();
        $item_count = Item::where('is_active','1')->count();
        $stock_and_store_value =  app('App\Http\Controllers\BalanceSheetController')->stockStore();

        return view('admin.dashboard', compact('new_order','item_count','stock_and_store_value'));
    }
}
