<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ITFdetailModel extends Model
{
    protected $table = 'itf_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['itf','type','item','unit','qty','cbm','minload','box_pallet','total_weight','weight','wrap_weight','created','updated'];
    // const CREATED_AT = 'created';
    // const UPDATED_AT = 'updated';
    public $timestamps = false;
}
