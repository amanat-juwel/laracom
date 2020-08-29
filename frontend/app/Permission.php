<?php

namespace App;
use Auth;
use DB;

class Permission 
{    

    public function getPermission($table_name){
        $role_id = Auth::user()->role_id;

        $permissions = DB::table("permissions")
        ->where('table_name', "$table_name")
        ->get();

        $permission_array = array();
        foreach ($permissions as $key => $value) {
            $permission_role = DB::table("permission_role")
            ->join('permissions','permissions.permission_id','=','permission_role.permission_id')
            ->where('permission_role.permission_id', $value->permission_id)
            ->where('permission_role.role_id', $role_id)
            ->first();
            if(isset($permission_role)){
                array_push($permission_array, $permission_role->permission);
            }
        }
        return  $permission_array;
    }
}
