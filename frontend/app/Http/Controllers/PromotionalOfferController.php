<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
class PromotionalOfferController extends Controller
{
    public function index(){
    	$customers = Customer::all();
    	return view('promotional_offer.index',['customers'=>$customers]);
    }
}
