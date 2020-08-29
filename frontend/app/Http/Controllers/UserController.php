<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;

use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function tableName(){

        $obj = new Admin;
        return $table_name = $obj->getTable();
    }

    public function index()
    {   
        $users = DB::table('admins')
        ->leftJoin('roles','roles.role_id','=','admins.role_id')
        ->selectRaw('admins.*,roles.display_name as role')
        ->get();

        $roles = DB::table('roles')->get();

        /* START GET USER PERMISSION  */
        $permission = new \App\Permission;
        $user_permissions = $permission->getPermission($this->tableName());
        
        if(!in_array("browse", $user_permissions)){
            return view('errors.permission');
        }
        /* END GET USER PERMISSION  */


        

        return view('admin.users.index', compact('users','roles','user_permissions'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        
        $user = new Admin;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect('admin/users/manage-users')->with('success','New User Added');
    }



    public function show($id)
    {
        //
    }

    public function edit($id)
    {   
        /* START GET USER PERMISSION  */
        $permission = new \App\Permission;
        $user_permissions = $permission->getPermission($this->tableName());
        if(!in_array("update", $user_permissions)){
            return view('errors.permission');
        }
        /* END GET USER PERMISSION  */

        $user = Admin::find($id);
        $roles = DB::table('roles')->get();
        return view('admin.users.edit',compact('user','roles'))->with('id',$id);
    }


    public function update(Request $request, $id)
    {
        $user = Admin::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        if($request->input('password') != $request->input('confirm_password')){
            return view('users.edit',compact('user'))->with('id',$id);
        }
        elseif($request->input('password') != ''){
            $user->password = bcrypt($request->input('password'));
        }
        $user->update();

        return redirect('admin/users/manage-users')->with('update','User Info Updated');
    }


    public function destroy($id)
    {
        $user = Admin::find($id);
        $user->delete();

        return redirect('admin/users/manage-users')->with('delete','User Deleted');
    }


}
