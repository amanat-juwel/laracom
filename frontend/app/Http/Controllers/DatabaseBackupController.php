<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;

class DatabaseBackupController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
         
   
         $directories = Storage::disk('public')->files('backup');
          
       return view('admin.database.index',compact('directories'));
    }
    public function create(){

        date_default_timezone_set('Asia/Dhaka');
        $filename = "backup1-".date("Y-m-d-H-i-s").".sql";
        $mysqlPath = "D:\\xampp/mysql/bin/mysqldump";
        
        // use "mysqldump" isntead of "$mysqlPath" in server
        try{
            $command = "$mysqlPath --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  > " . storage_path() . "/app/public/backup/"  . $filename."  2>&1";
            $returnVar = NULL;
            $output  = NULL;
            exec($command, $output, $returnVar);
            return redirect()->back()->with('success','Database Export Successfull');
    
         }catch(Exception $e){
           // return "0 ".$e->errorInfo; //some error
            return redirect()->back()->with('success',$e->errorInfo);
         }
    }
    public function destroy(Request $request){
  
        $filePath = 'storage/app/public/'.$request->file ; 
            if(File::exists($filePath)){
                File::delete($filePath);
                return redirect()->back()->with('success','File Deleted');
            }else{
                return redirect()->back()->with('warning','Something Went Wrong');
            }

    }
}