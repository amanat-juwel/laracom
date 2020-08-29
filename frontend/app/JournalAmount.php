<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalAmount extends Model
{
    protected $table = 'journal_amounts';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
