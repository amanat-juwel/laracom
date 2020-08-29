<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountGroup;
use DB;
use Auth;

class AccountGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    
    public function index(){

        $account_groups = AccountGroup::all()->sortBy('name');

        return view('bank.group.index',compact('account_groups'));
    }

    public function store(Request $request)
    {   
        $account_grp = new AccountGroup;
        $account_grp->name = $request->name;
        $account_grp->save();
        
        return redirect()->back()->with('success','Account Group Added');
    }

    public function edit(Request $request)
    {
        if($request->ajax())
        {
            $account_grp = AccountGroup::find($request->id);
            return Response($account_grp);
        }
    }

    public function update(Request $request)
    {
        $account_grp = AccountGroup::find($request->id);
        $account_grp->name = $request->name;
        $account_grp->update();
        
        return redirect()->back()->with('success','Account Group Added');
        
    }

    public function destroy(Request $request)
    {
        $count = DB::table('tbl_bank_account')
        ->where('account_group_id',$request->id)
        ->count();
        
        if($count==0){
            $account_grp = AccountGroup::find($request->id);
            $account_grp->delete();
            return redirect()->back()->with('success','Account Group Deleted');
        }
        else{
            return redirect()->back()->with('delete','Account Group is in use');
        }

        
    }

    
}
