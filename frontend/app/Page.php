<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function authorInfo(){
    	return $this->belongsTo('App\User', 'author_id');
    }
}
