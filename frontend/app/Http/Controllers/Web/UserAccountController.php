<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use DB;
use Input;
use Validator;
use Auth;
use App\Order;
use App\OrderHistory;
use App\OrderDetails;
use App\Item;
use App\Customer;
use App\CustomerSystemUser;

class UserAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showAccount()
    {
        $customer_sys_user = CustomerSystemUser::where('user_id',Auth::user()->id)->first(); 
        $customer = Customer::find($customer_sys_user->customer_id);

        $billing_address = DB::table('tbl_customer_address_books')
        ->where('tbl_customer_address_books.id', $customer->billing_address_id)
        ->first();

        $delivery_address = DB::table('tbl_customer_address_books')
        ->where('tbl_customer_address_books.id', $customer->delivery_address_id)
        ->first();

        return view('frontend.my-account.index',compact('customer','billing_address','delivery_address'));
    }

    public function orderHistory()
    {   
        $customer = CustomerSystemUser::where('user_id',Auth::user()->id)->first(); 
        $customer_id = $customer->customer_id;

        $orders = DB::table('tbl_order_details')
        ->join('tbl_orders','tbl_orders.id','=','tbl_order_details.order_master_id')
        ->join('tbl_item','tbl_item.item_id','=','tbl_order_details.item_id')
        ->selectRaw('tbl_orders.id as order_id, tbl_orders.datetime, tbl_orders.status,tbl_order_details.*, tbl_item.* ')
        ->where('tbl_orders.customer_id', $customer_id)
        ->orderBy('tbl_order_details.id','dsc')
        ->get();

        return view('frontend.my-account.order-history', compact('orders'));
    }

    public function orderInformation($order_id)
    {   
        //validation
        $customer_sys_user = CustomerSystemUser::where('user_id',Auth::user()->id)->first(); 
        $customer = Customer::find($customer_sys_user->customer_id);
        $orderObj = Order::find($order_id);
        if($customer->customer_id != $orderObj->customer_id){
            return view('errors.404');
        }

        //passed validation
        $order_history = DB::table('tbl_orders_history')
        ->join('tbl_orders','tbl_orders.id','=','tbl_orders_history.order_id')
        ->where('tbl_orders.id', $order_id)
        ->get();

        $order_details = DB::table('tbl_order_details')
        ->join('tbl_orders','tbl_orders.id','=','tbl_order_details.order_master_id')
        ->join('tbl_item','tbl_item.item_id','=','tbl_order_details.item_id')
        ->selectRaw('tbl_order_details.*, tbl_item.* ')
        ->where('tbl_order_details.id', $order_id)
        ->orderBy('tbl_order_details.id','dsc')
        ->get();

        $billing_address = DB::table('tbl_customer_address_books')
        ->where('tbl_customer_address_books.id', $customer->billing_address_id)
        ->first();

        $delivery_address = DB::table('tbl_customer_address_books')
        ->where('tbl_customer_address_books.id', $customer->delivery_address_id)
        ->first();


        return view('frontend.my-account.order-information', compact('orderObj','order_details','billing_address','delivery_address','order_history'));
    }

}
