<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use App\Item;
use App\Customer;
use App\Stock;

class PaymentNotificationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function paymentNotification($value){

        $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();

        $old_date = $globalSettings->next_due_date;
        $next_due_date = date('Y-m-d', strtotime($old_date. ' +1 month'));

        $old_service_hault_date = $globalSettings->next_service_hault_date;
        $next_service_hault_date = date('Y-m-d', strtotime($old_service_hault_date. ' +1 month'));

        
        if($value=='off'){
            //check if it is the first time of the month
            if(date('m') == date('m',strtotime($old_date)))
            {   
                DB::table('settings')
                ->where('setting_id', 1)
                ->update([
                    'payment_notification' => "$value", 
                    'next_due_date' => $next_due_date, 
                    'next_service_hault_date' => $next_service_hault_date
                ]);

                $formattedDate = date('M d, Y', strtotime($next_due_date));
                return "Payment notification is turned off for $globalSettings->company_name. Next due date: ".$formattedDate;
            }
            else{
                return "Payment notification has already turned off for $globalSettings->company_name. Next due date: ".date('M d, Y', strtotime($old_date));
            }
            
        }
        else{
            return 0;
        }

    }

     public function paymentStatus(){
        $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();
        return $globalSettings->payment_notification;
     }
    
}
