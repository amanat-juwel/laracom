<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Customer;
use App\Supplier;
use App\Employee;
use DB;
use Validator;

class SmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function sendSms()
    {	
    	$customers = DB::table('tbl_customer')->orderBy('customer_name', 'asc')->get();
        $suppliers = DB::table('tbl_supplier')->orderBy('sup_name', 'asc')->get();
        $employees = DB::table('employees')->orderBy('name')->get();

        return view('sms.send-sms', compact('customers','suppliers','employees'));
    }

    public function sent()
    {   
        return view('sms.sent');
    }


}
