<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Role;
use App\PermissionRole;

use DB;
use Exception;

use Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {

        $roles = DB::table('roles')
        ->orderBy('role_id', 'asc')
        ->get();

        return view('admin.role.index', compact('roles'));
    }


    public function create()
    {
        $tables = DB::table('permissions')
        ->groupBy('table_name')
        ->get();

        $permissions = DB::table('permissions')
        ->get();

        return view('admin.role.create',compact('tables','permissions'));
    }


    public function store(Request $request)
    {   
        $role = new Role;
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->save();

        for($i=0; $i<count($request->permission_id); $i++){
            $permission_role = new PermissionRole;
            $permission_role->permission_id = $request->permission_id[$i];
            $permission_role->role_id = $role->role_id;
            $permission_role->save();
        }

        return redirect('admin//roles')->with('success','New Role Added');
    }


    public function edit($role_id)
    {
        $role = Role::find($role_id);
        $permission_roles = PermissionRole::where('role_id',$role->role_id)->get();

        $tables = DB::table('permissions')
        ->groupBy('table_name')
        ->get();

        $permissions = DB::table('permissions')
        ->get();

        return view('admin.role.edit',compact('tables','permissions','permission_roles','role'));

    }

    public function update(Request $request, $role_id)
    {

        $role = Role::find($role_id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->update();

        $permission_roles = PermissionRole::where('role_id',$role_id)->delete();

        for($i=0; $i<count($request->permission_id); $i++){
            $permission_role = new PermissionRole;
            $permission_role->permission_id = $request->permission_id[$i];
            $permission_role->role_id = $role->role_id;
            $permission_role->save();
        }

        return redirect('admin//roles')->with('update','User Role Updated');
    }

    public function destroy($role_id)
    {
        $count = DB::table('users')
        ->where('role_id',$role_id)
        ->count();
        
        if($count==0){
           $role = Role::find($role_id);
           $role->delete();

            return redirect()->back()->with('success','Operation Successful');
        }
        else{
            return redirect()->back()->with('delete','User Role is in use!');
        }


    }
}
