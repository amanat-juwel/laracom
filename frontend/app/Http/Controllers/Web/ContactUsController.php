<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Item;
use App\Category;
use App\Subcategory;
use App\SubSubcategory;
use App\Brand;
use App\Unit;
use App\StockMaster;
use DB;
use Input;
use Validator;
use Auth;
use App\Batch;
use App\Stock;

class ContactUsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function index()
    {
        
        return view('frontend.contact-us.index');
    }


}
