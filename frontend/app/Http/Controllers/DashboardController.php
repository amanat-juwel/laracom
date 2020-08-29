<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;


class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        //$this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $verifed_users = DB::table('users')
        ->where('email_verified_at','!=',Null)
        ->where('is_blocked',0)
        ->count();
        $not_verifed_users = DB::table('users')
        ->where('email_verified_at',Null)
        ->where('is_blocked',0)
        ->count();
        $blocked_users = DB::table('users')
        ->where('is_blocked',1)
        ->count();
        $blog_count = DB::table('blog_posts')->count();
        $reviews_count = DB::table('reviews')->count();
        return view('admin.home',compact('verifed_users','not_verifed_users','blocked_users','blog_count','reviews_count'));
    }
}
