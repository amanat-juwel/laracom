<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Purchase;
use App\OrderHistory;
use App\Order;
use App\OrderDetails;
use App\Brand;
use App\Customer;
use App\Supplier;
use App\SupplierLedger;
use App\CustomerLedger;
use App\Item;
use App\ItemUnit;
use App\StockLocation;
use App\PurchaseDetails;
use App\StockMaster;
use App\Stock;
use App\Log;
use App\BankTransaction;
use App\BankAccountLedger;
use App\Voucher;
use DB;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderShipped;

// use Illuminate\Support\Facades\Mail;
use Mail;
use Auth;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index()
    {

        $orders = DB::table('tbl_orders')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_orders.customer_id')
        ->select('tbl_orders.*','tbl_customer.customer_name',DB::raw('(SELECT sum(tbl_order_details.qty * tbl_order_details.rate)  FROM tbl_order_details WHERE tbl_orders.id = tbl_order_details.order_master_id
         GROUP BY tbl_orders.id) as total'))
        ->orderBy('tbl_orders.id','dsc')
        ->get();

        return view('admin.order.index' , compact('orders'));
    }

    public function show($id)
    {

        $order = DB::table('tbl_orders')
        ->join('tbl_customer','tbl_customer.customer_id','=','tbl_orders.customer_id')
        ->select('tbl_orders.*','tbl_customer.customer_name')
        ->where('tbl_orders.id',$id)
        ->first();

        $order_details = DB::table('tbl_order_details')
        ->join('tbl_orders','tbl_orders.id','=','tbl_order_details.order_master_id')
        ->join('tbl_item','tbl_item.item_id','=','tbl_order_details.item_id')
        ->select('tbl_order_details.*','tbl_item.item_name','tbl_item.item_code')
        ->where('tbl_orders.id',$id)
        ->get();

        $customer = Customer::find($order->customer_id);

        $billing_address = DB::table('tbl_customer_address_books')
        ->where('tbl_customer_address_books.id', $customer->billing_address_id)
        ->first();

        $delivery_address = DB::table('tbl_customer_address_books')
        ->where('tbl_customer_address_books.id', $customer->delivery_address_id)
        ->first();

        return view('admin.order.show' , compact('order','order_details','billing_address','delivery_address'));
    }


    public function paymentReceived($id)
    {
        $order = Order::find($id);
        $order->status = 'In-Process';
        $order->update();

        //order history
        $order_history = new OrderHistory;
        $order_history->order_id = $id;
        $order_history->status = $order->status;
        $order_history->save();

        return redirect()->back()->with('success','Updated Order #'.$id);
    }

    public function orderCancled($id)
    {
        $order = Order::find($id);
        $order->status = 'Cancled';
        $order->update();

        //order history
        $order_history = new OrderHistory;
        $order_history->order_id = $id;
        $order_history->status = $order->status;
        $order_history->save();

        return redirect()->back()->with('success','Updated Order #'.$id);
    }

    public function orderDelivered($id)
    {
        $order = Order::find($id);
        $order->status = 'Delivered';
        $order->update();

        //order history
        $order_history = new OrderHistory;
        $order_history->order_id = $id;
        $order_history->status = $order->status;
        $order_history->save();

        return redirect()->back()->with('success','Updated Order #'.$id);
    }

    public function deleteOrder($id)
    {
        $order_history = OrderHistory::where('order_id',$id)->delete();

        $order_details = OrderDetails::where('order_master_id',$id)->delete();

        $order = Order::find($id);
        $order->delete();

        return redirect()->back()->with('success','Updated Order #'.$id);
    }


}
