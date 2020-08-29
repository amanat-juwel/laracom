<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests;

use DB;
use Input;
use Validator;
use Auth;
use Cart;
use App\User;
use App\Customer;
use App\CustomerAddressBook;
use App\CustomerSystemUser;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{

    public function create(Request $request)
    {      

        $customer_id = $this->storeNewCustomer($request);

        if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])){
            return redirect('/');
        }

    }

    public function storeNewCustomer($request){

        $customerObj = Customer::all();
        if(count($customerObj)>0){
            $customer_code = Customer::all()->last()->customer_code;
        }
        else{
            $customer_code = 0;
        }

        $customer = new Customer;
        $customer->customer_code = ++$customer_code;
        $customer->customer_name = $request->name;
        $customer->mobile_no = ($request->mobile_no!='')? "88".$request->mobile_no : NULL;
        $customer->gender = $request->gender;
        $customer->category = 1;
        ($request->debit!='')?$customer->op_bal_debit = $request->debit : $x=1;
        ($request->credit!='')?$customer->op_bal_credit = $request->credit : $x=1;
        $customer->save();

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->save();


        $customer_system_user = new CustomerSystemUser;
        $customer_system_user->customer_id = $customer->customer_id;
        $customer_system_user->user_id = $user->id;
        $customer_system_user->save();

        //store address
        $this->storeCustomerAddress($request, $customer->customer_id);

        return $customer->customer_id;

        
    }

    public function storeCustomerAddress($request, $customer_id){

        //billing address
        $address = new CustomerAddressBook;
        $address->customer_id = $customer_id;
        $address->fullname = $request->name_billing;
        $address->mobile = $request->mobile_no_billing;
        $address->address = $request->address_billing;
        $address->city = $request->city_billing;
        $address->postal_code = $request->postal_code_billing;
        $address->country = $request->country_billing;
        $address->save();
        $billing_address_id = $address->id;

        //delivery address
        if($request->shipping_address){
            //checked if billing address == delivery address
            $delivery_address_id = $billing_address_id;
        }
        else{
            $address = new CustomerAddressBook;
            $address->customer_id = $customer_id;
            $address->fullname = $request->name_delivery;
            $address->mobile = $request->mobile_no_delivery;
            $address->address = $request->address_delivery;
            $address->city = $request->city_delivery;
            $address->postal_code = $request->postal_code_delivery;
            $address->country = $request->country_delivery;
            $address->save();
            $delivery_address_id = $address->id;
        }

        $customer = Customer::find($customer_id);
        $customer->billing_address_id = $billing_address_id;
        $customer->delivery_address_id = $delivery_address_id;
        $customer->update();
    }

}
