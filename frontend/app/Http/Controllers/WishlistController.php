<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AddToWishlist;
use App\User;
use Auth;
use DB;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        $items = DB::table('wishlists')
        ->leftJoin('tbl_item','tbl_item.item_id','=','wishlists.item_id')
        ->leftJoin('tbl_category','tbl_category.cata_id','=','tbl_item.cata_id')
        ->leftJoin('tbl_sub_category','tbl_sub_category.id','=','tbl_item.sub_cata_id')
        ->leftJoin('tbl_brand','tbl_brand.brand_id','=','tbl_item.brand_id')
        ->leftJoin('tbl_unit','tbl_unit.id','=','tbl_item.unit_id')
        ->selectRaw('tbl_item.*,tbl_unit.name as unit,tbl_category.cata_name,tbl_brand.brand_name,tbl_sub_category.name as sub_cata_name')
        ->groupBy('tbl_item.item_name')
        ->orderBy('tbl_item.item_name', 'asc')
        ->where('wishlists.user_id', Auth::user()->id)
        ->get();

        return view('frontend.wishlist.index', compact('items'));
    }

    public function store(Request $request)
    {   
        // Check entry within table
        $count = DB::table('wishlists')
        ->selectRaw('count(id) as count')
        ->where('user_id', Auth::user()->id)
        ->where('item_id', $request->item_id)
        ->value('count');

        if($count == 0){
            $add_to_wishlist = new AddToWishlist;
            $add_to_wishlist->user_id = Auth::user()->id;
            $add_to_wishlist->item_id = $request->item_id; 
            $add_to_wishlist->save();
        }
        else{
            DB::table('wishlists')
            ->where('user_id', Auth::user()->id)
            ->where('item_id', $request->item_id)
            ->delete();
        }
        
        // count numbers of like and unlike in post
        $count = DB::table('wishlists')
        ->selectRaw('count(id) as count')
        ->where('item_id', $request->item_id)
        ->where('user_id', Auth::user()->id)
        ->value('count');

        if($request->redirect == 'true'){
            return redirect()->back()->with('success','Removed');
        }

        ($count > 0)? $message = '<i class="fa fa-heart text-red"></i> Added to Wishlist' : $message = '<i class="fa fa-heart-o"></i> Add to Wishlist';
        // initalizing array
        return response()->json([
                'message' => $message,
                ]);
    }

    public function destroy($item_id)
    {   
        DB::table('wishlists')
        ->where('user_id', Auth::user()->id)
        ->where('item_id', $item_id)
        ->delete();

        return redirect()->back()->with('success','Removed from Wishlist');
    }

}
