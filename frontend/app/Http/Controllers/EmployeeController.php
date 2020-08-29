<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Expense;
use App\ExpenseHead;
use DB;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $employees = DB::table('employees')
        ->orderBy('name')
        ->get();
        return view('employee.index', compact('employees'));
    }


    public function create()
    {
     
        return view('employee.create');
    }


    public function store(Request $request)
    {
    	
    	$employee = New Employee();
    	$employee->name = $request->name;
        $employee->department = $request->department;
    	$employee->designation = $request->designation;
    	$employee->email = $request->email;
    	$employee->phone = $request->phone;
    	$employee->address = $request->address;
    	$employee->nid = $request->nid;
    	$employee->joining_date = $request->joining_date;
    	$employee->is_active = 1;
    	$employee->save();

    	return redirect('/human-resource')->with('success','New Executive Added');
        
    }

    public function show($id)
    {
        
    }


    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {


    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();

        return redirect('/human-resource')->with('success','Data Deleted');
    }
}
