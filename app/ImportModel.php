<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class ImportModel extends Model
{
    protected $table = 'imports';
    protected $primaryKey = 'id';
    protected $fillable = ['po','item','barcode','quantity','price','unit_count','total_price','crate','created','updated'];
    // const CREATED_AT = 'created';
    // const UPDATED_AT = 'updated';
    public $timestamps = false;
}
