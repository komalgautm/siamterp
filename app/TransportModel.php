<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportModel extends Model
{
    protected $table = 'transport';
    protected $primarykey ='id';
    protected $fillable =['id','vendor','small_max_cbm','small_max_weight','small_pallet','small_price','medium_max_cbm','medium_max_weight','medium_pallet','medium_price','large_max_cbm','large_max_weight','large_pallet','large_price','jumbo_max_cbm','jumbo_max_weight','jumbo_pallet','jumbo_price','status','created','updated'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamps = false;
}
