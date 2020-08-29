<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddToWishlist extends Model
{
    protected $table = 'wishlists';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function user(){
    	return $this->belongsTo('App\User', 'user_id')->withDefault([
            'name' => 'Guest User'
        ]);
    }

}
