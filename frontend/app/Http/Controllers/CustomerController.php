<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\User;
use App\Customer;
use App\CustomerAddressBook;
use App\CustomerSystemUser;
use App\CustomerLedger;
use App\BankTransaction;
use App\BankAccountLedger;
use App\Voucher;
use Validator;
use DB;
use Image;
use Storage;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $customer = DB::table('tbl_customer')
        ->leftJoin('tbl_customer_ledger','tbl_customer_ledger.customer_id','=','tbl_customer.customer_id')
        ->leftJoin('tbl_customer_category','tbl_customer_category.id','=','tbl_customer.category')
        ->selectRaw('tbl_customer.*,SUM(credit) as credit,SUM(debit) as debit,tbl_customer_category.cat_name as category')
        ->groupBy('tbl_customer.customer_id')
        ->orderBy('customer_code', 'asc')
        ->get();
        
        return view('admin.customer.index', compact('customer'));
    }


    public function create()
    {
        $customer_categories = DB::table('tbl_customer_category')->get();
        return view('admin.customer.create',compact('customer_categories'));
    }


    public function store(Request $request)
    {
        ### IMAGE VALIDATION ###
        $validator = Validator::make($request->all(), [
                    'customer_name' => 'required',
                    'customer_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
            // return response()->json([
            // 'message' => "Invalid input",
            // 'class' => 'danger',
            // ]);
        }
        ### IMAGE VALIDATION ###

        //Handling Customer photo
        if($request->file('customer_image') != ""){
        $customerImage = $request->file('customer_image');
        $name = time().$customerImage->getClientOriginalName();
        $uploadPath = 'public/images/customer_images/';
        $customerImage->move($uploadPath,$name);
        $customerImageUrl = $uploadPath.$name;
        }
        else{
            $customerImageUrl = "";
        }

        $customer = Customer::all();
        if(count($customer)>0){
            $customer_code = Customer::all()->last()->customer_code;
        }
        else{
            $customer_code = 0;
        }
        //$customer = Customer::create($request->all());
        $customer = new Customer;
        $customer->customer_code = ++$customer_code;
        $customer->customer_name = $request->customer_name;
        $customer->mobile_no = ($request->mobile_no!='')? "88".$request->mobile_no : NULL;
        $customer->category = $request->category;
        $customer->customer_image = $customerImageUrl;
        ($request->debit!='')?$customer->op_bal_debit = $request->debit : $x=1;
        ($request->credit!='')?$customer->op_bal_credit = $request->credit : $x=1;
        $customer->save();

        $user = new User;
        $user->name = $request->customer_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->mobile_no);
        $user->role = 'admin';
        $user->save();


        $customer_system_user = new CustomerSystemUser;
        $customer_system_user->customer_id = $customer->customer_id;
        $customer_system_user->user_id = $user->id;
        $customer_system_user->save();

        //store address
        $this->storeCustomerAddress($request, $customer->customer_id);
        
        return redirect('admin/customer/')->with('success','New Customer Added');
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


    public function show($id)
    {
        $customerById = DB::table('tbl_customer')
        ->leftJoin('tbl_customer_category','tbl_customer_category.id','=','tbl_customer.category')
        ->where('customer_id', $id)
        ->first();

        $customer_ledgers = CustomerLedger::where('customer_id',$customerById->customer_id)->get();
        $debit = 0; $credit = 0;
        foreach ($customer_ledgers as $key => $value) {
            $debit += $value->debit;
            $credit += $value->credit;
        }
        $balance = $credit - $debit;

        return view('admin.customer.show',['customerById'=>$customerById,'balance'=>$balance]);
    }

    public function storeQuickCustomer($customer_name,$mobile_no,$address,$email,$category)
    {
        $globalSettings = DB::table('settings')->where('setting_id','=','1')->first();

        $customer = Customer::all();
        if(count($customer)>0){
            $customer_code = Customer::all()->last()->customer_code;
        }
        else{
            $customer_code = 0;
        }

        $customer = new Customer;
        $customer->customer_code = ++$customer_code;
        $customer->customer_name = $customer_name;
        $customer->mobile_no = "88".$mobile_no;
        $customer->address = str_replace("|||","/",$address);
        $customer->email = $email;
        $customer->category = $category;
        $customer->save();
        
        return response()->json([
                'customer_id' => $customer->customer_id,
                'customer_code' => $globalSettings->invoice_prefix."-".str_pad($customer->customer_code, 4, '0', STR_PAD_LEFT) ,
            ]);
    }

    public function edit($id)
    {
        $customers = Customer::find($id);
        $customer_ledger_by_id = DB::table('tbl_customer_ledger')
        ->select('tbl_customer_ledger.*')
        ->where('customer_id', $id)
        ->where('tran_ref_name', 'OpeningBalance')
        ->first();
        $customer_categories = DB::table('tbl_customer_category')->get();

        return view('admin.customer.edit',compact('customers','customer_ledger_by_id','customer_categories'))->with('id',$id);
    }


    public function update(Request $request, $id)
    {
        ### IMAGE VALIDATION ###
        $validator = Validator::make($request->all(), [
                    'customer_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
            // return response()->json([
            // 'message' => "Invalid input",
            // 'class' => 'danger',
            // ]);
        }
        ### IMAGE VALIDATION ###
        
        $customers = Customer::find($id);
        
        $customers->customer_name = $request->input('customer_name');
        $customers->mobile_no = $request->input('mobile_no');
        $customers->category = $request->input('category');
        // if($request->customer_image)
        // {
        //     $customer_image = $request->customer_image;
        //     $strImage = str_replace('data:image/jpeg;base64,', '', $customer_image);
        //     $strImage = str_replace(' ', '+', $strImage);
        //     $image = base64_decode($strImage);
        //     $source = imagecreatefromstring($image);
        //     $filename = time().'.jpg';
        //     $location = public_path('images/customer_images/'.$filename);
        //     Image::make($source)->resize(800, 400)->save($location);
        //     Storage::delete($customers->customer_image);
        //     $customers->customer_image = $filename;
        // }

        ($request->debit!='')?$customers->op_bal_debit = $request->debit : $x=1;
        ($request->credit!='')?$customers->op_bal_credit = $request->credit : $x=1;
        $customers->update();



        return redirect('admin/customer/')->with('update','Customer Info Updated');
    }


    public function destroy($id)
    {   
        $count = DB::table('tbl_customer_ledger')
        ->where('customer_id',$id)
        ->count();
        
        if($count==0){
            $customer = Customer::find($id);
            Storage::delete($customer->customer_image);
            $customer->delete();
            return redirect('admin/customer/')->with('delete','Customer Deleted');
        }
        else{
            return redirect()->back()->with('delete','Customer is in use!');
        }

        
    }

    public function transaction(Request $request)
    {
        $voucher_count = Voucher::whereDate("created_at",'=', date('Y-m-d'))->count();
        if($voucher_count == 0){
           echo $voucher_ref = date('Y-m-d')."-"."1";
        }
        else{
            $voucher = DB::table('vouchers')->orderBy('created_at', 'DESC')->first();
            $val = substr("$voucher->voucher_ref",11);
            ++$val;
            $voucher_ref = date('Y-m-d')."-"."$val";
            
        }

        $voucher = new Voucher;
        $voucher->voucher_ref = $voucher_ref;
        $voucher->type = "Sales";
        $voucher->save();

        if ($request->amount>0 && $request->type=="Receive"){
            $customer_ledger = new CustomerLedger;
            $customer_ledger->sales_master_id = 0;
            $customer_ledger->customer_id = $request->input('customer_id');
            $customer_ledger->tran_ref_id = 7;
            $customer_ledger->tran_ref_name = 'PreviousDuePaid';
            $customer_ledger->particulars = $request->description;
            $customer_ledger->debit = 0;
            $customer_ledger->credit = $request->input('amount');
            $customer_ledger->voucher_ref = $voucher_ref;
            $customer_ledger->transaction_date = $request->input('date');       
            $customer_ledger->save();
            $customer = Customer::where('customer_id',$request->customer_id)->first();
        }
        elseif ($request->amount>0 && $request->type=="Discount") {
            $customer_ledger = new CustomerLedger;
            $customer_ledger->sales_master_id = 0;
            $customer_ledger->customer_id = $request->input('customer_id');
            $customer_ledger->tran_ref_id = 3;
            $customer_ledger->tran_ref_name = 'Discount';
            $customer_ledger->particulars = $request->description;
            $customer_ledger->debit = 0;
            $customer_ledger->credit = $request->input('amount');
            $customer_ledger->voucher_ref = $voucher_ref;
            $customer_ledger->transaction_date = $request->input('date');       
            $customer_ledger->save();
            $customer = Customer::where('customer_id',$request->customer_id)->first();
        }

        # Start Bank Transaction
        if($request->amount>0){
            $transaction = new BankTransaction;
            $transaction->voucher_ref = $voucher_ref;
            $transaction->bank_account_id = $request->bank_account_id; //4=Cash in Hand //$request->bank_account_id;
            $transaction->transaction_date = $request->date;
            $transaction->transaction_description = $request->description;
            $transaction->deposit = $request->amount;
            $transaction->expense = 0;
            $transaction->save();
        }
        # End Bank Transaction

        //return view('expense.head.index')->with('success','New Expense Head Added');
        return redirect('/ledger/customer')->with('success','Success');
    }
}
